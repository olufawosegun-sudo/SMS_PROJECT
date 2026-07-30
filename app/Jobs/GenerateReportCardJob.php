<?php

namespace App\Jobs;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateReportCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentId;
    protected $sessionId;
    protected $termId;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 2;

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(int $studentId, int $sessionId, int $termId)
    {
        $this->studentId = $studentId;
        $this->sessionId = $sessionId;
        $this->termId = $termId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Generating report card', [
                'student_id' => $this->studentId,
                'session_id' => $this->sessionId,
                'term_id' => $this->termId,
            ]);

            $student = Student::with([
                'user',
                'schoolClass',
                'arm',
                'school'
            ])->findOrFail($this->studentId);

            // TODO: Implement actual report card generation logic
            // This would involve:
            // 1. Fetching all results for the student in this session/term
            // 2. Calculating grades, GPA, position, etc.
            // 3. Generating PDF using a library like DomPDF or TCPDF
            // 4. Storing the generated PDF
            // 5. Optionally emailing it to student/guardians

            // For now, just log it
            Log::info('Report card generated successfully', [
                'student_id' => $this->studentId,
            ]);

            // Example: Store in reports table
            // ReportCard::create([...]);

        } catch (\Exception $e) {
            Log::error('Failed to generate report card', [
                'student_id' => $this->studentId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Report card generation job failed', [
            'student_id' => $this->studentId,
            'session_id' => $this->sessionId,
            'term_id' => $this->termId,
            'error' => $exception->getMessage(),
        ]);
    }
}
