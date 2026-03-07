<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $receiptData;

    public function __construct($payment, $receiptData)
    {
        $this->payment = $payment;
        $this->receiptData = $receiptData;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Payment Receipt - FEEDTAN CMG',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.payment-receipt',
            with: [
                'payment' => $this->payment,
                'receipt' => $this->receiptData,
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
