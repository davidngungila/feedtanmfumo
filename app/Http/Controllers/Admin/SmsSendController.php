<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsMessageTemplate;
use App\Models\User;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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

    public function downloadSample()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Member ID');
        $sheet->setCellValue('B1', 'Full Name');
        $sheet->setCellValue('C1', 'Phone Number');
        $sheet->setCellValue('D1', 'Saving Behavior');
        $sheet->setCellValue('E1', 'Status');

        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '015425'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Add sample data rows
        $sampleData = [
            ['M001', 'John Doe', '255712345678', 'Regular Saver', 'Active'],
            ['M002', 'Jane Smith', '255723456789', 'Inconsistent Saver', 'Active'],
            ['M003', 'Peter Johnson', '255734567890', 'Sporadic Saver', 'Active'],
            ['M004', 'Mary Williams', '255745678901', 'Non-Saver', 'Pending'],
        ];

        $row = 2;
        foreach ($sampleData as $data) {
            $sheet->setCellValue('A'.$row, $data[0]);
            $sheet->setCellValue('B'.$row, $data[1]);
            $sheet->setCellValue('C'.$row, $data[2]);
            $sheet->setCellValue('D'.$row, $data[3]);
            $sheet->setCellValue('E'.$row, $data[4]);
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Create response
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="sms_bulk_upload_sample.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function uploadAndSend(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'template_id' => 'nullable|exists:sms_message_templates,id',
            'custom_message' => 'nullable|string|max:500',
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows) || count($rows) < 2) {
                return back()->with('error', 'Excel file is empty or has no data rows.');
            }

            // Get header row
            $headers = array_map('strtolower', array_map('trim', $rows[0]));

            // Find column indices
            $memberIdIndex = $this->findColumnIndex($headers, ['member id', 'member_id', 'memberid']);
            $fullNameIndex = $this->findColumnIndex($headers, ['full name', 'full_name', 'name']);
            $phoneIndex = $this->findColumnIndex($headers, ['phone number', 'phone_number', 'phone', 'mobile']);
            $savingBehaviorIndex = $this->findColumnIndex($headers, ['saving behavior', 'saving_behavior', 'behavior']);
            $statusIndex = $this->findColumnIndex($headers, ['status']);

            if ($phoneIndex === null) {
                return back()->with('error', 'Phone Number column not found in Excel file.');
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

                $phone = isset($row[$phoneIndex]) ? trim((string) $row[$phoneIndex]) : null;
                $memberId = $memberIdIndex !== null && isset($row[$memberIdIndex]) ? trim((string) $row[$memberIdIndex]) : null;
                $fullName = $fullNameIndex !== null && isset($row[$fullNameIndex]) ? trim((string) $row[$fullNameIndex]) : null;
                $savingBehavior = $savingBehaviorIndex !== null && isset($row[$savingBehaviorIndex]) ? trim((string) $row[$savingBehaviorIndex]) : null;
                $status = $statusIndex !== null && isset($row[$statusIndex]) ? trim((string) $row[$statusIndex]) : null;

                if (! $phone) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$i}: Phone number is missing";

                    continue;
                }

                // Try to find user by member ID or phone
                $user = null;
                if ($memberId) {
                    $user = User::where('member_number', $memberId)
                        ->orWhere('membership_code', $memberId)
                        ->first();
                }

                if (! $user && $phone) {
                    $user = User::where('phone', $phone)
                        ->orWhere('mobile', $phone)
                        ->first();
                }

                // If user not found but we have name, create a temporary user object
                if (! $user && $fullName) {
                    $user = new User;
                    $user->name = $fullName;
                    $user->phone = $phone;
                    $user->member_number = $memberId;
                }

                if (! $user) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$i}: Could not identify user (Member ID: {$memberId}, Phone: {$phone})";

                    continue;
                }

                // Determine message
                $message = $customMessage;

                if ($template) {
                    // If template has behavior type and saving behavior matches, use it
                    if ($template->behavior_type && $savingBehavior) {
                        $behaviorMatch = $this->matchBehavior($template->behavior_type, $savingBehavior);
                        if ($behaviorMatch) {
                            $orgInfo = $this->smsService->getOrganizationInfo();
                            $message = $template->getMessageForUser($user, [
                                'organization_name' => $orgInfo['name'],
                            ]);
                        }
                    } else {
                        // Use template regardless of behavior
                        $orgInfo = $this->smsService->getOrganizationInfo();
                        $message = $template->getMessageForUser($user, [
                            'organization_name' => $orgInfo['name'],
                        ]);
                    }
                }

                // If still no message, skip
                if (! $message) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$i}: No message template matched for behavior: {$savingBehavior}";

                    continue;
                }

                // Send SMS
                $result = $this->smsService->sendSms($phone, $message);

                if ($result['success']) {
                    $results['success']++;
                    Log::info('Bulk SMS sent successfully', [
                        'phone' => $phone,
                        'member_id' => $memberId,
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
        foreach ($possibleNames as $name) {
            $index = array_search($name, $headers);
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

        // Exact match
        if ($templateBehavior === $userBehavior) {
            return true;
        }

        // Fuzzy matching
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
}
