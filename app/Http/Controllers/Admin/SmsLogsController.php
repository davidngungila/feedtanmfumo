<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Models\SmsProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsLogsController extends Controller
{
    public function index(Request $request)
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
                    // Only update message if it's missing and we have it from API
                    if (empty($existing->message) && ! empty($logData['message'])) {
                        $existing->update($logData);
                    } else {
                        // Update other fields but preserve existing message
                        unset($logData['message']);
                        $existing->update($logData);
                    }
                    $updated++;
                } else {
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
}
