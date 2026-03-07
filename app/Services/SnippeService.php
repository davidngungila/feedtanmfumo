<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SnippeService
{
    protected $baseUrl;
    protected $apiKey;
    protected $webhookSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.snippe.base_url', 'https://api.snippe.sh');
        $this->apiKey = config('services.snippe.api_key') ?: 'snp_5da70756f0c8f8293d9473e6f001b32e7118690545762f02aea912350eb8c8da';
        $this->webhookSecret = config('services.snippe.webhook_secret') ?: 'whsec_f49fd8c6730db5eae37648fcb059194b6a7b8529b19be5937de5c33e4527f519';
    }

    /**
     * Create a payment via Snippe API
     */
    public function createPayment(array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Idempotency-Key' => Str::uuid()->toString(),
            ])->post($this->baseUrl . '/v1/payments', $data);

            $responseData = $response->json();

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $responseData
                ];
            } else {
                Log::error('Snippe API Error', [
                    'status' => $response->status(),
                    'response' => $responseData
                ]);

                return [
                    'status' => 'error',
                    'message' => $responseData['message'] ?? 'Payment processing failed',
                    'error_code' => $responseData['error_code'] ?? 'unknown_error'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Snippe Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'status' => 'error',
                'message' => 'Unable to connect to payment service'
            ];
        }
    }

    /**
     * Get payment status by reference
     */
    public function getPaymentStatus($reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/v1/payments/' . $reference);

            $responseData = $response->json();

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $responseData
                ];
            } else {
                Log::error('Snippe Status Check Error', [
                    'reference' => $reference,
                    'status' => $response->status(),
                    'response' => $responseData
                ]);

                return [
                    'status' => 'error',
                    'message' => $responseData['message'] ?? 'Unable to check payment status'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Snippe Status Check Exception', [
                'reference' => $reference,
                'message' => $e->getMessage()
            ]);

            return [
                'status' => 'error',
                'message' => 'Unable to connect to payment service'
            ];
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        if (!$this->webhookSecret) {
            Log::warning('Webhook secret not configured');
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $payload, $this->webhookSecret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Create a mobile money payment
     */
    public function createMobilePayment(array $data)
    {
        $mobileData = array_merge($data, [
            'payment_type' => 'mobile',
            'details' => array_merge($data['details'] ?? [], [
                'currency' => 'TZS'
            ])
        ]);

        return $this->createPayment($mobileData);
    }

    /**
     * Create a card payment
     */
    public function createCardPayment(array $data)
    {
        $cardData = array_merge($data, [
            'payment_type' => 'card',
            'details' => array_merge($data['details'] ?? [], [
                'currency' => 'TZS'
            ]),
            'callback_url' => route('public.payments.success')
        ]);

        return $this->createPayment($cardData);
    }

    /**
     * Create a QR code payment
     */
    public function createQRPayment(array $data)
    {
        $qrData = array_merge($data, [
            'payment_type' => 'dynamic-qr',
            'details' => array_merge($data['details'] ?? [], [
                'currency' => 'TZS'
            ]),
            'callback_url' => route('public.payments.success')
        ]);

        return $this->createPayment($qrData);
    }

    /**
     * Validate phone number for Tanzania
     */
    public function validatePhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove leading 255 if present and add +255
        if (strlen($phone) === 12 && str_starts_with($phone, '255')) {
            return '+' . $phone;
        }
        
        // Add +255 prefix for 9-digit numbers
        if (strlen($phone) === 9) {
            return '+255' . $phone;
        }
        
        // Already in correct format
        if (strlen($phone) === 13 && str_starts_with($phone, '+255')) {
            return $phone;
        }
        
        return false;
    }

    /**
     * Format amount for display
     */
    public function formatAmount($amount)
    {
        return number_format($amount, 0, '.', ',');
    }

    /**
     * Get supported mobile money providers
     */
    public function getSupportedProviders()
    {
        return [
            'mobile' => [
                'Airtel Money',
                'M-Pesa', 
                'Mixx by Yas',
                'Halotel'
            ],
            'card' => [
                'Visa',
                'Mastercard'
            ],
            'qr' => [
                'Lipa Namba (TIPS)'
            ]
        ];
    }
}
