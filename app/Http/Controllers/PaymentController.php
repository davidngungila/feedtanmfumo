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

    /**
     * Generate and send payment receipt
     */
    public function generateReceipt(Request $request)
    {
        try {
            $paymentData = $request->input('payment_data');
            $customerName = $request->input('customer_name');
            $customerEmail = $request->input('customer_email');
            $phoneNumber = $request->input('phone_number');
            $amount = $request->input('amount');
            $paymentType = $request->input('payment_type');

            // Create payment record
            $payment = \App\Models\Payment::create([
                'reference' => $paymentData['data']['reference'] ?? uniqid('PAY'),
                'amount' => $amount,
                'currency' => 'TZS',
                'payment_type' => $paymentType,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $phoneNumber,
                'status' => 'completed',
                'payment_data' => json_encode($paymentData),
                'paid_at' => now(),
            ]);

            // Generate PDF receipt
            $pdf = $this->generateReceiptPDF($payment, $paymentData, $customerName, $customerEmail, $phoneNumber, $amount, $paymentType);

            // Send email receipt
            $this->sendEmailReceipt($customerEmail, $customerName, $pdf, $payment);

            // Send SMS receipt
            if ($phoneNumber) {
                $this->sendSMSReceipt($phoneNumber, $customerName, $amount, $payment->reference);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Receipt generated and sent successfully',
                'payment_id' => $payment->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Receipt generation error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate receipt'
            ], 500);
        }
    }

    /**
     * Generate PDF receipt using membership structure
     */
    private function generateReceiptPDF($payment, $paymentData, $customerName, $customerEmail, $phoneNumber, $amount, $paymentType)
    {
        // Create user object for PDF template compatibility
        $user = new \stdClass();
        $user->name = $customerName;
        $user->email = $customerEmail;
        $user->phone = $phoneNumber;
        $user->membership_code = $payment->reference;

        // Prepare data for PDF
        $pdfData = [
            'payment' => $payment,
            'payment_data' => $paymentData,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'phone_number' => $phoneNumber,
            'amount' => $amount,
            'payment_type' => $paymentType,
            'reference' => $payment->reference,
            'paid_at' => $payment->paid_at->format('d M Y, H:i'),
        ];

        // Use the membership PDF template
        $pdf = \PDF::loadView('member.membership.pdf', [
            'user' => $user,
            'payment' => $payment,
            'paymentData' => $pdfData,
            'isReceipt' => true
        ]);

        // Save PDF
        $filename = 'receipt_' . $payment->reference . '.pdf';
        $pdf->save(public_path('receipts/' . $filename));

        return $filename;
    }

    /**
     * Send email receipt
     */
    private function sendEmailReceipt($email, $customerName, $pdfFilename, $payment)
    {
        try {
            \Mail::send([
                'to' => $email,
                'subject' => 'Payment Receipt - Feedtan CMG',
                'template' => 'emails.payment-receipt',
                'data' => [
                    'customerName' => $customerName,
                    'payment' => $payment,
                    'pdfPath' => public_path('receipts/' . $pdfFilename)
                ]
            ]);

            \Log::info('Receipt email sent to: ' . $email);
        } catch (\Exception $e) {
            \Log::error('Failed to send receipt email: ' . $e->getMessage());
        }
    }

    /**
     * Send SMS receipt
     */
    private function sendSMSReceipt($phoneNumber, $customerName, $amount, $reference)
    {
        try {
            $message = "Dear {$customerName}, your payment of TSh " . number_format($amount) . " has been received successfully. Ref: {$reference}. Thank you from Feedtan CMG!";
            
            // Use Feedtan sender ID for SMS
            $senderId = config('services.feedtan.sms_sender_id', 'FEEDTAN');
            
            // Here you would integrate with your SMS service
            // For now, we'll just log it
            \Log::info('SMS receipt would be sent to ' . $phoneNumber . ': ' . $message);
            
            // Example SMS integration (uncomment when you have SMS service):
            /*
            $smsService = new \App\Services\SmsNotificationService();
            $smsService->sendSms($phoneNumber, $message, $senderId);
            */
            
        } catch (\Exception $e) {
            \Log::error('Failed to send receipt SMS: ' . $e->getMessage());
        }
    }
}
