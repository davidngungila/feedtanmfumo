<?php

namespace App\Http\Controllers;

use App\Services\SnippeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $snippeService;

    public function __construct(SnippeService $snippeService)
    {
        $this->snippeService = $snippeService;
    }

    /**
     * Show the public payment page
     */
    public function showPaymentPage(Request $request)
    {
        $defaultAmount = $request->get('amount', 500);
        $currency = 'TZS';
        $merchantName = 'FEEDTAN CMG';

        return view('public.payments.index', compact('defaultAmount', 'currency', 'merchantName'));
    }

    /**
     * Process payment via Snippe API
     */
    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_type' => 'required|in:mobile,card,qr',
            'amount' => 'required|integer|min:500|max:5000000',
            'phone_number' => 'required_if:payment_type,mobile|regex:/^\+255[0-9]{9}$/',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customerName = explode(' ', $request->customer_name, 2);
            $firstName = $customerName[0] ?? '';
            $lastName = $customerName[1] ?? '';

            $paymentData = [
                'payment_type' => $request->payment_type,
                'details' => [
                    'amount' => $request->amount,
                    'currency' => 'TZS'
                ],
                'phone_number' => $request->phone_number ?? null,
                'customer' => [
                    'firstname' => $firstName,
                    'lastname' => $lastName,
                    'email' => $request->customer_email
                ],
                'webhook_url' => route('public.payments.webhook'),
                'metadata' => [
                    'merchant' => 'FEEDTAN CMG',
                    'source' => 'public_payment_page'
                ]
            ];

            $response = $this->snippeService->createPayment($paymentData);

            if ($response['status'] === 'success') {
                return response()->json([
                    'status' => 'success',
                    'data' => $response['data']
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $response['message'] ?? 'Payment processing failed'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your payment. Please try again.'
            ], 500);
        }
    }

    /**
     * Handle Snippe webhooks
     */
    public function handleWebhook(Request $request)
    {
        try {
            $signature = $request->header('Snippe-Signature');
            $payload = $request->getContent();

            if (!$this->snippeService->verifyWebhookSignature($payload, $signature)) {
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            $webhookData = $request->all();
            
            // Store webhook data
            \App\Models\PaymentWebhook::create([
                'payment_reference' => $webhookData['data']['reference'] ?? null,
                'status' => $webhookData['data']['status'] ?? 'unknown',
                'payload' => json_encode($webhookData),
                'processed' => false
            ]);

            return response()->json(['status' => 'received']);

        } catch (\Exception $e) {
            \Log::error('Webhook processing error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($reference)
    {
        try {
            $response = $this->snippeService->getPaymentStatus($reference);
            
            return response()->json([
                'status' => 'success',
                'data' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to check payment status'
            ], 500);
        }
    }
}
