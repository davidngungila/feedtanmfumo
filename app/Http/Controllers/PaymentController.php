<?php

namespace App\Http\Controllers;

use App\Services\SnippeService;
use App\Services\EmailNotificationService;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $snippeService;
    protected $emailService;
    protected $smsService;

    public function __construct(SnippeService $snippeService, EmailNotificationService $emailService, SmsNotificationService $smsService)
    {
        $this->snippeService = $snippeService;
        $this->emailService = $emailService;
        $this->smsService = $smsService;
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
     * Handle webhook from Snippe for payment/payout events
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Get webhook headers
            $signature = $request->header('X-Webhook-Signature');
            $timestamp = $request->header('X-Webhook-Timestamp');
            $event = $request->header('X-Webhook-Event');
            $userAgent = $request->header('User-Agent');

            // Verify webhook signature
            if (!$this->verifyWebhookSignature($request->getContent(), $signature, $timestamp)) {
                Log::warning('Invalid webhook signature received', [
                    'event' => $event,
                    'signature' => $signature,
                    'timestamp' => $timestamp,
                    'user_agent' => $userAgent
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            // Verify user agent
            if ($userAgent !== 'Snipe-Webhook/1.0') {
                Log::warning('Invalid webhook user agent', [
                    'event' => $event,
                    'user_agent' => $userAgent
                ]);
                return response()->json(['error' => 'Invalid user agent'], 401);
            }

            $payload = $request->json();
            
            // Log webhook received
            Log::info('Webhook received', [
                'event' => $event,
                'id' => $payload['id'] ?? null,
                'type' => $payload['type'] ?? null,
                'reference' => $payload['data']['reference'] ?? null
            ]);

            // Handle different webhook events
            switch ($event) {
                case 'payout.completed':
                    return $this->handlePayoutCompleted($payload);
                
                case 'payout.failed':
                    return $this->handlePayoutFailed($payload);
                
                case 'payment.completed':
                    return $this->handlePaymentCompleted($payload);
                
                case 'payment.failed':
                    return $this->handlePaymentFailed($payload);
                
                default:
                    Log::info('Unhandled webhook event', ['event' => $event]);
                    return response()->json(['status' => 'ignored']);
            }

        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle payout completed webhook
     */
    private function handlePayoutCompleted($payload)
    {
        try {
            $data = $payload['data'];
            $reference = $data['reference'];
            $amount = $data['amount']['value'];
            $currency = $data['amount']['currency'];
            $customer = $data['customer'];
            $settlement = $data['settlement'];
            $channel = $data['channel'];
            $completedAt = $data['completed_at'];

            // Find and update payment record
            $payment = \App\Models\Payment::where('reference', $reference)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => $completedAt,
                    'settlement_data' => json_encode($settlement),
                    'channel_data' => json_encode($channel),
                    'webhook_processed_at' => now(),
                ]);

                // Send success notifications
                $this->sendPayoutSuccessNotifications($payment, $data);
                
                Log::info('Payout completed successfully', [
                    'reference' => $reference,
                    'amount' => $amount,
                    'currency' => $currency,
                    'customer' => $customer
                ]);
            } else {
                Log::warning('Payment record not found for payout completion', [
                    'reference' => $reference
                ]);
            }

            // Create webhook log
            \App\Models\PaymentWebhook::create([
                'event_id' => $payload['id'],
                'event_type' => 'payout.completed',
                'reference' => $reference,
                'payload' => json_encode($payload),
                'processed_at' => now(),
                'status' => 'success'
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Payout completion handling failed', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle payout failed webhook
     */
    private function handlePayoutFailed($payload)
    {
        try {
            $data = $payload['data'];
            $reference = $data['reference'];
            $amount = $data['amount']['value'];
            $currency = $data['amount']['currency'];
            $customer = $data['customer'];
            $failureReason = $data['failure_reason'];

            // Find and update payment record
            $payment = \App\Models\Payment::where('reference', $reference)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $failureReason,
                    'webhook_processed_at' => now(),
                ]);

                // Send failure notifications
                $this->sendPayoutFailureNotifications($payment, $data);
                
                Log::warning('Payout failed', [
                    'reference' => $reference,
                    'amount' => $amount,
                    'currency' => $currency,
                    'customer' => $customer,
                    'failure_reason' => $failureReason
                ]);
            } else {
                Log::warning('Payment record not found for payout failure', [
                    'reference' => $reference
                ]);
            }

            // Create webhook log
            \App\Models\PaymentWebhook::create([
                'event_id' => $payload['id'],
                'event_type' => 'payout.failed',
                'reference' => $reference,
                'payload' => json_encode($payload),
                'processed_at' => now(),
                'status' => 'success'
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Payout failure handling failed', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Send payout success notifications
     */
    private function sendPayoutSuccessNotifications($payment, $data)
    {
        try {
            // Send email notification
            if ($payment->customer_email) {
                Mail::send([], [], function ($message) use ($payment, $data) {
                    $message->to($payment->customer_email)
                        ->subject('Payout Completed Successfully - Feedtan CMG')
                        ->html($this->getPayoutSuccessEmailTemplate($payment, $data));
                });
            }

            // Send SMS notification
            if ($payment->customer_phone) {
                $message = "Your payout of TSh " . number_format($data['amount']['value']) . 
                          " has been completed successfully. Ref: " . $payment->reference . 
                          ". Thank you from Feedtan CMG!";
                
                // Log SMS (implement actual SMS service later)
                Log::info('SMS notification would be sent', [
                    'phone' => $payment->customer_phone,
                    'message' => $message
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send payout success notifications', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id
            ]);
        }
    }

    /**
     * Send payout failure notifications
     */
    private function sendPayoutFailureNotifications($payment, $data)
    {
        try {
            // Send email notification
            if ($payment->customer_email) {
                Mail::send([], [], function ($message) use ($payment, $data) {
                    $message->to($payment->customer_email)
                        ->subject('Payout Failed - Feedtan CMG')
                        ->html($this->getPayoutFailureEmailTemplate($payment, $data));
                });
            }

            // Send SMS notification
            if ($payment->customer_phone) {
                $message = "Your payout of TSh " . number_format($data['amount']['value']) . 
                          " failed. Reason: " . $data['failure_reason'] . 
                          ". Ref: " . $payment->reference . ". Please contact support.";
                
                // Log SMS (implement actual SMS service later)
                Log::info('SMS notification would be sent', [
                    'phone' => $payment->customer_phone,
                    'message' => $message
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send payout failure notifications', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id
            ]);
        }
    }

    /**
     * Get payout success email template
     */
    private function getPayoutSuccessEmailTemplate($payment, $data)
    {
        $amount = number_format($data['amount']['value']);
        $netAmount = number_format($data['settlement']['net']['value']);
        $fees = number_format($data['settlement']['fees']['value']);
        
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: #015425; color: white; padding: 20px; text-align: center;'>
                <h1 style='margin: 0;'>Feedtan CMG</h1>
                <p style='margin: 5px 0 0 0; opacity: 0.9;'>Payout Completed Successfully</p>
            </div>
            <div style='padding: 30px; background: #f8f9fa;'>
                <h2 style='color: #015425; margin-bottom: 20px;'>Payout Confirmation</h2>
                <div style='background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                    <p><strong>Reference:</strong> {$payment->reference}</p>
                    <p><strong>Amount:</strong> TSh {$amount}</p>
                    <p><strong>Fees:</strong> TSh {$fees}</p>
                    <p><strong>Net Amount:</strong> TSh {$netAmount}</p>
                    <p><strong>Status:</strong> <span style='color: #28a745; font-weight: bold;'>COMPLETED</span></p>
                    <p><strong>Completed At:</strong> {$data['completed_at']}</p>
                </div>
                <p style='color: #666; font-size: 14px; text-align: center; margin-top: 30px;'>
                    Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY
                </p>
            </div>
        </div>";
    }

    /**
     * Get payout failure email template
     */
    private function getPayoutFailureEmailTemplate($payment, $data)
    {
        $amount = number_format($data['amount']['value']);
        
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: #dc3545; color: white; padding: 20px; text-align: center;'>
                <h1 style='margin: 0;'>Feedtan CMG</h1>
                <p style='margin: 5px 0 0 0; opacity: 0.9;'>Payout Failed</p>
            </div>
            <div style='padding: 30px; background: #f8f9fa;'>
                <h2 style='color: #dc3545; margin-bottom: 20px;'>Payout Failed</h2>
                <div style='background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                    <p><strong>Reference:</strong> {$payment->reference}</p>
                    <p><strong>Amount:</strong> TSh {$amount}</p>
                    <p><strong>Status:</strong> <span style='color: #dc3545; font-weight: bold;'>FAILED</span></p>
                    <p><strong>Reason:</strong> {$data['failure_reason']}</p>
                </div>
                <div style='background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; margin-bottom: 20px;'>
                    <p style='margin: 0; color: #856404;'><strong>Action Required:</strong> Please check your details and try again, or contact support if the issue persists.</p>
                </div>
                <p style='color: #666; font-size: 14px; text-align: center; margin-top: 30px;'>
                    Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY
                </p>
            </div>
        </div>";
    }

    /**
     * Handle payment completed webhook
     */
    private function handlePaymentCompleted($payload)
    {
        try {
            $data = $payload['data'];
            $reference = $data['reference'];
            $amount = $data['amount']['value'];
            $currency = $data['amount']['currency'];
            $customer = $data['customer'];
            $settlement = $data['settlement'] ?? null;
            $channel = $data['channel'] ?? null;
            $completedAt = $data['completed_at'] ?? now();

            // Find and update payment record
            $payment = \App\Models\Payment::where('reference', $reference)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => $completedAt,
                    'settlement_data' => $settlement ? json_encode($settlement) : null,
                    'channel_data' => $channel ? json_encode($channel) : null,
                    'webhook_processed_at' => now(),
                ]);

                // Send success notifications
                $this->sendPaymentSuccessNotifications($payment, $data);
                
                Log::info('Payment completed successfully', [
                    'reference' => $reference,
                    'amount' => $amount,
                    'currency' => $currency,
                    'customer' => $customer
                ]);
            } else {
                Log::warning('Payment record not found for payment completion', [
                    'reference' => $reference
                ]);
            }

            // Create webhook log
            \App\Models\PaymentWebhook::create([
                'event_id' => $payload['id'],
                'event_type' => 'payment.completed',
                'reference' => $reference,
                'payload' => json_encode($payload),
                'processed_at' => now(),
                'status' => 'success'
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Payment completion handling failed', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle payment failed webhook
     */
    private function handlePaymentFailed($payload)
    {
        try {
            $data = $payload['data'];
            $reference = $data['reference'];
            $amount = $data['amount']['value'];
            $currency = $data['amount']['currency'];
            $customer = $data['customer'];
            $failureReason = $data['failure_reason'] ?? 'Unknown error';

            // Find and update payment record
            $payment = \App\Models\Payment::where('reference', $reference)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $failureReason,
                    'webhook_processed_at' => now(),
                ]);

                // Send failure notifications
                $this->sendPaymentFailureNotifications($payment, $data);
                
                Log::warning('Payment failed', [
                    'reference' => $reference,
                    'amount' => $amount,
                    'currency' => $currency,
                    'customer' => $customer,
                    'failure_reason' => $failureReason
                ]);
            } else {
                Log::warning('Payment record not found for payment failure', [
                    'reference' => $reference
                ]);
            }

            // Create webhook log
            \App\Models\PaymentWebhook::create([
                'event_id' => $payload['id'],
                'event_type' => 'payment.failed',
                'reference' => $reference,
                'payload' => json_encode($payload),
                'processed_at' => now(),
                'status' => 'success'
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Payment failure handling failed', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
                'error' => $e->getMessage(),
                'payment_id' => $payment->id
            ]);
        }
    }

    /**
     * Get payment success email template
     */
    private function getPaymentSuccessEmailTemplate($payment, $data)
    {
        $amount = number_format($data['amount']['value']);
        $netAmount = isset($data['settlement']['net']) ? number_format($data['settlement']['net']['value']) : $amount;
        $fees = isset($data['settlement']['fees']) ? number_format($data['settlement']['fees']['value']) : '0';
        
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: #015425; color: white; padding: 20px; text-align: center;'>
                <h1 style='margin: 0;'>Feedtan CMG</h1>
                <p style='margin: 5px 0 0 0; opacity: 0.9;'>Payment Completed Successfully</p>
            </div>
            <div style='padding: 30px; background: #f8f9fa;'>
                <h2 style='color: #015425; margin-bottom: 20px;'>Payment Confirmation</h2>
                <div style='background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                    <p><strong>Reference:</strong> {$payment->reference}</p>
                    <p><strong>Amount:</strong> TSh {$amount}</p>
                    <p><strong>Fees:</strong> TSh {$fees}</p>
                    <p><strong>Net Amount:</strong> TSh {$netAmount}</p>
                    <p><strong>Status:</strong> <span style='color: #28a745; font-weight: bold;'>COMPLETED</span></p>
                    <p><strong>Completed At:</strong> " . ($data['completed_at'] ?? now()) . "</p>
                </div>
                <p style='color: #666; font-size: 14px; text-align: center; margin-top: 30px;'>
                    Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY
                </p>
            </div>
        </div>";
    }

    /**
     * Get payment failure email template
     */
    private function getPaymentFailureEmailTemplate($payment, $data)
    {
        $amount = number_format($data['amount']['value']);
        
        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: #dc3545; color: white; padding: 20px; text-align: center;'>
                <h1 style='margin: 0;'>Feedtan CMG</h1>
                <p style='margin: 5px 0 0 0; opacity: 0.9;'>Payment Failed</p>
            </div>
            <div style='padding: 30px; background: #f8f9fa;'>
                <h2 style='color: #dc3545; margin-bottom: 20px;'>Payment Failed</h2>
                <div style='background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
                    <p><strong>Reference:</strong> {$payment->reference}</p>
                    <p><strong>Amount:</strong> TSh {$amount}</p>
                    <p><strong>Status:</strong> <span style='color: #dc3545; font-weight: bold;'>FAILED</span></p>
                    <p><strong>Reason:</strong> " . ($data['failure_reason'] ?? 'Unknown error') . "</p>
                </div>
                <div style='background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107; margin-bottom: 20px;'>
                    <p style='margin: 0; color: #856404;'><strong>Action Required:</strong> Please check your details and try again, or contact support if the issue persists.</p>
                </div>
                <p style='color: #666; font-size: 14px; text-align: center; margin-top: 30px;'>
                    Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY
                </p>
            </div>
        </div>";
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
