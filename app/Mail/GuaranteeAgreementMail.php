<?php

namespace App\Mail;

use App\Models\GuarantorAssessment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class GuaranteeAgreementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $assessment;

    /**
     * Create a new message instance.
     */
    public function __construct(GuarantorAssessment $assessment)
    {
        $this->assessment = $assessment->load(['loan.user', 'guarantor']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'FeedTan Loan Guarantee Agreement - ' . $this->assessment->loan->loan_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.guarantee-agreement',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('pdfs.guarantee-agreement', [
            'assessment' => $this->assessment
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Guarantee_Agreement_' . $this->assessment->ulid . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
