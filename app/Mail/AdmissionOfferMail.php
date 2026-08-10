<?php

namespace App\Mail;

use App\Models\AdmissionApplication;
use App\Models\AdmissionOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public $offer;

    public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct(AdmissionApplication $application, AdmissionOffer $offer, $pdfPath)
    {
        $this->application = $application;
        $this->offer = $offer;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), $this->application->school->name),
            subject: '🎓 Admission Offer - '.$this->application->school->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admission-offer',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('public', $this->pdfPath)
                ->as('Admission_Offer_Letter.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
