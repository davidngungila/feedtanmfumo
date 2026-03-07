<?php

namespace App\Http\Controllers;

use App\Services\SnippeService;
use Illuminate\Http\Request;

class TestPaymentController extends Controller
{
    protected $snippeService;

    public function __construct(SnippeService $snippeService)
    {
        $this->snippeService = $snippeService;
    }

    public function testApiConnection()
    {
        try {
            $testData = [
                'payment_type' => 'mobile',
                'details' => [
                    'amount' => 500,
                    'currency' => 'TZS'
                ],
                'phone_number' => '+255712345678',
                'customer' => [
                    'firstname' => 'Test',
                    'lastname' => 'User',
                    'email' => 'test@example.com'
                ],
                'webhook_url' => route('public.payments.webhook'),
                'metadata' => [
                    'test' => true
                ]
            ];

            $response = $this->snippeService->createPayment($testData);
            
            return response()->json([
                'status' => 'success',
                'data' => $response,
                'api_key_status' => config('services.snippe.api_key') ? 'SET' : 'NOT SET',
                'api_url' => config('services.snippe.base_url')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'api_key_status' => config('services.snippe.api_key') ? 'SET' : 'NOT SET',
                'api_url' => config('services.snippe.base_url'),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function showConfigStatus()
    {
        return response()->json([
            'snippe_config' => [
                'base_url' => config('services.snippe.base_url'),
                'api_key_set' => !empty(config('services.snippe.api_key')),
                'api_key_preview' => config('services.snippe.api_key') ? substr(config('services.snippe.api_key'), 0, 12) . '...' : 'NOT SET',
                'webhook_secret_set' => !empty(config('services.snippe.webhook_secret'))
            ]
        ]);
    }

    public function testSimplePayment()
    {
        try {
            // Test with minimal data
            $minimalData = [
                'payment_type' => 'mobile',
                'details' => [
                    'amount' => 500,
                    'currency' => 'TZS'
                ],
                'phone_number' => '+255712345678',
                'customer' => [
                    'firstname' => 'Test',
                    'lastname' => 'User',
                    'email' => 'test@example.com'
                ],
                'webhook_url' => 'https://webhook.site/test',
                'metadata' => [
                    'test' => true,
                    'timestamp' => now()->toISOString()
                ]
            ];

            // Make direct HTTP call to debug
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.snippe.api_key'),
                'Content-Type' => 'application/json',
                'Idempotency-Key' => \Illuminate\Support\Str::uuid()->toString(),
            ])->post(config('services.snippe.base_url') . '/v1/payments', $minimalData);

            return response()->json([
                'status' => 'success',
                'http_status' => $response->status(),
                'response_body' => $response->body(),
                'response_json' => $response->json(),
                'request_data' => $minimalData,
                'api_key_preview' => config('services.snippe.api_key') ? substr(config('services.snippe.api_key'), 0, 12) . '...' : 'NOT SET',
                'api_url' => config('services.snippe.base_url')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
