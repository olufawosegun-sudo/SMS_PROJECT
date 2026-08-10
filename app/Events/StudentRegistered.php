<?php

namespace App\Events;

use App\Models\Student;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $student;

    public $password;

    /**
     * Create a new event instance.
     *
     * @param  string  $password  The plain text password to send in the welcome email
     */
    public function __construct(Student $student, string $password)
    {
        $this->student = $student;
        $this->password = $password;
    }
}
