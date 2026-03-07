<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $apiKey;
    protected $senderId;

    public function __construct()
    {
        $this->apiKey = config('services.sms.api_key');
        $this->senderId = config('services.sms.sender_id', 'FEEDTAN');
    }

    /**
     * Send SMS message
     */
    public function send($phoneNumber, $message)
    {
        try {
            // Format phone number
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            
            $response = Http::post(config('services.sms.endpoint'), [
                'api_key' => $this->apiKey,
                'to' => $formattedPhone,
                'message' => $message,
                'sender_id' => $this->senderId
            ]);

            if ($response->successful()) {
                Log::info('SMS sent successfully', [
                    'phone' => $formattedPhone,
                    'message_length' => strlen($message)
                ]);
                return true;
            } else {
                Log::error('SMS sending failed', [
                    'phone' => $formattedPhone,
                    'response' => $response->json()
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('SMS service error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Format phone number for SMS
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Ensure it starts with country code
        if (!str_starts_with($phone, '255')) {
            $phone = '255' . ltrim($phone, '0');
        }
        
        return $phone;
    }
}
