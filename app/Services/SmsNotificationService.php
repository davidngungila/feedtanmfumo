<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsNotificationService
{
    /**
     * Reload SMS configuration from database
     */
    protected function reloadSmsConfig(): void
    {
        try {
            $settings = Setting::getByGroup('communication');
            
            // Store config in cache for this request
            if (isset($settings['sms_provider']) && $settings['sms_provider']->value) {
                config(['sms.provider' => $settings['sms_provider']->value]);
            }
            
            if (isset($settings['sms_api_key']) && $settings['sms_api_key']->value) {
                config(['sms.api_key' => $settings['sms_api_key']->value]);
            }
            
            if (isset($settings['sms_api_secret']) && $settings['sms_api_secret']->value) {
                config(['sms.api_secret' => $settings['sms_api_secret']->value]);
            }
            
            if (isset($settings['sms_sender_id']) && $settings['sms_sender_id']->value) {
                config(['sms.sender_id' => $settings['sms_sender_id']->value]);
            }
            
            if (isset($settings['sms_enabled']) && $settings['sms_enabled']->value) {
                config(['sms.enabled' => (bool)$settings['sms_enabled']->value]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to reload SMS config: ' . $e->getMessage());
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
        $cleaned = preg_replace('/\D/', '', $phone);
        
        // Handle Tanzanian numbers
        if (strpos($cleaned, '0') === 0) {
            $cleaned = '255' . substr($cleaned, 1);
        } elseif (strpos($cleaned, '255') !== 0) {
            $cleaned = '255' . $cleaned;
        }
        
        return $cleaned;
    }

    /**
     * Send a single SMS message
     */
    public function sendSms(string $phoneNumber, string $message): array
    {
        $this->reloadSmsConfig();
        
        if (!config('sms.enabled', true)) {
            return [
                'success' => false,
                'error' => 'SMS is disabled in settings'
            ];
        }

        try {
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);
            $apiKey = config('sms.api_key');
            $senderId = config('sms.sender_id', 'FEEDTAN');
            
            if (!$apiKey) {
                return [
                    'success' => false,
                    'error' => 'SMS API key not configured'
                ];
            }

            // Use messaging-service.co.tz API
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post('https://messaging-service.co.tz/api/sms/v2/text/single', [
                    'from' => $senderId,
                    'to' => $phoneNumber,
                    'text' => $message,
                    'flash' => 0,
                    'reference' => uniqid('feedtan_', true)
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
                    
                    // Message is pending or sent
                    if (($messageStatus['groupName'] ?? '') === 'PENDING' || ($messageStatus['id'] ?? 0) === 51) {
                        Log::info("SMS sent successfully to {$phoneNumber}");
                        return [
                            'success' => true,
                            'message_id' => $responseData['messages'][0]['messageId'] ?? null,
                            'response' => $responseData
                        ];
                    }
                }
                
                // If response structure is different but status is 200, assume success
                Log::info("SMS sent successfully to {$phoneNumber}");
                return [
                    'success' => true,
                    'response' => $responseData
                ];
            }

            return [
                'success' => false,
                'error' => 'HTTP ' . $response->status() . ': ' . $response->body(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error("Failed to send SMS to {$phoneNumber}: " . $e->getMessage());
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
        $this->reloadSmsConfig();
        
        if (!config('sms.enabled', true)) {
            return [
                'success' => false,
                'error' => 'SMS is disabled in settings'
            ];
        }

        try {
            $apiKey = config('sms.api_key');
            $senderId = config('sms.sender_id', 'FEEDTAN');
            
            if (!$apiKey) {
                return [
                    'success' => false,
                    'error' => 'SMS API key not configured'
                ];
            }

            // Prepare messages array
            $messages = [];
            foreach ($recipients as $phone) {
                $messages[] = [
                    'from' => $senderId,
                    'to' => $this->formatPhoneNumber($phone),
                    'text' => $message
                ];
            }

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post('https://messaging-service.co.tz/api/sms/v2/text/multi', [
                    'messages' => $messages,
                    'flash' => 0,
                    'reference' => uniqid('feedtan_bulk_', true)
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
            
            $result = $this->sendSms($user->phone ?? $user->profile->phone ?? '', $message);
            
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
            
            $result = $this->sendSms($user->phone ?? $user->profile->phone ?? '', $message);
            
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

