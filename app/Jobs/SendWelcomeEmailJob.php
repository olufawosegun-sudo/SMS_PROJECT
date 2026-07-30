<?php

namespace App\Jobs;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Guardian;
use App\Mail\StudentWelcomeMail;
use App\Mail\TeacherWelcomeMail;
use App\Mail\GuardianWelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $password;
    protected $type;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = [30, 60, 120];

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @param mixed $model (Student, Teacher, or Guardian model)
     * @param string $password Plain text password
     * @param string $type 'student', 'teacher', or 'guardian'
     */
    public function __construct($model, string $password, string $type)
    {
        $this->model = $model;
        $this->password = $password;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $email = $this->model->user->email ?? null;

            if (empty($email)) {
                Log::warning('Cannot send welcome email - no email address', [
                    'type' => $this->type,
                    'model_id' => $this->model->id,
                ]);
                return;
            }

            // Send appropriate welcome email based on type
            switch ($this->type) {
                case 'student':
                    Mail::to($email)->send(new StudentWelcomeMail($this->model, $this->password));
                    Log::info('Student welcome email sent', [
                        'student_id' => $this->model->id,
                        'email' => $email,
                    ]);
                    break;

                case 'teacher':
                    Mail::to($email)->send(new TeacherWelcomeMail($this->model, $this->password));
                    Log::info('Teacher welcome email sent', [
                        'teacher_id' => $this->model->id,
                        'email' => $email,
                    ]);
                    break;

                case 'guardian':
                    Mail::to($email)->send(new GuardianWelcomeMail($this->model, $this->password));
                    Log::info('Guardian welcome email sent', [
                        'guardian_id' => $this->model->id,
                        'email' => $email,
                    ]);
                    break;

                default:
                    throw new \Exception("Invalid email type: {$this->type}");
            }

            // Log email sent to database if EmailLog model exists
            $this->logEmailSent($email);

        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'type' => $this->type,
                'model_id' => $this->model->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw the exception to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Welcome email job failed after all retries', [
            'type' => $this->type,
            'model_id' => $this->model->id,
            'error' => $exception->getMessage(),
        ]);

        // You can send notification to admin about failed email
        // or log it to a failed_emails table for manual review
    }

    /**
     * Log email sent to database
     */
    protected function logEmailSent(string $email): void
    {
        try {
            if (class_exists(\App\Models\EmailLog::class)) {
                \App\Models\EmailLog::create([
                    'school_id' => $this->model->school_id,
                    'recipient_email' => $email,
                    'recipient_name' => $this->model->user->first_name . ' ' . $this->model->user->last_name,
                    'subject' => 'Welcome to ' . ($this->model->school->name ?? 'School'),
                    'type' => 'welcome_' . $this->type,
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Silently fail email logging to avoid breaking the main operation
            Log::warning('Failed to log email to database', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
