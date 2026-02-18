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
use App\Helpers\PdfHelper;

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

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No file was uploaded.',
                    ], 400);
                }

                return redirect()->route('admin.payment-confirmations.upload')
                    ->with('error', 'No file was uploaded.');
            }

            Log::info('Loading Excel file', [
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);

            $spreadsheet = IOFactory::load($file->getRealPath());

            // Get selected sheet - handle both string and integer
            $sheetIndexInput = $request->input('sheet_index');
            $sheetIndex = is_numeric($sheetIndexInput) ? (int) $sheetIndexInput : 0;
            $sheetNames = $spreadsheet->getSheetNames();

            Log::info('Sheet information', [
                'sheet_index' => $sheetIndex,
                'available_sheets' => $sheetNames,
                'total_sheets' => count($sheetNames),
            ]);

            if (! isset($sheetNames[$sheetIndex])) {
                Log::error('Sheet not found', ['sheet_index' => $sheetIndex, 'available_sheets' => $sheetNames]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected sheet not found.',
                    ], 400);
                }

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

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Excel file is empty or has no data rows.',
                    ], 400);
                }

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

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid column mapping. Please ensure Member ID and Amount columns are mapped.',
                    ], 400);
                }

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

            // Find distribution columns (optional)
            $swfIndex = $this->findColumnIndex($headers, [
                $columnMapping['swf_contribution'] ?? 'swf deduction',
                'swf deduction',
                'swf',
                'social welfare',
            ]);

            $loanIndex = $this->findColumnIndex($headers, [
                $columnMapping['loan_repayment'] ?? 'loan installment',
                'loan installment',
                'loan repayment',
                'loan',
            ]);

            $capitalIndex = $this->findColumnIndex($headers, [
                $columnMapping['capital_contribution'] ?? 'capital contribution',
                'capital contribution',
                'capital',
                'share',
            ]);

            $fineIndex = $this->findColumnIndex($headers, [
                $columnMapping['fine_penalty'] ?? 'fine/penalty',
                'fine/penalty',
                'fine',
                'penalty',
            ]);

            if ($memberIdIndex === null) {
                Log::error('Member ID column not found', [
                    'headers' => $headers,
                    'column_mapping' => $columnMapping,
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Member ID column not found. Please check your column mapping.',
                    ], 400);
                }

                return redirect()->route('admin.payment-confirmations.upload')
                    ->with('error', 'Member ID column not found. Please check your column mapping.');
            }

            if ($amountIndex === null) {
                Log::error('Amount column not found', [
                    'headers' => $headers,
                    'column_mapping' => $columnMapping,
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Amount column not found. Please check your column mapping.',
                    ], 400);
                }

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
                'swf_index' => $swfIndex,
                'loan_index' => $loanIndex,
                'capital_index' => $capitalIndex,
                'fine_index' => $fineIndex,
            ]);

            // Use a single transaction for all inserts
            DB::beginTransaction();

            try {
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

                    // Try to find user (strictly using member_number or membership_code)
                    $user = User::where('membership_code', $memberId)
                        ->orWhere('member_number', $memberId)
                        ->first();

                    // If user found, strictly use their membership_code as the ID for consistency
                    if ($user && $user->membership_code) {
                        $memberId = $user->membership_code;
                    }

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
                    // Check outside transaction to avoid locking issues
                    $existing = PaymentConfirmation::where('member_id', $memberId)
                        ->where('amount_to_pay', $amount)
                        ->whereDate('created_at', today())
                        ->first();

                    if ($existing) {
                        $results['failed']++;
                        $results['errors'][] = "Row {$i}: Payment confirmation already exists for member ID '{$memberId}' with amount {$amount}";
                        Log::info('Skipping duplicate payment confirmation', [
                            'row' => $i,
                            'member_id' => $memberId,
                            'amount' => $amount,
                        ]);

                        continue;
                    }

                    // Create payment confirmation record (without distribution - member will fill it)
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
                        'member_email' => $user?->email ?? '',
                        'notes' => 'Imported from Excel sheet',
                        
                        // Populate distribution fields based on Excel mapping
                        'swf_contribution' => ($swfIndex !== null && isset($row[$swfIndex])) ? $this->parseAmount($row[$swfIndex]) : 0,
                        're_deposit' => 0, // Calculated by member
                        'fia_investment' => 0, // Calculated by member
                        'capital_contribution' => ($capitalIndex !== null && isset($row[$capitalIndex])) ? $this->parseAmount($row[$capitalIndex]) : 0,
                        'loan_repayment' => ($loanIndex !== null && isset($row[$loanIndex])) ? $this->parseAmount($row[$loanIndex]) : 0,
                        'fine_penalty' => ($fineIndex !== null && isset($row[$fineIndex])) ? $this->parseAmount($row[$fineIndex]) : 0,
                    ];

                    Log::debug('Creating payment confirmation', [
                        'row' => $i,
                        'member_id' => $memberId,
                        'amount' => $amount,
                        'data' => $data,
                    ]);

                    $paymentConfirmation = PaymentConfirmation::create($data);

                    // Verify immediately
                    if (! $paymentConfirmation || ! $paymentConfirmation->id) {
                        throw new \Exception("Row {$i}: Failed to create payment confirmation - no ID returned for member ID '{$memberId}'");
                    }

                    $results['success']++;
                    Log::info('Payment confirmation created successfully', [
                        'row' => $i,
                        'member_id' => $memberId,
                        'amount' => $amount,
                        'payment_confirmation_id' => $paymentConfirmation->id,
                    ]);
                }

                // Commit all inserts at once
                DB::commit();
                Log::info('All payment confirmations committed to database', [
                    'success_count' => $results['success'],
                ]);

            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                $results['failed']++;
                $errorMsg = $e->getMessage();
                $results['errors'][] = 'Database error: '.$errorMsg;
                Log::error('Payment confirmation import database error', [
                    'error' => $errorMsg,
                    'sql' => $e->getSql() ?? 'N/A',
                    'bindings' => $e->getBindings() ?? [],
                    'trace' => $e->getTraceAsString(),
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                $results['failed']++;
                $errorMsg = $e->getMessage();
                $results['errors'][] = 'Error: '.$errorMsg;
                Log::error('Payment confirmation import error', [
                    'error' => $errorMsg,
                    'trace' => $e->getTraceAsString(),
                ]);
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

            // If AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => $results['success'] > 0,
                    'message' => $message,
                    'results' => $results,
                    'redirect' => $results['success'] > 0
                        ? route('admin.payment-confirmations.index')
                        : null,
                ], $results['success'] > 0 ? 200 : 422);
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
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // If AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error processing Excel file: '.$e->getMessage(),
                    'error' => $e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.payment-confirmations.upload')
                ->with('error', 'Error processing Excel file: '.$e->getMessage());
        }
    }

    public function downloadSample()
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = ['S/N', 'Members Name', 'Member type', 'ID', 'Amount', 'SWF deduction', 'Loan installment', 'Capital contribution', 'Fine/Penalty', 'Net Payment'];
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
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        // Sample data
        $sampleData = [
            [1, 'John Doe', 'Ordinary', 'MEM001', 100000, 5000, 10000, 10000, 0, 75000],
            [2, 'Jane Smith', 'Associate', 'MEM002', 200000, 10000, 20000, 20000, 5000, 145000],
            [3, 'Bob Johnson', 'Ordinary', 'MEM003', 150000, 7500, 15000, 15000, 0, 112500],
        ];
        $sheet->fromArray($sampleData, null, 'A2');

        // Auto-size columns
        foreach (range('A', 'J') as $col) {
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

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:payment_confirmations,id',
        ]);

        $ids = $request->input('ids');
        $deleted = PaymentConfirmation::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.payment-confirmations.index')
            ->with('success', "Successfully deleted {$deleted} payment confirmation(s).");
    }

    public function exportExcel(Request $request)
    {
        $query = PaymentConfirmation::query()->latest();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                    ->orWhere('member_name', 'like', "%{$search}%")
                    ->orWhere('member_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $confirmations = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Payment Confirmations');

        // Headers
        $headers = [
            'ID', 'Date', 'Member ID', 'Member Name', 'Member Type', 'Email',
            'Amount to Pay', 'SWF', 'Loan', 'Capital', 'Fine', 'FIA', 'Re-deposit',
            'Net Payment', 'Method', 'Bank Account', 'Mobile Phone', 'Status'
        ];
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
        $sheet->getStyle('A1:R1')->applyFromArray($headerStyle);

        // Data
        $data = [];
        foreach ($confirmations as $c) {
            $data[] = [
                $c->id,
                $c->created_at->format('Y-m-d H:i'),
                $c->member_id,
                $c->member_name,
                $c->member_type,
                $c->member_email,
                $c->amount_to_pay,
                $c->swf_contribution,
                $c->loan_repayment,
                $c->capital_contribution,
                $c->fine_penalty,
                $c->fia_investment,
                $c->re_deposit,
                $c->cash_amount, // Use the accessor
                $c->payment_method ?: 'N/A',
                $c->bank_account_number ?: 'N/A',
                $c->mobile_number ?: 'N/A',
                $c->payment_method ? 'Confirmed' : 'Pending'
            ];
        }
        $sheet->fromArray($data, null, 'A2');

        // Auto-size columns
        foreach (range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'payment_confirmations_export_' . date('Ymd_His') . '.xlsx';

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportPdf(Request $request)
    {
        $query = PaymentConfirmation::query()->latest();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('member_id', 'like', "%{$search}%")
                    ->orWhere('member_name', 'like', "%{$search}%")
                    ->orWhere('member_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $confirmations = $query->get();

        return PdfHelper::downloadPdf('admin.payment-confirmations.reports-pdf', [
            'confirmations' => $confirmations,
            'documentTitle' => 'Payment Confirmations Report',
            'documentSubtitle' => 'Full summary of payouts and distributions',
        ], 'payment-confirmations-' . date('Y-m-d-His') . '.pdf', 'a4', 'landscape');
    }
}

