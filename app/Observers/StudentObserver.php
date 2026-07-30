<?php

namespace App\Observers;

use App\Models\Student;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentObserver
{
    /**
     * Handle the Student "creating" event.
     * This runs before the student is saved to the database.
     */
    public function creating(Student $student): void
    {
        // Ensure UUID is set
        if (empty($student->uuid)) {
            $student->uuid = (string) \Illuminate\Support\Str::uuid();
        }

        // Set default status if not provided
        if (empty($student->status)) {
            $student->status = 'active';
        }

        Log::info('Student is being created', [
            'admission_no' => $student->admission_no,
            'school_id' => $student->school_id,
        ]);
    }

    /**
     * Handle the Student "created" event.
     * This runs after the student has been saved to the database.
     */
    public function created(Student $student): void
    {
        // Log the creation activity
        $this->logActivity($student, 'created', 'Student account created');

        Log::info('Student created successfully', [
            'student_id' => $student->id,
            'admission_no' => $student->admission_no,
            'user_id' => $student->user_id,
        ]);
    }

    /**
     * Handle the Student "updating" event.
     * This runs before the student update is saved to the database.
     */
    public function updating(Student $student): void
    {
        // Check if status is changing
        if ($student->isDirty('status')) {
            $oldStatus = $student->getOriginal('status');
            $newStatus = $student->status;

            Log::info('Student status changing', [
                'student_id' => $student->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);
        }

        // Check if class is changing (promotion/demotion)
        if ($student->isDirty('class_id')) {
            $oldClassId = $student->getOriginal('class_id');
            $newClassId = $student->class_id;

            Log::info('Student class changing', [
                'student_id' => $student->id,
                'old_class_id' => $oldClassId,
                'new_class_id' => $newClassId,
            ]);
        }
    }

    /**
     * Handle the Student "updated" event.
     * This runs after the student update has been saved to the database.
     */
    public function updated(Student $student): void
    {
        // Log status change
        if ($student->wasChanged('status')) {
            $oldStatus = $student->getOriginal('status');
            $newStatus = $student->status;

            $this->logActivity(
                $student,
                'status_changed',
                "Student status changed from {$oldStatus} to {$newStatus}"
            );
        }

        // Log class change (promotion/demotion)
        if ($student->wasChanged('class_id')) {
            $oldClassId = $student->getOriginal('class_id');
            $newClassId = $student->class_id;

            $this->logActivity(
                $student,
                'class_changed',
                "Student moved from class {$oldClassId} to {$newClassId}"
            );
        }

        // Log general update
        if (!$student->wasChanged(['status', 'class_id'])) {
            $this->logActivity($student, 'updated', 'Student information updated');
        }

        Log::info('Student updated successfully', [
            'student_id' => $student->id,
            'changed_fields' => array_keys($student->getChanges()),
        ]);
    }

    /**
     * Handle the Student "deleting" event.
     * This runs before the student is deleted from the database.
     */
    public function deleting(Student $student): void
    {
        Log::warning('Student is being deleted', [
            'student_id' => $student->id,
            'admission_no' => $student->admission_no,
        ]);
    }

    /**
     * Handle the Student "deleted" event.
     * This runs after the student has been deleted from the database.
     */
    public function deleted(Student $student): void
    {
        // Log the deletion activity
        $this->logActivity($student, 'deleted', 'Student account deleted');

        Log::warning('Student deleted', [
            'student_id' => $student->id,
            'admission_no' => $student->admission_no,
        ]);
    }

    /**
     * Handle the Student "restored" event.
     * This runs after a soft-deleted student has been restored.
     */
    public function restored(Student $student): void
    {
        // Log the restoration activity
        $this->logActivity($student, 'restored', 'Student account restored');

        Log::info('Student restored', [
            'student_id' => $student->id,
            'admission_no' => $student->admission_no,
        ]);
    }

    /**
     * Handle the Student "force deleted" event.
     * This runs after the student has been permanently deleted.
     */
    public function forceDeleted(Student $student): void
    {
        Log::critical('Student permanently deleted', [
            'student_id' => $student->id,
            'admission_no' => $student->admission_no,
        ]);
    }

    /**
     * Log activity for audit trail
     */
    protected function logActivity(Student $student, string $action, string $description): void
    {
        try {
            // Only log if ActivityLog model exists
            if (class_exists(ActivityLog::class)) {
                ActivityLog::create([
                    'school_id' => $student->school_id,
                    'user_id' => Auth::id(),
                    'subject_type' => Student::class,
                    'subject_id' => $student->id,
                    'action' => $action,
                    'description' => $description,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'properties' => [
                        'student_id' => $student->id,
                        'admission_no' => $student->admission_no,
                        'class_id' => $student->class_id,
                        'status' => $student->status,
                    ],
                ]);
            }
        } catch (\Exception $e) {
            // Silently fail activity logging to avoid breaking the main operation
            Log::error('Failed to log student activity', [
                'error' => $e->getMessage(),
                'student_id' => $student->id,
                'action' => $action,
            ]);
        }
    }
}
