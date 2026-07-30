<?php

namespace App\Events;

use App\Models\Student;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentPromoted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $student;
    public $oldClassId;
    public $newClassId;

    /**
     * Create a new event instance.
     *
     * @param Student $student
     * @param int $oldClassId
     * @param int $newClassId
     */
    public function __construct(Student $student, int $oldClassId, int $newClassId)
    {
        $this->student = $student;
        $this->oldClassId = $oldClassId;
        $this->newClassId = $newClassId;
    }
}
