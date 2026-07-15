<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
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
            subject: 'Welcome to ' . $this->schoolName . ' - Your Student Account',
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
