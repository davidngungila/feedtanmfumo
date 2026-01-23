<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
                $this->smsApiKey = $provider->username ?? '';
                $this->smsApiSecret = $provider->password ?? '';
                $this->smsFrom = $provider->from ?? 'FEEDTAN';
                $this->smsUrl = $provider->api_url ?? 'https://messaging-service.co.tz/link/sms/v1/text/single';
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
                
                $this->smsUrl = Setting::getValue('sms_url', env('SMS_URL', 'https://messaging-service.co.tz/link/sms/v1/text/single'));
            }
            
            $settings = Setting::getByGroup('communication');
            $this->smsEnabled = isset($settings['sms_enabled']) && $settings['sms_enabled']->value 
                ? (bool)$settings['sms_enabled']->value 
                : Setting::getValue('sms_enabled', env('SMS_ENABLED', true));
                
        } catch (\Exception $e) {
            Log::warning('Failed to reload SMS config: ' . $e->getMessage());
            // Use env fallback
            $this->smsApiKey = env('SMS_API_KEY', '');
            $this->smsApiSecret = env('SMS_API_SECRET', '');
            $this->smsFrom = env('SMS_FROM', 'FEEDTAN');
            $this->smsUrl = env('SMS_URL', 'https://messaging-service.co.tz/link/sms/v1/text/single');
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
            $cleaned = '255' . substr($cleaned, 1);
        } elseif (strpos($cleaned, '255') !== 0) {
            $cleaned = '255' . $cleaned;
        }
        
        // Validate format: should be 255 followed by 9 digits
        if (!preg_match('/^255[0-9]{9}$/', $cleaned)) {
            Log::error('SMS sending failed: Invalid phone number format', [
                'phone' => $phone,
                'cleaned' => $cleaned,
                'expected_format' => '255XXXXXXXXX'
            ]);
            throw new \Exception('Invalid phone number format. Expected: 255XXXXXXXXX or 0XXXXXXXXX');
        }
        
        return $cleaned;
    }

    /**
     * Send a single SMS message
     */
    public function sendSms(string $phoneNumber, string $message): array
    {
        if (!$this->smsEnabled) {
            return [
                'success' => false,
                'error' => 'SMS is disabled in settings'
            ];
        }

        if (!$this->smsApiKey) {
            return [
                'success' => false,
                'error' => 'SMS API key not configured'
            ];
        }

        try {
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);
            
            Log::info('Attempting to send SMS', [
                'phone' => $phoneNumber,
                'message' => substr($message, 0, 50) . (strlen($message) > 50 ? '...' : ''),
                'url' => $this->smsUrl,
                'from' => $this->smsFrom
            ]);

            // Determine authentication method based on provider configuration
            $provider = \App\Models\SmsProvider::getPrimary();
            $useBasicAuth = $provider && $provider->username && $provider->password;
            
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ];
            
            // Use Basic Auth if username/password are configured, otherwise use Bearer token
            if ($useBasicAuth) {
                // Ensure API URL has the correct endpoint
                $apiUrl = $this->smsUrl;
                if (strpos($apiUrl, '/api/sms') !== false && strpos($apiUrl, '/text/') === false) {
                    $apiUrl = rtrim($apiUrl, '/') . '/v1/text/single';
                }
                
                $response = Http::timeout(30)
                    ->withBasicAuth($provider->username, $provider->password)
                    ->withHeaders($headers)
                    ->post($apiUrl, [
                        'from' => $this->smsFrom,
                        'to' => $phoneNumber,
                        'text' => $message,
                        'flash' => 0,
                        'reference' => 'feedtan_' . time() . '_' . uniqid()
                    ]);
            } else {
                // Use Bearer token authentication
                $headers['Authorization'] = 'Bearer ' . $this->smsApiKey;
                $response = Http::timeout(30)
                    ->withHeaders($headers)
                    ->post($this->smsUrl, [
                        'from' => $this->smsFrom,
                        'to' => $phoneNumber,
                        'text' => $message,
                        'flash' => 0,
                        'reference' => 'feedtan_' . time() . '_' . uniqid()
                    ]);
            }

            $httpCode = $response->status();
            $responseBody = $response->body();

            Log::info('SMS Response', [
                'http_code' => $httpCode,
                'response' => $responseBody
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
                            'response' => $responseData
                        ];
                    }
                    
                    // Message is pending or sent (status id 51 = ENROUTE (SENT))
                    if (($messageStatus['groupName'] ?? '') === 'PENDING' || ($messageStatus['id'] ?? 0) === 51) {
                        Log::info("SMS sent successfully to {$phoneNumber}");
                        
                        // Log SMS activity
                        try {
                            $userId = Auth::id();
                            $user = User::where('phone', $phoneNumber)
                                ->orWhere('mobile', $phoneNumber)
                                ->first();
                            
                            // You can add activity logging here if you have ActivityLogService
                            // ActivityLogService::logSMSSent($phoneNumber, $message, $userId, $user?->id, [
                            //     'provider' => 'messaging-service.co.tz',
                            //     'sms_from' => $this->smsFrom,
                            //     'response_code' => $httpCode,
                            // ]);
                        } catch (\Exception $e) {
                            Log::warning('Failed to log SMS activity', ['error' => $e->getMessage()]);
                        }
                        
                        return [
                            'success' => true,
                            'message_id' => $responseData['messages'][0]['messageId'] ?? null,
                            'response' => $responseData
                        ];
                    }
                    
                    // Other status codes
                    return [
                        'success' => false,
                        'error' => $messageStatus['description'] ?? $messageStatus['name'] ?? 'Unknown status',
                        'response' => $responseData,
                        'status_group' => $messageStatus['groupName'] ?? null,
                        'status_id' => $messageStatus['id'] ?? null
                    ];
                }
                
                // If response structure is different but status is 200, assume success
                Log::info("SMS sent successfully to {$phoneNumber}");
                return [
                    'success' => true,
                    'response' => $responseData
                ];
            }

            $errorMsg = "SMS failed with HTTP code {$httpCode}";
            if ($responseBody) {
                $errorMsg .= ': ' . substr($responseBody, 0, 200);
            }
            
            Log::error('SMS failed with HTTP code', [
                'http_code' => $httpCode,
                'response' => $responseBody,
                'phone' => $phoneNumber,
                'error' => $errorMsg
            ]);

            return [
                'success' => false,
                'error' => $errorMsg,
                'status' => $httpCode
            ];

        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'phone' => $phoneNumber ?? 'unknown',
                'message_text' => substr($message ?? 'unknown', 0, 50),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send SMS to multiple recipients
     */
    public function sendBulkSms(array $recipients, string $message): array
    {
        if (!$this->smsEnabled) {
            return [
                'success' => false,
                'error' => 'SMS is disabled in settings'
            ];
        }

        if (!$this->smsApiKey) {
            return [
                'success' => false,
                'error' => 'SMS API key not configured'
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
                        'text' => $message
                    ];
                } catch (\Exception $e) {
                    Log::warning('Skipping invalid phone number in bulk SMS', [
                        'phone' => $phone,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            if (empty($messages)) {
                return [
                    'success' => false,
                    'error' => 'No valid phone numbers to send to'
                ];
            }

            $bulkUrl = str_replace('/single', '/multi', $this->smsUrl);
            if ($bulkUrl === $this->smsUrl) {
                // If URL doesn't have /single, try to construct multi URL
                $bulkUrl = 'https://messaging-service.co.tz/api/sms/v2/text/multi';
            }

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->smsApiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($bulkUrl, [
                    'messages' => $messages,
                    'flash' => 0,
                    'reference' => 'feedtan_bulk_' . time() . '_' . uniqid()
                ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                $results = [
                    'success' => true,
                    'sent' => 0,
                    'failed' => 0,
                    'messages' => []
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
                            'error' => $status['description'] ?? null
                        ];
                    }
                }

                return $results;
            }

            return [
                'success' => false,
                'error' => 'HTTP ' . $response->status() . ': ' . $response->body()
            ];

        } catch (\Exception $e) {
            Log::error("Failed to send bulk SMS: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
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
            
            $result = $this->sendSms($user->phone ?? $user->mobile ?? '', $message);
            
            if ($result['success']) {
                Log::info("Loan approval SMS sent to {$user->email} for user ID {$user->id}.");
                return true;
            } else {
                Log::error("Failed to send loan approval SMS to {$user->email}: " . ($result['error'] ?? 'Unknown error'));
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Failed to send loan approval SMS to {$user->email}: " . $e->getMessage());
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
            
            $result = $this->sendSms($user->phone ?? $user->mobile ?? '', $message);
            
            if ($result['success']) {
                Log::info("Payment reminder SMS sent to {$user->email}");
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error("Failed to send payment reminder SMS: " . $e->getMessage());
            return false;
        }
    }
}
