<?php

namespace App\Listeners;

use App\Events\StudentPromoted;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogStudentPromotion implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StudentPromoted $event): void
    {
        $student = $event->student;

        try {
            // Log the promotion activity
            if (class_exists(ActivityLog::class)) {
                ActivityLog::create([
                    'school_id' => $student->school_id,
                    'user_id' => Auth::id(),
                    'subject_type' => get_class($student),
                    'subject_id' => $student->id,
                    'action' => 'promoted',
                    'description' => "Student promoted from class {$event->oldClassId} to class {$event->newClassId}",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'properties' => [
                        'student_id' => $student->id,
                        'admission_no' => $student->admission_no,
                        'old_class_id' => $event->oldClassId,
                        'new_class_id' => $event->newClassId,
                    ],
                ]);
            }

            Log::info('Student promotion logged', [
                'student_id' => $student->id,
                'old_class' => $event->oldClassId,
                'new_class' => $event->newClassId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log student promotion', [
                'student_id' => $student->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
