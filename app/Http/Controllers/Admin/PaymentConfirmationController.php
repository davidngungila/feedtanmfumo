<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentConfirmation;
use App\Models\SavingsAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentConfirmationController extends Controller
{
    public function index(Request $request)
    {
        // Start with base query - don't use with('user') as it might cause issues with null user_id
        $query = PaymentConfirmation::query()->latest();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                    ->orWhere('member_name', 'like', "%{$search}%")
                    ->orWhere('member_email', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $paymentConfirmations = $query->paginate(20);

        // Debug: Log the count and verify data exists
        $totalInDb = PaymentConfirmation::count();
        Log::info('Payment confirmations query result', [
            'total_in_database' => $totalInDb,
            'total_found' => $paymentConfirmations->total(),
            'current_page_count' => $paymentConfirmations->count(),
            'has_records' => $totalInDb > 0,
        ]);

        $stats = [
            'total' => PaymentConfirmation::count(),
            'total_amount' => PaymentConfirmation::sum('amount_to_pay') ?? 0,
            'today' => PaymentConfirmation::whereDate('created_at', today())->count(),
            'this_month' => PaymentConfirmation::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('admin.payment-confirmations.index', compact('paymentConfirmations', 'stats'));
    }

    public function show(PaymentConfirmation $paymentConfirmation)
    {
        $paymentConfirmation->load('user');

        return view('admin.payment-confirmations.show', compact('paymentConfirmation'));
    }

    public function upload()
    {
        return view('admin.payment-confirmations.upload');
    }

    public function previewExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());

            $sheets = [];
            $sheetNames = $spreadsheet->getSheetNames();

            foreach ($sheetNames as $sheetIndex => $sheetName) {
                $worksheet = $spreadsheet->getSheet($sheetIndex);
                $rows = $worksheet->toArray();

                if (empty($rows)) {
                    continue;
                }

                // Get headers (first row)
                $headers = array_map('trim', array_filter($rows[0] ?? []));

                // Get sample data (next 5 rows)
                $sampleData = [];
                for ($i = 1; $i < min(6, count($rows)); $i++) {
                    if (! empty(array_filter($rows[$i]))) {
                        $sampleData[] = array_slice($rows[$i], 0, count($headers));
                    }
                }

                $sheets[] = [
                    'name' => $sheetName,
                    'index' => $sheetIndex,
                    'headers' => $headers,
                    'sample_data' => $sampleData,
                    'row_count' => count($rows) - 1, // Exclude header
                ];
            }

            return response()->json([
                'success' => true,
                'sheets' => $sheets,
            ]);
        } catch (\Exception $e) {
            Log::error('Excel preview error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Error reading Excel file: '.$e->getMessage(),
            ], 400);
        }
    }

    public function processUpload(Request $request)
    {
        Log::info('Payment confirmation upload started', [
            'has_file' => $request->hasFile('excel_file'),
            'sheet_index' => $request->input('sheet_index'),
            'column_mapping' => $request->input('column_mapping'),
            'all_input' => $request->all(),
        ]);

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'sheet_index' => 'required',
            'column_mapping' => 'required|string',
        ], [
            'excel_file.required' => 'Please select an Excel file to upload.',
            'excel_file.file' => 'The uploaded file is not valid.',
            'excel_file.mimes' => 'The file must be an Excel file (.xlsx, .xls, or .csv).',
            'excel_file.max' => 'The file size must not exceed 10MB.',
            'sheet_index.required' => 'Please select a sheet from the Excel file.',
            'column_mapping.required' => 'Column mapping is required. Please map the columns.',
        ]);

        try {
            $file = $request->file('excel_file');

            if (! $file) {
                Log::error('No file uploaded');

                return redirect()->route('admin.payment-confirmations.upload')
                    ->with('error', 'No file was uploaded.');
            }

            Log::info('Loading Excel file', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);

            $spreadsheet = IOFactory::load($file->getRealPath());

            // Get selected sheet
            $sheetIndex = (int) $request->input('sheet_index', 0);
            $sheetNames = $spreadsheet->getSheetNames();

            Log::info('Sheet information', [
                'sheet_index' => $sheetIndex,
                'available_sheets' => $sheetNames,
                'total_sheets' => count($sheetNames),
            ]);

            if (! isset($sheetNames[$sheetIndex])) {
                Log::error('Sheet not found', ['sheet_index' => $sheetIndex, 'available_sheets' => $sheetNames]);

                return redirect()->route('admin.payment-confirmations.upload')
                    ->with('error', 'Selected sheet not found.');
            }

            $worksheet = $spreadsheet->getSheet($sheetIndex);
            $rows = $worksheet->toArray();

            Log::info('Excel rows loaded', [
                'total_rows' => count($rows),
                'first_row' => $rows[0] ?? null,
            ]);

            if (empty($rows) || count($rows) < 2) {
                Log::error('Excel file is empty or has no data rows', ['row_count' => count($rows)]);

                return redirect()->route('admin.payment-confirmations.upload')
                    ->with('error', 'Excel file is empty or has no data rows.');
            }

            // Get column mapping
            $columnMappingJson = $request->input('column_mapping');
            $columnMapping = json_decode($columnMappingJson, true);

            Log::info('Column mapping', [
                'raw_json' => $columnMappingJson,
                'parsed' => $columnMapping,
            ]);

            if (empty($columnMapping) || ! is_array($columnMapping)) {
                Log::error('Invalid column mapping', [
                    'raw' => $columnMappingJson,
                    'parsed' => $columnMapping,
                ]);

                return redirect()->route('admin.payment-confirmations.upload')
                    ->with('error', 'Invalid column mapping. Please ensure Member ID and Amount columns are mapped.');
            }

            // Get headers (first row)
            $headers = array_map('trim', array_filter($rows[0] ?? []));

            // Find required columns
            $memberIdIndex = $this->findColumnIndex($headers, [
                $columnMapping['member_id'] ?? 'member id',
                'member id',
                'id',
                'member_number',
                'membership_code',
            ]);

            $amountIndex = $this->findColumnIndex($headers, [
                $columnMapping['amount'] ?? 'amount to be paid',
                'amount to be paid',
                'amount',
                'amount to be paid. 2026',
            ]);

            // Try to find member name column (optional)
            $memberNameIndex = $this->findColumnIndex($headers, [
                $columnMapping['member_name'] ?? 'members name',
                'members name',
                'member name',
                'name',
            ]);

            // Try to find member type column (optional)
            $memberTypeIndex = $this->findColumnIndex($headers, [
                $columnMapping['member_type'] ?? 'member type',
                'member type',
                'type',
            ]);

            if ($memberIdIndex === null) {
                Log::error('Member ID column not found', [
                    'headers' => $headers,
                    'column_mapping' => $columnMapping,
                ]);

                return redirect()->route('admin.payment-confirmations.upload')
                    ->with('error', 'Member ID column not found. Please check your column mapping.');
            }

            if ($amountIndex === null) {
                Log::error('Amount column not found', [
                    'headers' => $headers,
                    'column_mapping' => $columnMapping,
                ]);

                return redirect()->route('admin.payment-confirmations.upload')
                    ->with('error', 'Amount column not found. Please check your column mapping.');
            }

            $results = [
                'total' => 0,
                'success' => 0,
                'failed' => 0,
                'errors' => [],
            ];

            Log::info('Starting to process rows', [
                'total_rows' => count($rows),
                'member_id_index' => $memberIdIndex,
                'amount_index' => $amountIndex,
                'member_name_index' => $memberNameIndex,
                'member_type_index' => $memberTypeIndex,
            ]);

            // Process data rows (skip header)
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                $results['total']++;

                $memberId = trim((string) ($row[$memberIdIndex] ?? ''));
                $amount = $this->parseAmount($row[$amountIndex] ?? 0);

                if (empty($memberId)) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$i}: Member ID is missing";

                    continue;
                }

                if ($amount <= 0) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$i}: Invalid amount";

                    continue;
                }

                // Try to find user (optional - don't fail if not found)
                $user = User::where('member_number', $memberId)
                    ->orWhere('membership_code', $memberId)
                    ->first();

                // Extract member name from Excel or use user name
                $memberName = '';
                if ($memberNameIndex !== null && isset($row[$memberNameIndex])) {
                    $memberName = trim((string) $row[$memberNameIndex]);
                }
                if (empty($memberName) && $user) {
                    $memberName = $user->name;
                }
                if (empty($memberName)) {
                    $memberName = "Member {$memberId}";
                }

                // Extract member type from Excel or use user type
                $memberType = null;
                if ($memberTypeIndex !== null && isset($row[$memberTypeIndex])) {
                    $memberType = trim((string) $row[$memberTypeIndex]);
                }
                if (empty($memberType) && $user) {
                    $memberType = $user->membershipType?->name;
                }

                // Get deposit balance (0 if no user)
                $depositBalance = 0;
                if ($user) {
                    $depositBalance = SavingsAccount::where('user_id', $user->id)
                        ->where('status', 'active')
                        ->sum('balance') ?? 0;
                }

                // Check if payment confirmation already exists for this member ID and amount
                $existing = PaymentConfirmation::where('member_id', $memberId)
                    ->where('amount_to_pay', $amount)
                    ->whereDate('created_at', today())
                    ->first();

                if ($existing) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$i}: Payment confirmation already exists for member ID '{$memberId}' with amount {$amount}";

                    continue;
                }

                // Create payment confirmation record (without distribution - member will fill it)
                try {
                    // Ensure amount is properly formatted as decimal
                    $amount = round((float) $amount, 2);

                    $data = [
                        'user_id' => $user?->id,
                        'member_id' => $memberId,
                        'member_name' => $memberName,
                        'member_type' => $memberType,
                        'amount_to_pay' => $amount,
                        'deposit_balance' => round((float) $depositBalance, 2),
                        'member_email' => $user?->email ?? '',
                        'notes' => 'Imported from Excel sheet',
                        // Initialize distribution fields to 0
                        'swf_contribution' => 0,
                        're_deposit' => 0,
                        'fia_investment' => 0,
                        'capital_contribution' => 0,
                        'loan_repayment' => 0,
                    ];

                    Log::debug('Creating payment confirmation', [
                        'row' => $i,
                        'member_id' => $memberId,
                        'amount' => $amount,
                        'data' => $data,
                    ]);

                    // Use DB transaction to ensure data integrity
                    DB::beginTransaction();

                    $paymentConfirmation = PaymentConfirmation::create($data);

                    // Verify immediately
                    if (! $paymentConfirmation || ! $paymentConfirmation->id) {
                        DB::rollBack();
                        throw new \Exception('Failed to create payment confirmation - no ID returned');
                    }

                    // Commit the transaction
                    DB::commit();

                    // Verify the record exists in database
                    $savedRecord = PaymentConfirmation::find($paymentConfirmation->id);
                    if (! $savedRecord) {
                        $results['failed']++;
                        $results['errors'][] = "Row {$i}: Record created but not found in database for member ID '{$memberId}'";
                        Log::error('Payment confirmation not found after creation', [
                            'row' => $i,
                            'member_id' => $memberId,
                            'payment_confirmation_id' => $paymentConfirmation->id,
                        ]);

                        continue;
                    }

                    $results['success']++;
                    Log::info('Payment confirmation created successfully', [
                        'row' => $i,
                        'member_id' => $memberId,
                        'amount' => $amount,
                        'payment_confirmation_id' => $paymentConfirmation->id,
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    if (DB::transactionLevel() > 0) {
                        DB::rollBack();
                    }
                    $results['failed']++;
                    $errorMsg = $e->getMessage();
                    $results['errors'][] = "Row {$i}: Database error - ".$errorMsg;
                    Log::error('Payment confirmation import database error', [
                        'row' => $i,
                        'member_id' => $memberId,
                        'error' => $errorMsg,
                        'sql' => $e->getSql() ?? 'N/A',
                        'bindings' => $e->getBindings() ?? [],
                        'trace' => $e->getTraceAsString(),
                    ]);
                } catch (\Exception $e) {
                    if (DB::transactionLevel() > 0) {
                        DB::rollBack();
                    }
                    $results['failed']++;
                    $errorMsg = $e->getMessage();
                    $results['errors'][] = "Row {$i}: Error creating record - ".$errorMsg;
                    Log::error('Payment confirmation import error', [
                        'row' => $i,
                        'member_id' => $memberId,
                        'error' => $errorMsg,
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            // Log final results
            Log::info('Payment confirmation import completed', [
                'total' => $results['total'],
                'success' => $results['success'],
                'failed' => $results['failed'],
            ]);

            // Verify records were actually saved (check last 10 minutes to avoid conflicts)
            $savedCount = PaymentConfirmation::where('notes', 'Imported from Excel sheet')
                ->where('created_at', '>=', now()->subMinutes(10))
                ->count();

            // Also get total count for reference
            $totalInDb = PaymentConfirmation::count();

            Log::info('Payment confirmation import verification', [
                'success_count' => $results['success'],
                'saved_count' => $savedCount,
                'total_in_db' => $totalInDb,
            ]);

            // Build success message
            $message = "✅ Import completed successfully!\n\n";
            $message .= "📊 Results:\n";
            $message .= "• Total rows processed: {$results['total']}\n";
            $message .= "• Successfully imported: {$results['success']}\n";
            $message .= "• Failed: {$results['failed']}\n";

            if ($savedCount >= $results['success']) {
                $message .= "\n✅ Verified: {$savedCount} record(s) successfully saved to database.";
            } elseif ($results['success'] > 0 && $savedCount > 0) {
                $message .= "\n✅ Verified: {$savedCount} record(s) saved to database (some may have been created earlier).";
            } elseif ($results['success'] > 0) {
                $message .= "\n⚠️ Warning: {$results['success']} records were reported as created, but verification found {$savedCount} records. Please check the logs.";
            }

            if (! empty($results['errors']) && $results['failed'] > 0) {
                $errorCount = min(10, count($results['errors']));
                $message .= "\n\n⚠️ Errors (showing first {$errorCount}):\n";
                $message .= implode("\n", array_slice($results['errors'], 0, $errorCount));
                if (count($results['errors']) > $errorCount) {
                    $message .= "\n... and ".(count($results['errors']) - $errorCount).' more errors.';
                }
            }

            if ($results['success'] > 0) {
                // Redirect to index page to show the imported data
                return redirect()->route('admin.payment-confirmations.index')
                    ->with('success', $message)
                    ->with('results', $results);
            } else {
                // If no records were created, stay on upload page with error
                return redirect()->route('admin.payment-confirmations.upload')
                    ->with('error', $message)
                    ->with('results', $results);
            }
        } catch (\Exception $e) {
            Log::error('Payment confirmation import error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('admin.payment-confirmations.upload')
                ->with('error', 'Error processing Excel file: '.$e->getMessage());
        }
    }

    public function downloadSample()
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = ['S/N', 'Members Name', 'Member type', 'ID', 'Amount to be paid. 2026'];
        $sheet->fromArray($headers, null, 'A1');

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '015425'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Sample data
        $sampleData = [
            [1, 'John Doe', 'Ordinary', 'MEM001', 50000],
            [2, 'Jane Smith', 'Associate', 'MEM002', 75000],
            [3, 'Bob Johnson', 'Ordinary', 'MEM003', 100000],
        ];
        $sheet->fromArray($sampleData, null, 'A2');

        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="payment_confirmation_template.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    protected function findColumnIndex(array $headers, array $possibleNames): ?int
    {
        $headersLower = array_map('strtolower', array_map('trim', $headers));

        foreach ($possibleNames as $name) {
            $nameLower = strtolower(trim($name));
            $index = array_search($nameLower, $headersLower, true);

            if ($index !== false) {
                return $index;
            }
        }

        return null;
    }

    protected function parseAmount($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Remove currency symbols and commas
        $cleaned = preg_replace('/[^\d.]/', '', (string) $value);

        return (float) $cleaned;
    }
}
