<?php

namespace App\Mail;

use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class TeacherWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $teacher;
    public $defaultPassword;
    public $schoolName;
    public $loginUrl;

    public function __construct(Staff $teacher, $defaultPassword = 'password123')
    {
        $this->teacher = $teacher;
        $this->defaultPassword = $defaultPassword;
        $this->schoolName = $teacher->school->name ?? 'SMS Project';
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
                new Address(config('mail.from.address'), config('mail.from.name', $this->schoolName))
            ],
            subject: 'Welcome to ' . $this->schoolName . ' - Your Staff Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.teacher-welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
