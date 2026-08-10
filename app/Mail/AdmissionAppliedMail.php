<?php

namespace App\Mail;

use App\Models\AdmissionApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionAppliedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public $schoolName;

    public function __construct(AdmissionApplication $application)
    {
        $this->application = $application;
        $this->schoolName = $application->school->name ?? 'SMS Project';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), $this->schoolName),
            subject: 'Admission Application Received - '.$this->application->application_no,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admission-applied',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
