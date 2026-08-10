<?php

namespace App\Events;

use App\Models\Student;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $student;

    public $oldStatus;

    public $newStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(Student $student, string $oldStatus, string $newStatus)
    {
        $this->student = $student;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}
