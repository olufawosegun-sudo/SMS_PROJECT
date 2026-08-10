<?php

namespace App\Mail;

use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PrincipalWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $principal;

    public $defaultPassword;

    public $schoolName;

    public $loginUrl;

    public function __construct(Staff $principal, $defaultPassword = 'password123')
    {
        $this->principal = $principal;
        $this->defaultPassword = $defaultPassword;
        $this->schoolName = $principal->school->name ?? 'SMS Project';
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
            subject: 'Welcome to '.$this->schoolName.' - Your Principal Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.principal-welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
