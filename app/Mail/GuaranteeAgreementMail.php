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
    public $loan;
    public $organizationInfo;

    /**
     * Create a new message instance.
     */
    public function __construct(GuarantorAssessment $assessment, $loan, $organizationInfo = [])
    {
        $this->assessment = $assessment->load(['loan.user', 'guarantor']);
        $this->loan = $loan;
        $this->organizationInfo = $organizationInfo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'FeedTan Guarantor Assessment Confirmation - ' . $this->assessment->full_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.guarantor-assessment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('guarantor-assessment.pdf', [
            'assessment' => $this->assessment,
            'loan' => $this->loan
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Guarantor_Assessment_' . $this->assessment->ulid . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
