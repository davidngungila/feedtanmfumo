<?php

namespace App\Mail;

use App\Models\GuarantorAssessment;
use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GuarantorSubmittedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $assessment;
    public $loan;

    /**
     * Create a new message instance.
     */
    public function __construct(GuarantorAssessment $assessment, Loan $loan)
    {
        $this->assessment = $assessment;
        $this->loan = $loan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            subject: 'Guarantor Assessment Submitted - FeedTan CMG',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content()
    {
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.guarantor-submitted',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments()
    {
        return [];
    }
}
