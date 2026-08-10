<?php

namespace App\Mail;

use App\Models\Guardian;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuardianWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $guardian;

    public $defaultPassword;

    public $schoolName;

    public $loginUrl;

    public function __construct(Guardian $guardian, $defaultPassword = 'password123')
    {
        $this->guardian = $guardian;
        $this->defaultPassword = $defaultPassword;
        $this->schoolName = $guardian->school->name ?? 'SMS Project';
        $this->loginUrl = url('/login');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name', $this->schoolName)
            ),
            replyTo: [
                new Address(config('mail.from.address'), config('mail.from.name', $this->schoolName)),
            ],
            subject: 'Welcome to '.$this->schoolName.' - Your Parent Portal Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.guardian-welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
