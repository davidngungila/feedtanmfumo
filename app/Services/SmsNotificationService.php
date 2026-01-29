<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\SmsLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsNotificationService
{
    protected $smsApiKey;

    protected $smsApiSecret;

    protected $smsFrom;

    protected $smsUrl;

    protected $smsEnabled;

    public function __construct()
    {
        $this->reloadSmsConfig();
    }

    /**
     * Reload SMS configuration from database
     */
    protected function reloadSmsConfig(): void
    {
        try {
            // First, try to get from SMS Provider
            $provider = \App\Models\SmsProvider::getPrimary();

            if ($provider && $provider->active) {
                // Use provider configuration
                // Note: username field contains the Bearer token
                $this->smsApiKey = $provider->username ?? '';
                $this->smsApiSecret = $provider->password ?? '';
                $this->smsFrom = $provider->from ?? 'FEEDTAN';
                $this->smsUrl = $provider->api_url ?? 'https://messaging-service.co.tz/api/sms/v2/text/single';
            } else {
                // Fallback to settings
                $settings = Setting::getByGroup('communication');

                $this->smsApiKey = isset($settings['sms_api_key']) && $settings['sms_api_key']->value
                    ? $settings['sms_api_key']->value
                    : Setting::getValue('sms_api_key', env('SMS_API_KEY', ''));

                $this->smsApiSecret = isset($settings['sms_api_secret']) && $settings['sms_api_secret']->value
                    ? $settings['sms_api_secret']->value
                    : Setting::getValue('sms_api_secret', env('SMS_API_SECRET', ''));

                $this->smsFrom = isset($settings['sms_sender_id']) && $settings['sms_sender_id']->value
                    ? $settings['sms_sender_id']->value
                    : Setting::getValue('sms_sender_id', env('SMS_FROM', 'FEEDTAN'));

                $this->smsUrl = Setting::getValue('sms_url', env('SMS_URL', 'https://messaging-service.co.tz/api/sms/v2/text/single'));
            }

            $settings = Setting::getByGroup('communication');
            $this->smsEnabled = isset($settings['sms_enabled']) && $settings['sms_enabled']->value
                ? (bool) $settings['sms_enabled']->value
                : Setting::getValue('sms_enabled', env('SMS_ENABLED', true));

        } catch (\Exception $e) {
            Log::warning('Failed to reload SMS config: '.$e->getMessage());
            // Use env fallback
            $this->smsApiKey = env('SMS_API_KEY', '');
            $this->smsApiSecret = env('SMS_API_SECRET', '');
            $this->smsFrom = env('SMS_FROM', 'FEEDTAN');
            $this->smsUrl = env('SMS_URL', 'https://messaging-service.co.tz/api/sms/v2/text/single');
            $this->smsEnabled = env('SMS_ENABLED', true);
        }
    }

    /**
     * Get organization information for SMS headers
     */
    public function getOrganizationInfo(): array
    {
        $settings = Setting::getByGroup('communication');

        return [
            'name' => $settings['organization_name']->value ?? 'FeedTan Community Microfinance Group',
            'po_box' => $settings['organization_po_box']->value ?? 'P.O.Box 7744',
            'address' => $settings['organization_address']->value ?? 'Ushirika Sokoine Road',
            'city' => $settings['organization_city']->value ?? 'Moshi',
            'region' => $settings['organization_region']->value ?? 'Kilimanjaro',
            'country' => $settings['organization_country']->value ?? 'Tanzania',
            'phone' => $settings['organization_phone']->value ?? '+255 123 456 789',
        ];
    }

    /**
     * Format phone number to international format
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove any non-digit characters
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Handle Tanzanian numbers
        if (strpos($cleaned, '0') === 0) {
            $cleaned = '255'.substr($cleaned, 1);
        } elseif (strpos($cleaned, '255') !== 0) {
            $cleaned = '255'.$cleaned;
        }

        // Validate format: should be 255 followed by 9 digits
        if (! preg_match('/^255[0-9]{9}$/', $cleaned)) {
            Log::error('SMS sending failed: Invalid phone number format', [
                'phone' => $phone,
                'cleaned' => $cleaned,
                'expected_format' => '255XXXXXXXXX',
            ]);
            throw new \Exception('Invalid phone number format. Expected: 255XXXXXXXXX or 0XXXXXXXXX');
        }

        return $cleaned;
    }

    /**
     * Send a single SMS message
     */
    public function sendSms(string $phoneNumber, string $message, array $options = []): array
    {
        if (! $this->smsEnabled) {
            return [
                'success' => false,
                'error' => 'SMS is disabled in settings',
            ];
        }

        if (! $this->smsApiKey) {
            return [
                'success' => false,
                'error' => 'SMS API key not configured',
            ];
        }

        try {
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            Log::info('Attempting to send SMS', [
                'phone' => $phoneNumber,
                'message' => substr($message, 0, 50).(strlen($message) > 50 ? '...' : ''),
                'url' => $this->smsUrl,
                'from' => $this->smsFrom,
            ]);

            // Determine authentication method based on provider configuration
            $provider = \App\Models\SmsProvider::getPrimary();

            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];

            // Normalize API URL and determine authentication
            if ($provider && $provider->active) {
                // Use provider configuration with Bearer token (username field contains token)
                $apiUrl = $provider->api_url;

                // Normalize API URL to use v2 endpoint
                if (strpos($apiUrl, '/api/sms') !== false) {
                    if (strpos($apiUrl, '/v1/') !== false) {
                        $apiUrl = str_replace('/v1/', '/v2/', $apiUrl);
                    } elseif (strpos($apiUrl, '/v2/') === false && strpos($apiUrl, '/text/') === false) {
                        $apiUrl = rtrim($apiUrl, '/').'/v2/text/single';
                    }
                } elseif (strpos($apiUrl, '/link/sms') !== false) {
                    $apiUrl = 'https://messaging-service.co.tz/api/sms/v2/text/single';
                } else {
                    $apiUrl = 'https://messaging-service.co.tz/api/sms/v2/text/single';
                }

                // Use Bearer token (username field contains the Bearer token)
                $bearerToken = trim((string) $provider->username); // Trim whitespace

                if (empty($bearerToken)) {
                    Log::error('SMS sending failed: Bearer token is empty', [
                        'provider_id' => $provider->id,
                        'phone' => $phoneNumber,
                    ]);

                    return [
                        'success' => false,
                        'error' => 'Bearer token (API Key) is missing. Please configure the SMS provider.',
                    ];
                }

                $headers['Authorization'] = 'Bearer '.$bearerToken;

                Log::info('SMS sending request', [
                    'provider_id' => $provider->id,
                    'api_url' => $apiUrl,
                    'from' => $this->smsFrom,
                    'to' => $phoneNumber,
                    'token_length' => strlen($bearerToken),
                ]);

                $response = Http::timeout(30)
                    ->withHeaders($headers)
                    ->post($apiUrl, [
                        'from' => $this->smsFrom,
                        'to' => $phoneNumber,
                        'text' => $message,
                        'flash' => 0,
                        'reference' => 'feedtan_'.time().'_'.uniqid(),
                    ]);
            } else {
                // Use Bearer token authentication from settings
                $apiUrl = $this->smsUrl;

                // Normalize API URL
                if (strpos($apiUrl, '/api/sms') !== false) {
                    if (strpos($apiUrl, '/v1/') !== false) {
                        $apiUrl = str_replace('/v1/', '/v2/', $apiUrl);
                    } elseif (strpos($apiUrl, '/v2/') === false && strpos($apiUrl, '/text/') === false) {
                        $apiUrl = rtrim($apiUrl, '/').'/v2/text/single';
                    }
                } elseif (strpos($apiUrl, '/link/sms') !== false) {
                    $apiUrl = 'https://messaging-service.co.tz/api/sms/v2/text/single';
                } else {
                    $apiUrl = 'https://messaging-service.co.tz/api/sms/v2/text/single';
                }

                $bearerToken = trim((string) $this->smsApiKey); // Trim whitespace

                if (empty($bearerToken)) {
                    Log::error('SMS sending failed: Bearer token is empty from settings', [
                        'phone' => $phoneNumber,
                    ]);

                    return [
                        'success' => false,
                        'error' => 'Bearer token (API Key) is missing. Please configure SMS settings.',
                    ];
                }

                $headers['Authorization'] = 'Bearer '.$bearerToken;

                Log::info('SMS sending request (from settings)', [
                    'api_url' => $apiUrl,
                    'from' => $this->smsFrom,
                    'to' => $phoneNumber,
                    'token_length' => strlen($bearerToken),
                ]);

                $response = Http::timeout(30)
                    ->withHeaders($headers)
                    ->post($apiUrl, [
                        'from' => $this->smsFrom,
                        'to' => $phoneNumber,
                        'text' => $message,
                        'flash' => 0,
                        'reference' => 'feedtan_'.time().'_'.uniqid(),
                    ]);
            }

            $httpCode = $response->status();
            $responseBody = $response->body();

            Log::info('SMS Response', [
                'http_code' => $httpCode,
                'response' => $responseBody,
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                // Check response structure
                if (isset($responseData['messages']) && is_array($responseData['messages']) && count($responseData['messages']) > 0) {
                    $messageStatus = $responseData['messages'][0]['status'] ?? [];

                    if (isset($messageStatus['groupName']) && $messageStatus['groupName'] === 'REJECTED') {
                        $errorMsg = $messageStatus['description'] ?? $messageStatus['name'] ?? 'Message rejected';
                        Log::error("SMS rejected for {$phoneNumber}: {$errorMsg}");

                        return [
                            'success' => false,
                            'error' => $errorMsg,
                            'response' => $responseData,
                        ];
                    }

                    // Message is pending or sent (status id 51 = ENROUTE (SENT))
                    if (($messageStatus['groupName'] ?? '') === 'PENDING' || ($messageStatus['id'] ?? 0) === 51) {
                        Log::info("SMS sent successfully to {$phoneNumber}");

                        // Log SMS to database
                        try {
                            $userId = Auth::id();
                            $user = User::where('phone', $phoneNumber)->first();
                            $messageId = $responseData['messages'][0]['messageId'] ?? null;
                            $reference = $responseData['messages'][0]['reference'] ?? null;

                            $logData = [
                                'message_id' => $messageId,
                                'reference' => $reference,
                                'from' => $this->smsFrom,
                                'to' => $phoneNumber,
                                'message' => $message,
                                'channel' => $responseData['messages'][0]['channel'] ?? 'Internet SMS',
                                'sms_count' => $responseData['messages'][0]['smsCount'] ?? 1,
                                'status_group_id' => $messageStatus['groupId'] ?? null,
                                'status_group_name' => $messageStatus['groupName'] ?? null,
                                'status_id' => $messageStatus['id'] ?? null,
                                'status_name' => $messageStatus['name'] ?? null,
                                'status_description' => $messageStatus['description'] ?? null,
                                'sent_at' => isset($responseData['messages'][0]['sentAt']) ? date('Y-m-d H:i:s', strtotime($responseData['messages'][0]['sentAt'])) : now(),
                                'done_at' => isset($responseData['messages'][0]['doneAt']) ? date('Y-m-d H:i:s', strtotime($responseData['messages'][0]['doneAt'])) : now(),
                                'delivery' => $responseData['messages'][0]['delivery'] ?? null,
                                'api_response' => $responseData,
                                'user_id' => $user?->id,
                                'sent_by' => $userId,
                                'success' => true,
                            ];

                            // Merge options but don't override message
                            if (! empty($options)) {
                                $logData = array_merge($logData, $options);
                                // Ensure message is not overridden
                                $logData['message'] = $message;
                            }

                            $this->logSms($logData);
                        } catch (\Exception $e) {
                            Log::warning('Failed to log SMS to database', ['error' => $e->getMessage()]);
                        }

                        return [
                            'success' => true,
                            'message_id' => $responseData['messages'][0]['messageId'] ?? null,
                            'response' => $responseData,
                        ];
                    }

                    // Log failed SMS
                    try {
                        $userId = Auth::id();
                        $user = User::where('phone', $phoneNumber)->first();
                        $this->logSms([
                            'from' => $this->smsFrom,
                            'to' => $phoneNumber,
                            'message' => $message,
                            'status_group_name' => $messageStatus['groupName'] ?? 'REJECTED',
                            'status_name' => $messageStatus['name'] ?? 'Unknown',
                            'status_description' => $messageStatus['description'] ?? 'Unknown status',
                            'api_response' => $responseData,
                            'user_id' => $user?->id,
                            'sent_by' => $userId,
                            'success' => false,
                            'error_message' => $messageStatus['description'] ?? $messageStatus['name'] ?? 'Unknown status',
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Failed to log failed SMS to database', ['error' => $e->getMessage()]);
                    }

                    // Other status codes
                    return [
                        'success' => false,
                        'error' => $messageStatus['description'] ?? $messageStatus['name'] ?? 'Unknown status',
                        'response' => $responseData,
                        'status_group' => $messageStatus['groupName'] ?? null,
                        'status_id' => $messageStatus['id'] ?? null,
                    ];
                }

                // If response structure is different but status is 200, assume success
                Log::info("SMS sent successfully to {$phoneNumber}");

                // Log to database
                try {
                    $userId = Auth::id();
                    $user = User::where('phone', $phoneNumber)->first();
                    $this->logSms([
                        'from' => $this->smsFrom,
                        'to' => $phoneNumber,
                        'message' => $message,
                        'api_response' => $responseData,
                        'user_id' => $user?->id,
                        'sent_by' => $userId,
                        'success' => true,
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to log SMS to database', ['error' => $e->getMessage()]);
                }

                return [
                    'success' => true,
                    'response' => $responseData,
                ];
            }

            $errorMsg = "SMS failed with HTTP code {$httpCode}";
            if ($responseBody) {
                $errorMsg .= ': '.substr($responseBody, 0, 200);
            }

            Log::error('SMS failed with HTTP code', [
                'http_code' => $httpCode,
                'response' => $responseBody,
                'phone' => $phoneNumber,
                'error' => $errorMsg,
            ]);

            // Log failed SMS
            try {
                $userId = Auth::id();
                $user = User::where('phone', $phoneNumber)->first();
                $this->logSms([
                    'from' => $this->smsFrom,
                    'to' => $phoneNumber,
                    'message' => $message,
                    'api_response' => ['http_code' => $httpCode, 'body' => $responseBody],
                    'user_id' => $user?->id,
                    'sent_by' => $userId,
                    'success' => false,
                    'error_message' => $errorMsg,
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to log failed SMS to database', ['error' => $e->getMessage()]);
            }

            return [
                'success' => false,
                'error' => $errorMsg,
                'status' => $httpCode,
            ];

        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'phone' => $phoneNumber ?? 'unknown',
                'message_text' => substr($message ?? 'unknown', 0, 50),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            // Log exception
            try {
                $userId = Auth::id();
                $user = User::where('phone', $phoneNumber ?? '')->first();
                $this->logSms([
                    'from' => $this->smsFrom ?? 'FEEDTAN',
                    'to' => $phoneNumber ?? 'unknown',
                    'message' => $message ?? '',
                    'user_id' => $user?->id,
                    'sent_by' => $userId,
                    'success' => false,
                    'error_message' => $e->getMessage(),
                ]);
            } catch (\Exception $logException) {
                Log::warning('Failed to log exception SMS to database', ['error' => $logException->getMessage()]);
            }

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Log SMS details to database
     */
    protected function logSms(array $data): void
    {
        try {
            // If message_id exists, try to update existing log instead of creating new one
            if (! empty($data['message_id'])) {
                $existing = SmsLog::where('message_id', $data['message_id'])->first();
                if ($existing) {
                    // Update existing log, preserving message if it exists and new one is empty
                    if (empty($data['message']) && ! empty($existing->message)) {
                        unset($data['message']);
                    }
                    $existing->update($data);

                    return;
                }
            }

            // Create new log
            SmsLog::create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create SMS log', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Send SMS to multiple recipients
     */
    public function sendBulkSms(array $recipients, string $message): array
    {
        if (! $this->smsEnabled) {
            return [
                'success' => false,
                'error' => 'SMS is disabled in settings',
            ];
        }

        if (! $this->smsApiKey) {
            return [
                'success' => false,
                'error' => 'SMS API key not configured',
            ];
        }

        try {
            // Prepare messages array
            $messages = [];
            foreach ($recipients as $phone) {
                try {
                    $formattedPhone = $this->formatPhoneNumber($phone);
                    $messages[] = [
                        'from' => $this->smsFrom,
                        'to' => $formattedPhone,
                        'text' => $message,
                    ];
                } catch (\Exception $e) {
                    Log::warning('Skipping invalid phone number in bulk SMS', [
                        'phone' => $phone,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if (empty($messages)) {
                return [
                    'success' => false,
                    'error' => 'No valid phone numbers to send to',
                ];
            }

            $bulkUrl = str_replace('/single', '/multi', $this->smsUrl);
            if ($bulkUrl === $this->smsUrl) {
                // If URL doesn't have /single, try to construct multi URL
                $bulkUrl = 'https://messaging-service.co.tz/api/sms/v2/text/multi';
            }

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer '.$this->smsApiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($bulkUrl, [
                    'messages' => $messages,
                    'flash' => 0,
                    'reference' => 'feedtan_bulk_'.time().'_'.uniqid(),
                ]);

            if ($response->successful()) {
                $responseData = $response->json();

                $results = [
                    'success' => true,
                    'sent' => 0,
                    'failed' => 0,
                    'messages' => [],
                ];

                if (isset($responseData['messages']) && is_array($responseData['messages'])) {
                    foreach ($responseData['messages'] as $msg) {
                        $status = $msg['status'] ?? [];
                        if (($status['groupName'] ?? '') === 'PENDING' || ($status['id'] ?? 0) === 51) {
                            $results['sent']++;
                        } else {
                            $results['failed']++;
                        }
                        $results['messages'][] = [
                            'to' => $msg['to'] ?? '',
                            'status' => $status['groupName'] ?? 'UNKNOWN',
                            'message_id' => $msg['messageId'] ?? null,
                            'error' => $status['description'] ?? null,
                        ];
                    }
                }

                return $results;
            }

            return [
                'success' => false,
                'error' => 'HTTP '.$response->status().': '.$response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Failed to send bulk SMS: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send loan approval SMS notification
     */
    public function sendLoanApprovalSms(User $user, array $loanDetails): bool
    {
        try {
            $orgInfo = $this->getOrganizationInfo();
            $message = $this->formatLoanApprovalSms($user, $loanDetails, $orgInfo);

            $result = $this->sendSms($user->phone ?? $user->alternate_phone ?? '', $message);

            if ($result['success']) {
                Log::info("Loan approval SMS sent to {$user->email} for user ID {$user->id}.");

                return true;
            } else {
                Log::error("Failed to send loan approval SMS to {$user->email}: ".($result['error'] ?? 'Unknown error'));

                return false;
            }
        } catch (\Exception $e) {
            Log::error("Failed to send loan approval SMS to {$user->email}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Format loan approval SMS message
     */
    protected function formatLoanApprovalSms(User $user, array $loanDetails, array $orgInfo): string
    {
        $name = explode(' ', $user->name)[0]; // First name only
        $amount = number_format($loanDetails['amount'] ?? 0, 0);

        return "Habari {$name}, mkopo wako wa TZS {$amount} umeidhinishwa! Tafadhali fika ofisi yetu au piga {$orgInfo['phone']} kujua zaidi. - {$orgInfo['name']}";
    }

    /**
     * Send payment reminder SMS
     */
    public function sendPaymentReminderSms(User $user, array $paymentDetails): bool
    {
        try {
            $orgInfo = $this->getOrganizationInfo();
            $name = explode(' ', $user->name)[0];
            $amount = number_format($paymentDetails['amount'] ?? 0, 0);
            $dueDate = isset($paymentDetails['due_date']) ? date('d/m/Y', strtotime($paymentDetails['due_date'])) : '';

            $message = "Habari {$name}, kumbuka malipo yako ya TZS {$amount}";
            if ($dueDate) {
                $message .= " yanayostahili {$dueDate}";
            }
            $message .= ". Tafadhali fika ofisi au tumia M-Pesa. - {$orgInfo['name']}";

            $result = $this->sendSms($user->phone ?? $user->alternate_phone ?? '', $message);

            if ($result['success']) {
                Log::info("Payment reminder SMS sent to {$user->email}");

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Failed to send payment reminder SMS: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Send welcome SMS with login credentials
     */
    public function sendWelcomeSms(User $user, string $plainPassword): bool
    {
        try {
            $orgInfo = $this->getOrganizationInfo();
            $message = $this->formatWelcomeSms($user, $plainPassword, $orgInfo);

            $result = $this->sendSms($user->phone ?? $user->alternate_phone ?? '', $message);

            if ($result['success']) {
                Log::info("Welcome SMS sent to {$user->phone} for user ID {$user->id}.");

                return true;
            } else {
                Log::error("Failed to send welcome SMS to {$user->phone}: ".($result['error'] ?? 'Unknown error'));

                return false;
            }
        } catch (\Exception $e) {
            Log::error("Failed to send welcome SMS to {$user->phone}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Format welcome SMS message
     */
    protected function formatWelcomeSms(User $user, string $plainPassword, array $orgInfo): string
    {
        $name = explode(' ', $user->name)[0]; // First name only

        return "Karibu {$name}! Akaunti yako imeundwa kwa mafanikio. Email: {$user->email}, Nenosiri: {$plainPassword}. Ingia: ".route('login')." - {$orgInfo['name']}";
    }

    /**
     * Send membership application submission SMS
     */
    public function sendMembershipSubmissionSms(User $user): bool
    {
        try {
            $orgInfo = $this->getOrganizationInfo();
            $message = $this->formatMembershipSubmissionSms($user, $orgInfo);

            $result = $this->sendSms($user->phone ?? $user->alternate_phone ?? '', $message);

            if ($result['success']) {
                Log::info("Membership submission SMS sent to {$user->phone} for user ID {$user->id}.");

                return true;
            } else {
                Log::error("Failed to send membership submission SMS to {$user->phone}: ".($result['error'] ?? 'Unknown error'));

                return false;
            }
        } catch (\Exception $e) {
            Log::error("Failed to send membership submission SMS to {$user->phone}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Format membership submission SMS message
     */
    protected function formatMembershipSubmissionSms(User $user, array $orgInfo): string
    {
        $name = explode(' ', $user->name)[0]; // First name only
        $membershipCode = $user->membership_code ?? 'Pending';

        return "Asante {$name}! Maombi yako ya uanachama yamepokelewa kwa mafanikio. Nambari yako: {$membershipCode}. Tunaendelea kukagua na tutakujulisha matokeo hivi karibuni. Tafadhali subiri kwa idhini. - {$orgInfo['name']}";
    }
}
