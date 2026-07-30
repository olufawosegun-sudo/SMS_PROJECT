<?php

namespace App\Listeners;

use App\Events\StudentStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyGuardiansOfStatusChange implements ShouldQueue
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
    public function handle(StudentStatusChanged $event): void
    {
        $student = $event->student;
        $oldStatus = $event->oldStatus;
        $newStatus = $event->newStatus;

        // Load guardians
        $student->load('guardians.user');

        // Get guardians with email addresses
        $guardians = $student->guardians->filter(function ($guardian) {
            return !empty($guardian->user->email);
        });

        if ($guardians->isEmpty()) {
            Log::info('No guardians with email to notify', [
                'student_id' => $student->id,
            ]);
            return;
        }

        // Send notification to each guardian
        foreach ($guardians as $guardian) {
            try {
                // You can implement a custom notification class here
                // For now, we'll just log it
                Log::info('Guardian notification queued', [
                    'student_id' => $student->id,
                    'guardian_id' => $guardian->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'guardian_email' => $guardian->user->email,
                ]);

                // TODO: Implement actual notification
                // Notification::send($guardian->user, new StudentStatusChangedNotification($student, $oldStatus, $newStatus));
            } catch (\Exception $e) {
                Log::error('Failed to notify guardian', [
                    'guardian_id' => $guardian->id,
                    'student_id' => $student->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
