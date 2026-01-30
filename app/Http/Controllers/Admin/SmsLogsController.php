<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Models\SmsProvider;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SmsLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildQuery($request);

        // Get SMS balance
        $balance = $this->getSmsBalance();

        // Paginate results
        $logs = $query->orderBy('sent_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(50)
            ->withQueryString();

        $stats = [
            'total' => SmsLog::count(),
            'success' => SmsLog::where('success', true)->count(),
            'failed' => SmsLog::where('success', false)->count(),
            'today' => SmsLog::whereDate('created_at', today())->count(),
        ];

        return view('admin.sms.logs', compact('logs', 'balance', 'stats'));
    }

    public function syncFromApi(Request $request)
    {
        try {
            $provider = SmsProvider::getPrimary();

            if (! $provider || ! $provider->active) {
                return back()->with('error', 'No active SMS provider configured.');
            }

            $bearerToken = trim((string) $provider->username);

            if (empty($bearerToken)) {
                return back()->with('error', 'Bearer token is missing. Please configure the SMS provider.');
            }

            // Build API URL with filters
            $apiUrl = 'https://messaging-service.co.tz/api/v2/logs';
            $params = [];

            if ($request->filled('from')) {
                $params['from'] = $request->from;
            }

            if ($request->filled('to')) {
                $params['to'] = $request->to;
            }

            if ($request->filled('sentSince')) {
                $params['sentSince'] = $request->sentSince;
            }

            if ($request->filled('sentUntil')) {
                $params['sentUntil'] = $request->sentUntil;
            }

            if ($request->filled('offset')) {
                $params['offset'] = $request->offset;
            }

            if ($request->filled('limit')) {
                $params['limit'] = min($request->limit, 500); // Max 500
            }

            if (! empty($params)) {
                $apiUrl .= '?'.http_build_query($params);
            }

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer '.$bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->get($apiUrl);

            if (! $response->successful()) {
                return back()->with('error', 'Failed to fetch logs from API: '.$response->body());
            }

            $data = $response->json();
            $results = $data['results'] ?? [];

            $synced = 0;
            $updated = 0;

            foreach ($results as $result) {
                $logData = [
                    'message_id' => $result['messageId'] ?? null,
                    'reference' => $result['reference'] ?? null,
                    'from' => $result['from'] ?? null,
                    'to' => $result['to'] ?? null,
                    'message' => $result['text'] ?? $result['message'] ?? null, // Try to get message from API if available
                    'channel' => $result['channel'] ?? null,
                    'sms_count' => $result['smsCount'] ?? 1,
                    'status_group_id' => $result['status']['groupId'] ?? null,
                    'status_group_name' => $result['status']['groupName'] ?? null,
                    'status_id' => $result['status']['id'] ?? null,
                    'status_name' => $result['status']['name'] ?? null,
                    'status_description' => $result['status']['description'] ?? null,
                    'sent_at' => isset($result['sentAt']) ? date('Y-m-d H:i:s', strtotime($result['sentAt'])) : null,
                    'done_at' => isset($result['doneAt']) ? date('Y-m-d H:i:s', strtotime($result['doneAt'])) : null,
                    'delivery' => $result['delivery'] ?? null,
                    'api_response' => $result,
                    'success' => in_array($result['status']['groupName'] ?? '', ['ACCEPTED', 'DELIVERED', 'PENDING']),
                ];

                // Try to find user by phone number
                if ($logData['to']) {
                    $user = \App\Models\User::where('phone', $logData['to'])
                        ->orWhere('alternate_phone', $logData['to'])
                        ->first();
                    if ($user) {
                        $logData['user_id'] = $user->id;
                    }
                }

                $existing = SmsLog::where('message_id', $logData['message_id'])->first();

                if ($existing) {
                    // Always preserve existing message - API doesn't return message content
                    $existingMessage = $existing->message;
                    // Update other fields but preserve existing message
                    unset($logData['message']);
                    $existing->update($logData);
                    // Restore message if it existed
                    if ($existingMessage) {
                        $existing->message = $existingMessage;
                        $existing->save();
                    }
                    $updated++;
                } else {
                    // For new logs from API, message will be null (API doesn't provide it)
                    // But we'll create it anyway so it can be updated later when sending through our system
                    SmsLog::create($logData);
                    $synced++;
                }
            }

            return back()->with('success', "Synced {$synced} new logs and updated {$updated} existing logs from API.");
        } catch (\Exception $e) {
            Log::error('Failed to sync SMS logs from API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Error syncing logs: '.$e->getMessage());
        }
    }

    public function show(SmsLog $smsLog)
    {
        $smsLog->load(['user', 'sentByUser']);

        return view('admin.sms.logs-show', compact('smsLog'));
    }

    protected function getSmsBalance(): ?array
    {
        try {
            $provider = SmsProvider::getPrimary();

            if (! $provider || ! $provider->active) {
                return null;
            }

            $bearerToken = trim((string) $provider->username);

            if (empty($bearerToken)) {
                return null;
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer '.$bearerToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->get('https://messaging-service.co.tz/api/v2/balance');

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::warning('Failed to fetch SMS balance', ['error' => $e->getMessage()]);

            return null;
        }
    }

    protected function buildQuery(Request $request)
    {
        $query = SmsLog::query()->with(['user', 'sentByUser']);

        // Apply filters
        if ($request->filled('from')) {
            $query->where('from', 'like', '%'.$request->from.'%');
        }

        if ($request->filled('to')) {
            $query->where('to', 'like', '%'.$request->to.'%');
        }

        if ($request->filled('status')) {
            $query->where('status_group_name', $request->status);
        }

        if ($request->filled('sent_since')) {
            $query->where('sent_at', '>=', $request->sent_since);
        }

        if ($request->filled('sent_until')) {
            $query->where('sent_at', '<=', $request->sent_until.' 23:59:59');
        }

        if ($request->filled('success')) {
            $query->where('success', $request->success === '1');
        }

        return $query;
    }

    public function exportPdf(Request $request)
    {
        $query = $this->buildQuery($request);
        $logs = $query->orderBy('sent_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $balance = $this->getSmsBalance();
        $stats = [
            'total' => $logs->count(),
            'success' => $logs->where('success', true)->count(),
            'failed' => $logs->where('success', false)->count(),
        ];

        $pdf = Pdf::loadView('admin.sms.logs-pdf', [
            'logs' => $logs,
            'balance' => $balance,
            'stats' => $stats,
            'filters' => $request->only(['from', 'to', 'status', 'sent_since', 'sent_until', 'success']),
        ])->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('sms-logs-'.date('Y-m-d-His').'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = $this->buildQuery($request);
        $logs = $query->orderBy('sent_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'Message ID', 'Reference', 'From', 'To', 'Message', 'Channel',
            'SMS Count', 'Status', 'Status Name', 'Sent At', 'Done At',
            'User', 'Sent By', 'Template ID', 'Saving Behavior', 'Success', 'Error Message',
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col.'1', $header);
            $col++;
        }

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
        $sheet->getStyle('A1:Q1')->applyFromArray($headerStyle);

        // Add data rows
        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue('A'.$row, $log->message_id ?? 'N/A');
            $sheet->setCellValue('B'.$row, $log->reference ?? 'N/A');
            $sheet->setCellValue('C'.$row, $log->from ?? 'N/A');
            $sheet->setCellValue('D'.$row, $log->to);
            $sheet->setCellValue('E'.$row, $log->message ?? 'N/A');
            $sheet->setCellValue('F'.$row, $log->channel ?? 'Internet SMS');
            $sheet->setCellValue('G'.$row, $log->sms_count ?? 1);
            $sheet->setCellValue('H'.$row, $log->status_group_name ?? 'N/A');
            $sheet->setCellValue('I'.$row, $log->status_name ?? 'N/A');
            $sheet->setCellValue('J'.$row, $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : 'N/A');
            $sheet->setCellValue('K'.$row, $log->done_at ? $log->done_at->format('Y-m-d H:i:s') : 'N/A');
            $sheet->setCellValue('L'.$row, $log->user ? $log->user->name : 'N/A');
            $sheet->setCellValue('M'.$row, $log->sentByUser ? $log->sentByUser->name : 'N/A');
            $sheet->setCellValue('N'.$row, $log->template_id ?? 'N/A');
            $sheet->setCellValue('O'.$row, $log->saving_behavior ?? 'N/A');
            $sheet->setCellValue('P'.$row, $log->success ? 'Yes' : 'No');
            $sheet->setCellValue('Q'.$row, $log->error_message ?? 'N/A');
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'Q') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Create writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Create response
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="sms-logs-'.date('Y-m-d-His').'.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
