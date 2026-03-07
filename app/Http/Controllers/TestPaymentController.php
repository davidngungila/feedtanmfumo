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
                'api_key_status' => config('services.snippe.api_key') ? 'SET' : 'NOT SET'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'api_key_status' => config('services.snippe.api_key') ? 'SET' : 'NOT SET'
            ], 500);
        }
    }

    public function showConfigStatus()
    {
        return response()->json([
            'snippe_config' => [
                'base_url' => config('services.snippe.base_url'),
                'api_key_set' => !empty(config('services.snippe.api_key')),
                'webhook_secret_set' => !empty(config('services.snippe.webhook_secret')),
            ]
        ]);
    }
}
