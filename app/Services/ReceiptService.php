<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReceiptService
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Generate and send payment receipt
     */
    public function generateAndSendReceipt(Payment $payment)
    {
        try {
            // Generate receipt data
            $receiptData = $this->generateReceiptData($payment);
            
            // Save receipt to database
            $payment->update([
                'receipt_data' => json_encode($receiptData),
                'receipt_sent_at' => now(),
            ]);

            // Send email receipt
            $this->sendEmailReceipt($payment, $receiptData);
            
            // Send SMS receipt
            $this->sendSMSReceipt($payment, $receiptData);

            return true;

        } catch (\Exception $e) {
            Log::error('Receipt generation failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Generate receipt data
     */
    private function generateReceiptData(Payment $payment)
    {
        return [
            'receipt_number' => 'RCPT-' . strtoupper(Str::random(8)),
            'payment_reference' => $payment->reference,
            'transaction_date' => $payment->created_at->format('d M Y, H:i'),
            'customer_name' => $payment->customer_name,
            'customer_email' => $payment->customer_email,
            'customer_phone' => $payment->phone_number,
            'payment_method' => $payment->payment_type,
            'amount' => $payment->amount,
            'fee' => $payment->fee,
            'total_amount' => $payment->total_amount,
            'currency' => 'TZS',
            'status' => $payment->status,
            'merchant' => 'FEEDTAN CMG',
            'powered_by' => 'Feedtan CMG @2026 SECURED PAYMENT GATEWAY',
            'support_email' => 'support@feedtan.com',
            'support_phone' => '+255 712 345 678'
        ];
    }

    /**
     * Send email receipt
     */
    private function sendEmailReceipt(Payment $payment, $receiptData)
    {
        try {
            Mail::send(new \App\Mail\PaymentReceiptMail($payment, $receiptData));
            Log::info('Email receipt sent', ['payment_id' => $payment->id]);
        } catch (\Exception $e) {
            Log::error('Email receipt failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send SMS receipt
     */
    private function sendSMSReceipt(Payment $payment, $receiptData)
    {
        try {
            $message = $this->formatSMSMessage($receiptData);
            $this->smsService->send($payment->phone_number, $message);
            Log::info('SMS receipt sent', ['payment_id' => $payment->id]);
        } catch (\Exception $e) {
            Log::error('SMS receipt failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Format SMS message
     */
    private function formatSMSMessage($receiptData)
    {
        return "FEEDTAN CMG Payment Receipt\n" .
               "Receipt: {$receiptData['receipt_number']}\n" .
               "Amount: TSh " . number_format($receiptData['amount']) . "\n" .
               "Fee: TSh " . number_format($receiptData['fee']) . "\n" .
               "Total: TSh " . number_format($receiptData['total_amount']) . "\n" .
               "Status: {$receiptData['status']}\n" .
               "Powered by Feedtan CMG @2026 SECURED PAYMENT GATEWAY";
    }
}
