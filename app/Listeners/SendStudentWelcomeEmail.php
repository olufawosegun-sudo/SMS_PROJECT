<?php

namespace App\Listeners;

use App\Events\StudentRegistered;
use App\Jobs\SendWelcomeEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendStudentWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the listener may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying.
     */
    public $backoff = [10, 30, 60];

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
    public function handle(StudentRegistered $event): void
    {
        $student = $event->student;
        $password = $event->password;

        // Only send email if the student has an email address
        if (empty($student->user->email)) {
            Log::info('Skipping welcome email - no email address', [
                'student_id' => $student->id,
                'admission_no' => $student->admission_no,
            ]);
            return;
        }

        // Dispatch the job to send the welcome email
        SendWelcomeEmailJob::dispatch($student, $password, 'student');

        Log::info('Welcome email job dispatched for student', [
            'student_id' => $student->id,
            'admission_no' => $student->admission_no,
            'email' => $student->user->email,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(StudentRegistered $event, \Throwable $exception): void
    {
        Log::error('Failed to send student welcome email', [
            'student_id' => $event->student->id,
            'admission_no' => $event->student->admission_no,
            'error' => $exception->getMessage(),
        ]);
    }
}
