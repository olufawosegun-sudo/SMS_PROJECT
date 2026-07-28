<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class StudentWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $defaultPassword;
    public $schoolName;
    public $loginUrl;

    public function __construct(Student $student, $defaultPassword = 'password123')
    {
        $this->student = $student;
        $this->defaultPassword = $defaultPassword;
        $this->schoolName = $student->school->name ?? 'SMS Project';
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
            subject: 'Welcome to ' . $this->schoolName . ' - Your Student Portal Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student-welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
