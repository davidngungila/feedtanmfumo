<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsMessageTemplate;
use App\Models\User;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SmsSendController extends Controller
{
    protected SmsNotificationService $smsService;

    public function __construct(SmsNotificationService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function index()
    {
        $templates = SmsMessageTemplate::orderBy('priority')->get();

        return view('admin.sms.send', compact('templates'));
    }

    /**
     * Preview Excel file and get column mapping
     */
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

                // Get sample data (next 3 rows)
                $sampleData = [];
                for ($i = 1; $i < min(4, count($rows)); $i++) {
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

    public function downloadSample()
    {
        $spreadsheet = new Spreadsheet;

        // Sheet 1: General Members
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Members');
        $sheet1->setCellValue('A1', 'Member ID');
        $sheet1->setCellValue('B1', 'Full Name');
        $sheet1->setCellValue('C1', 'Phone Number');
        $sheet1->setCellValue('D1', 'Saving Behavior');
        $sheet1->setCellValue('E1', 'Status');

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '015425']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet1->getStyle('A1:E1')->applyFromArray($headerStyle);

        $sampleData1 = [
            ['M001', 'John Doe', '255712345678', 'Regular Saver', 'Active'],
            ['M002', 'Jane Smith', '255723456789', 'Inconsistent Saver', 'Active'],
        ];
        $row = 2;
        foreach ($sampleData1 as $data) {
            $sheet1->setCellValue('A'.$row, $data[0]);
            $sheet1->setCellValue('B'.$row, $data[1]);
            $sheet1->setCellValue('C'.$row, $data[2]);
            $sheet1->setCellValue('D'.$row, $data[3]);
            $sheet1->setCellValue('E'.$row, $data[4]);
            $row++;
        }
        foreach (range('A', 'E') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }

        // Sheet 2: Loans
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Loans');
        $sheet2->setCellValue('A1', 'Loan ID');
        $sheet2->setCellValue('B1', 'Member ID');
        $sheet2->setCellValue('C1', 'Member Name');
        $sheet2->setCellValue('D1', 'Phone Number');
        $sheet2->setCellValue('E1', 'Loan Amount');
        $sheet2->setCellValue('F1', 'Outstanding Balance');
        $sheet2->setCellValue('G1', 'Due Date');
        $sheet2->getStyle('A1:G1')->applyFromArray($headerStyle);

        $sampleData2 = [
            ['L001', 'M001', 'John Doe', '255712345678', '500000', '250000', '2026-02-15'],
            ['L002', 'M002', 'Jane Smith', '255723456789', '300000', '150000', '2026-02-20'],
        ];
        $row = 2;
        foreach ($sampleData2 as $data) {
            $sheet2->setCellValue('A'.$row, $data[0]);
            $sheet2->setCellValue('B'.$row, $data[1]);
            $sheet2->setCellValue('C'.$row, $data[2]);
            $sheet2->setCellValue('D'.$row, $data[3]);
            $sheet2->setCellValue('E'.$row, $data[4]);
            $sheet2->setCellValue('F'.$row, $data[5]);
            $sheet2->setCellValue('G'.$row, $data[6]);
            $row++;
        }
        foreach (range('A', 'G') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        // Sheet 3: Savings
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Savings');
        $sheet3->setCellValue('A1', 'Account Number');
        $sheet3->setCellValue('B1', 'Member ID');
        $sheet3->setCellValue('C1', 'Member Name');
        $sheet3->setCellValue('D1', 'Phone Number');
        $sheet3->setCellValue('E1', 'Balance');
        $sheet3->setCellValue('F1', 'Last Deposit');
        $sheet3->setCellValue('G1', 'Last Deposit Date');
        $sheet3->getStyle('A1:G1')->applyFromArray($headerStyle);

        $sampleData3 = [
            ['SAV001', 'M001', 'John Doe', '255712345678', '150000', '50000', '2026-01-25'],
            ['SAV002', 'M002', 'Jane Smith', '255723456789', '200000', '30000', '2026-01-20'],
        ];
        $row = 2;
        foreach ($sampleData3 as $data) {
            $sheet3->setCellValue('A'.$row, $data[0]);
            $sheet3->setCellValue('B'.$row, $data[1]);
            $sheet3->setCellValue('C'.$row, $data[2]);
            $sheet3->setCellValue('D'.$row, $data[3]);
            $sheet3->setCellValue('E'.$row, $data[4]);
            $sheet3->setCellValue('F'.$row, $data[5]);
            $sheet3->setCellValue('G'.$row, $data[6]);
            $row++;
        }
        foreach (range('A', 'G') as $col) {
            $sheet3->getColumnDimension($col)->setAutoSize(true);
        }

        // Sheet 4: Investments
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('Investments');
        $sheet4->setCellValue('A1', 'Investment ID');
        $sheet4->setCellValue('B1', 'Member ID');
        $sheet4->setCellValue('C1', 'Member Name');
        $sheet4->setCellValue('D1', 'Phone Number');
        $sheet4->setCellValue('E1', 'Principal Amount');
        $sheet4->setCellValue('F1', 'Expected Return');
        $sheet4->setCellValue('G1', 'Maturity Date');
        $sheet4->getStyle('A1:G1')->applyFromArray($headerStyle);

        $sampleData4 = [
            ['INV001', 'M001', 'John Doe', '255712345678', '1000000', '1200000', '2026-06-30'],
            ['INV002', 'M002', 'Jane Smith', '255723456789', '800000', '960000', '2026-05-31'],
        ];
        $row = 2;
        foreach ($sampleData4 as $data) {
            $sheet4->setCellValue('A'.$row, $data[0]);
            $sheet4->setCellValue('B'.$row, $data[1]);
            $sheet4->setCellValue('C'.$row, $data[2]);
            $sheet4->setCellValue('D'.$row, $data[3]);
            $sheet4->setCellValue('E'.$row, $data[4]);
            $sheet4->setCellValue('F'.$row, $data[5]);
            $sheet4->setCellValue('G'.$row, $data[6]);
            $row++;
        }
        foreach (range('A', 'G') as $col) {
            $sheet4->getColumnDimension($col)->setAutoSize(true);
        }

        // Sheet 5: Welfare
        $sheet5 = $spreadsheet->createSheet();
        $sheet5->setTitle('Welfare');
        $sheet5->setCellValue('A1', 'Welfare ID');
        $sheet5->setCellValue('B1', 'Member ID');
        $sheet5->setCellValue('C1', 'Member Name');
        $sheet5->setCellValue('D1', 'Phone Number');
        $sheet5->setCellValue('E1', 'Welfare Type');
        $sheet5->setCellValue('F1', 'Amount');
        $sheet5->setCellValue('G1', 'Status');
        $sheet5->getStyle('A1:G1')->applyFromArray($headerStyle);

        $sampleData5 = [
            ['WEL001', 'M001', 'John Doe', '255712345678', 'Medical', '100000', 'Approved'],
            ['WEL002', 'M002', 'Jane Smith', '255723456789', 'Education', '150000', 'Pending'],
        ];
        $row = 2;
        foreach ($sampleData5 as $data) {
            $sheet5->setCellValue('A'.$row, $data[0]);
            $sheet5->setCellValue('B'.$row, $data[1]);
            $sheet5->setCellValue('C'.$row, $data[2]);
            $sheet5->setCellValue('D'.$row, $data[3]);
            $sheet5->setCellValue('E'.$row, $data[4]);
            $sheet5->setCellValue('F'.$row, $data[5]);
            $sheet5->setCellValue('G'.$row, $data[6]);
            $row++;
        }
        foreach (range('A', 'G') as $col) {
            $sheet5->getColumnDimension($col)->setAutoSize(true);
        }

        // Sheet 6: Birthday Data
        $sheet6 = $spreadsheet->createSheet();
        $sheet6->setTitle('Birthday');
        $sheet6->setCellValue('A1', 'Full Name');
        $sheet6->setCellValue('B1', 'Phone Number');
        $sheet6->setCellValue('C1', 'Date of Birth');
        $sheet6->setCellValue('D1', 'SWF Member');
        $sheet6->setCellValue('E1', 'SWF Number');
        $sheet6->setCellValue('F1', 'SMS Consent');
        $sheet6->setCellValue('G1', 'Preferred Language');
        $sheet6->getStyle('A1:G1')->applyFromArray($headerStyle);

        $sampleData6 = [
            ['John M. Peter', '255712345678', '1998-05-14', 'No', '', 'Yes', 'sw'],
            ['Jane Smith', '255723456789', '1990-12-25', 'Yes', 'SWF-23456', 'Yes', 'sw'],
            ['Michael Johnson', '255734567890', '1985-03-10', 'No', '', 'Yes', 'en'],
        ];
        $row = 2;
        foreach ($sampleData6 as $data) {
            $sheet6->setCellValue('A'.$row, $data[0]);
            $sheet6->setCellValue('B'.$row, $data[1]);
            $sheet6->setCellValue('C'.$row, $data[2]);
            $sheet6->setCellValue('D'.$row, $data[3]);
            $sheet6->setCellValue('E'.$row, $data[4]);
            $sheet6->setCellValue('F'.$row, $data[5]);
            $sheet6->setCellValue('G'.$row, $data[6]);
            $row++;
        }
        foreach (range('A', 'G') as $col) {
            $sheet6->getColumnDimension($col)->setAutoSize(true);
        }

        // Set first sheet as active
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="sms_bulk_upload_sample.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function uploadAndSend(Request $request)
    {
        // Handle test SMS send
        if ($request->has('test_template_id') && $request->has('test_phone')) {
            return $this->sendTestSms($request);
        }

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'sheet_index' => 'nullable|integer|min:0',
            'column_mapping' => 'required|json',
            'template_id' => 'nullable|exists:sms_message_templates,id',
            'custom_message' => 'nullable|string|max:1000',
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

            // Find required columns from mapping
            $phoneIndex = $this->findColumnIndex($headers, [$columnMapping['phone'] ?? 'phone number', 'phone', 'mobile']);

            if ($phoneIndex === null) {
                return back()->with('error', 'Phone Number column not found. Please check your column mapping.');
            }

            // Get template or use custom message
            $template = null;
            if ($request->template_id) {
                $template = SmsMessageTemplate::find($request->template_id);
            }

            $customMessage = $request->custom_message;

            if (! $template && ! $customMessage) {
                return back()->with('error', 'Please select a template or provide a custom message.');
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

                // Extract data based on column mapping
                $rowData = [];
                foreach ($columnMapping as $key => $columnName) {
                    $colIndex = $this->findColumnIndex($headers, [$columnName]);
                    if ($colIndex !== null && isset($row[$colIndex])) {
                        $rowData[$key] = trim((string) $row[$colIndex]);
                    } else {
                        $rowData[$key] = null;
                    }
                }

                $phone = $rowData['phone'] ?? null;

                if (! $phone) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$i}: Phone number is missing";

                    continue;
                }

                // Try to find user
                $user = null;
                if (isset($rowData['member_id']) && $rowData['member_id']) {
                    $user = User::where('member_number', $rowData['member_id'])
                        ->orWhere('membership_code', $rowData['member_id'])
                        ->first();
                }

                if (! $user && $phone) {
                    $user = User::where('phone', $phone)->first();
                }

                // Create temporary user object if not found
                if (! $user) {
                    $user = new User;
                    $user->name = $rowData['name'] ?? $rowData['member_name'] ?? 'Member';
                    $user->phone = $phone;
                    $user->member_number = $rowData['member_id'] ?? null;
                }

                // Build message with variable replacement
                $message = $customMessage ?? '';

                if ($template) {
                    $orgInfo = $this->smsService->getOrganizationInfo();
                    $message = $template->getMessageForUser($user, [
                        'organization_name' => $orgInfo['name'],
                    ]);
                }

                // Replace variables from Excel columns
                // Support {{column_name}} syntax
                foreach ($rowData as $key => $value) {
                    if ($value !== null) {
                        // Replace {{key}} and {{column_name}} patterns
                        $message = str_replace('{{'.$key.'}}', $value, $message);
                        $message = str_replace('{{'.str_replace('_', ' ', $key).'}}', $value, $message);

                        // Also support common variable names
                        if ($key === 'name' || $key === 'member_name') {
                            $message = str_replace('{{name}}', $value, $message);
                            $message = str_replace('{{full_name}}', $value, $message);
                        }
                        if ($key === 'member_id') {
                            $message = str_replace('{{member_id}}', $value, $message);
                        }
                        if (in_array($key, ['amount', 'loan_amount', 'balance', 'outstanding_balance', 'principal_amount'])) {
                            $message = str_replace('{{amount}}', number_format((float) $value), $message);
                        }
                    }
                }

                // If still no message, skip
                if (! $message) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$i}: No message could be generated";

                    continue;
                }

                // Send SMS
                $result = $this->smsService->sendSms($phone, $message, [
                    'template_id' => $template?->id ? (string) $template->id : null,
                    'saving_behavior' => $rowData['saving_behavior'] ?? null,
                ]);

                if ($result['success']) {
                    $results['success']++;
                    Log::info('Bulk SMS sent successfully', [
                        'phone' => $phone,
                        'member_id' => $rowData['member_id'] ?? null,
                        'template_id' => $template?->id,
                    ]);
                } else {
                    $results['failed']++;
                    $results['errors'][] = "Row {$i} ({$phone}): ".($result['error'] ?? 'Unknown error');
                    Log::error('Bulk SMS failed', [
                        'phone' => $phone,
                        'error' => $result['error'] ?? 'Unknown error',
                    ]);
                }
            }

            $message = "SMS sending completed. Success: {$results['success']}, Failed: {$results['failed']} out of {$results['total']} total.";

            if (! empty($results['errors'])) {
                $message .= "\n\nErrors:\n".implode("\n", array_slice($results['errors'], 0, 10));
                if (count($results['errors']) > 10) {
                    $message .= "\n... and ".(count($results['errors']) - 10).' more errors.';
                }
            }

            return back()->with('success', $message)->with('results', $results);

        } catch (\Exception $e) {
            Log::error('Bulk SMS upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Error processing Excel file: '.$e->getMessage());
        }
    }

    protected function findColumnIndex(array $headers, array $possibleNames): ?int
    {
        $headersLower = array_map('strtolower', array_map('trim', $headers));

        foreach ($possibleNames as $name) {
            $nameLower = strtolower(trim($name));
            $index = array_search($nameLower, $headersLower);
            if ($index !== false) {
                return $index;
            }
        }

        return null;
    }

    protected function matchBehavior(string $templateBehavior, string $userBehavior): bool
    {
        $templateBehavior = strtolower(trim($templateBehavior));
        $userBehavior = strtolower(trim($userBehavior));

        if ($templateBehavior === $userBehavior) {
            return true;
        }

        $mappings = [
            'inconsistent saver' => ['inconsistent', 'irregular'],
            'sporadic saver' => ['sporadic', 'occasional'],
            'non-saver' => ['non-saver', 'nonsaver', 'no saver'],
            'regular saver' => ['regular', 'consistent'],
        ];

        foreach ($mappings as $key => $variants) {
            if (in_array($templateBehavior, $variants) || $templateBehavior === $key) {
                foreach ($variants as $variant) {
                    if (stripos($userBehavior, $variant) !== false) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Send test SMS for a template
     */
    protected function sendTestSms(Request $request)
    {
        $request->validate([
            'test_template_id' => 'required|exists:sms_message_templates,id',
            'test_phone' => 'required|string',
            'test_message' => 'required|string',
        ]);

        try {
            $phone = $request->input('test_phone');
            $message = $request->input('test_message');

            $result = $this->smsService->sendSms($phone, $message, [
                'template_id' => $request->input('test_template_id'),
            ]);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test SMS sent successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Failed to send SMS',
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Test SMS error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }
}
