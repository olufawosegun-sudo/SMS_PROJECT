<?php

namespace App\Console\Commands;

use App\Jobs\GenerateReportCardJob;
use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\Student;
use Illuminate\Console\Command;

class GenerateReportCardsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reports:generate 
                            {school_id : The ID of the school}
                            {session_id : The academic session ID}
                            {term_id : The academic term ID}
                            {--class= : Optional: Generate only for specific class}
                            {--student= : Optional: Generate only for specific student ID}
                            {--queue : Queue the report generation jobs}';

    /**
     * The console command description.
     */
    protected $description = 'Generate report cards for students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schoolId = $this->argument('school_id');
        $sessionId = $this->argument('session_id');
        $termId = $this->argument('term_id');
        $classId = $this->option('class');
        $studentId = $this->option('student');
        $shouldQueue = $this->option('queue');

        $this->info('Starting report card generation...');
        $this->newLine();

        // Validate session and term
        $session = AcademicSession::where('id', $sessionId)
            ->where('school_id', $schoolId)
            ->first();

        $term = AcademicTerm::where('id', $termId)
            ->where('academic_session_id', $sessionId)
            ->first();

        if (! $session) {
            $this->error("Academic session with ID {$sessionId} not found.");

            return 1;
        }

        if (! $term) {
            $this->error("Academic term with ID {$termId} not found.");

            return 1;
        }

        $this->info("Session: {$session->name}");
        $this->info("Term: {$term->name}");
        $this->newLine();

        // Build query for students
        $query = Student::where('school_id', $schoolId)
            ->where('status', 'active')
            ->with(['user', 'schoolClass']);

        if ($studentId) {
            $query->where('id', $studentId);
        }

        if ($classId) {
            $query->where('class_id', $classId);
        }

        $students = $query->get();

        if ($students->isEmpty()) {
            $this->warn('No students found matching the criteria.');

            return 0;
        }

        $this->info("Found {$students->count()} student(s) for report generation");
        $this->newLine();

        if (! $this->confirm('Do you want to proceed?')) {
            $this->info('Operation cancelled.');

            return 0;
        }

        $progressBar = $this->output->createProgressBar($students->count());
        $progressBar->start();

        $generated = 0;
        $failed = 0;

        foreach ($students as $student) {
            try {
                if ($shouldQueue) {
                    // Dispatch job to queue
                    GenerateReportCardJob::dispatch($student->id, $sessionId, $termId);
                } else {
                    // Generate synchronously (for testing/small batches)
                    GenerateReportCardJob::dispatchSync($student->id, $sessionId, $termId);
                }

                $generated++;
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("Failed for student {$student->admission_no}: ".$e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        if ($shouldQueue) {
            $this->info("Report generation jobs queued: {$generated}");
        } else {
            $this->info("Report cards generated: {$generated}");
        }

        if ($failed > 0) {
            $this->warn("Failed: {$failed}");
        }

        return 0;
    }
}
