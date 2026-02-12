<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentConfirmation;
use App\Models\SavingsAccount;
use App\Models\User;
use Illuminate\Http\Request;
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
        $query = PaymentConfirmation::with('user')->latest();

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

        $stats = [
            'total' => PaymentConfirmation::count(),
            'total_amount' => PaymentConfirmation::sum('amount_to_pay'),
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
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'sheet_index' => 'nullable|integer|min:0',
            'column_mapping' => 'required|json',
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());

            // Get selected sheet
            $sheetIndex = $request->input('sheet_index', 0);
            $sheetNames = $spreadsheet->getSheetNames();

            if (! isset($sheetNames[$sheetIndex])) {
                return back()->with('error', 'Selected sheet not found.');
            }

            $worksheet = $spreadsheet->getSheet($sheetIndex);
            $rows = $worksheet->toArray();

            if (empty($rows) || count($rows) < 2) {
                return back()->with('error', 'Excel file is empty or has no data rows.');
            }

            // Get column mapping
            $columnMapping = json_decode($request->input('column_mapping'), true);

            if (empty($columnMapping)) {
                return back()->with('error', 'Column mapping is required.');
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
                return back()->with('error', 'Member ID column not found. Please check your column mapping.');
            }

            if ($amountIndex === null) {
                return back()->with('error', 'Amount column not found. Please check your column mapping.');
            }

            $results = [
                'total' => 0,
                'success' => 0,
                'failed' => 0,
                'errors' => [],
            ];

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
                    PaymentConfirmation::create([
                        'user_id' => $user?->id,
                        'member_id' => $memberId,
                        'member_name' => $memberName,
                        'member_type' => $memberType,
                        'amount_to_pay' => $amount,
                        'deposit_balance' => $depositBalance,
                        'member_email' => $user?->email ?? '',
                        'notes' => 'Imported from Excel sheet',
                    ]);

                    $results['success']++;
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$i}: Error creating record - ".$e->getMessage();
                    Log::error('Payment confirmation import error', [
                        'row' => $i,
                        'member_id' => $memberId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $message = "Import completed. Success: {$results['success']}, Failed: {$results['failed']} out of {$results['total']} total.";

            if (! empty($results['errors'])) {
                $message .= "\n\nErrors:\n".implode("\n", array_slice($results['errors'], 0, 20));
                if (count($results['errors']) > 20) {
                    $message .= "\n... and ".(count($results['errors']) - 20).' more errors.';
                }
            }

            return back()->with('success', $message)->with('results', $results);
        } catch (\Exception $e) {
            Log::error('Payment confirmation import error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Error processing Excel file: '.$e->getMessage());
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
