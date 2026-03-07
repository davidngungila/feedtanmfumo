<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $apiKey;
    protected $senderId;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.feedtan.sms_api_key');
        $this->senderId = config('services.feedtan.sms_sender_id', 'FEEDTAN');
        $this->baseUrl = config('services.feedtan.sms_base_url', 'https://api.smsprovider.com');
    }

    /**
     * Send SMS notification
     */
    public function sendSms($phoneNumber, $message, $senderId = null)
    {
        try {
            $senderId = $senderId ?: $this->senderId;
            
            // Clean phone number - ensure it starts with country code
            $cleanPhone = $this->cleanPhoneNumber($phoneNumber);
            
            // Prepare SMS data
            $smsData = [
                'api_key' => $this->apiKey,
                'sender_id' => $senderId,
                'phone' => $cleanPhone,
                'message' => $message,
                'unicode' => 1
            ];

            // Send SMS via API
            $response = Http::timeout(30)->post($this->baseUrl . '/send', $smsData);
            
            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('SMS sent successfully', [
                    'phone' => $cleanPhone,
                    'message' => substr($message, 0, 100) . '...',
                    'response' => $result,
                    'sender_id' => $senderId
                ]);

                return [
                    'status' => 'success',
                    'message_id' => $result['message_id'] ?? null,
                    'response' => $result
                ];
            } else {
                Log::error('SMS sending failed', [
                    'phone' => $cleanPhone,
                    'message' => substr($message, 0, 100) . '...',
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'status' => 'error',
                    'error' => 'SMS API returned error: ' . $response->status()
                ];
            }

        } catch (\Exception $e) {
            Log::error('SMS service error', [
                'phone' => $phoneNumber,
                'message' => substr($message, 0, 100) . '...',
                'error' => $e->getMessage()
            ]);

            return [
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Clean and format phone number
     */
    private function cleanPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $clean = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Ensure it starts with country code for Tanzania
        if (strlen($clean) === 9) {
            // Assume Tanzania number if 9 digits
            $clean = '255' . $clean;
        } elseif (strlen($clean) === 12 && str_starts_with($clean, '255')) {
            // Already has Tanzania country code
            // Keep as is
        } elseif (strlen($clean) === 10 && str_starts_with($clean, '0')) {
            // Remove leading 0 and add country code
            $clean = '255' . substr($clean, 1);
        }

        return $clean;
    }

    /**
     * Send bulk SMS
     */
    public function sendBulkSms($phoneNumbers, $message, $senderId = null)
    {
        $results = [];
        
        foreach ($phoneNumbers as $phone) {
            $results[] = $this->sendSms($phone, $message, $senderId);
        }

        return $results;
    }

    /**
     * Get SMS delivery status
     */
    public function getDeliveryStatus($messageId)
    {
        try {
            $response = Http::timeout(10)->get($this->baseUrl . '/status', [
                'api_key' => $this->apiKey,
                'message_id' => $messageId
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (\Exception $e) {
            Log::error('SMS status check error', [
                'message_id' => $messageId,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }
}
