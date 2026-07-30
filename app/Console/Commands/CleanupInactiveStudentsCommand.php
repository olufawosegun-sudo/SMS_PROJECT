<?php

namespace App\Console\Commands;

use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupInactiveStudentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'students:cleanup 
                            {school_id : The ID of the school}
                            {--days=365 : Number of days of inactivity before cleanup}
                            {--status=inactive : Status of students to cleanup}
                            {--force : Force deletion without confirmation}
                            {--dry-run : Run without making actual changes}';

    /**
     * The console command description.
     */
    protected $description = 'Cleanup inactive students based on criteria';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schoolId = $this->argument('school_id');
        $days = $this->option('days');
        $status = $this->option('status');
        $force = $this->option('force');
        $isDryRun = $this->option('dry-run');

        $this->info('Starting student cleanup process...');
        $this->newLine();

        // Calculate the cutoff date
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Looking for students with status '{$status}' inactive for {$days}+ days (before {$cutoffDate->toDateString()})");
        $this->newLine();

        // Find students to cleanup
        $students = Student::where('school_id', $schoolId)
            ->where('status', $status)
            ->where('updated_at', '<', $cutoffDate)
            ->with(['user', 'schoolClass'])
            ->get();

        if ($students->isEmpty()) {
            $this->info('No students found matching the cleanup criteria.');
            return 0;
        }

        $this->warn("Found {$students->count()} student(s) eligible for cleanup");
        $this->newLine();

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
            $this->newLine();
        }

        // Display table of students
        $tableData = $students->map(function ($student) {
            return [
                'ID' => $student->id,
                'Admission No' => $student->admission_no,
                'Name' => $student->user->first_name . ' ' . $student->user->last_name,
                'Class' => $student->schoolClass->name ?? 'N/A',
                'Status' => $student->status,
                'Last Updated' => $student->updated_at->diffForHumans(),
            ];
        })->toArray();

        $this->table(
            ['ID', 'Admission No', 'Name', 'Class', 'Status', 'Last Updated'],
            $tableData
        );

        if (!$isDryRun) {
            if (!$force && !$this->confirm('Do you want to proceed with cleanup (soft delete)?', false)) {
                $this->info('Cleanup cancelled.');
                return 0;
            }

            $this->warn('Proceeding with cleanup...');
            $progressBar = $this->output->createProgressBar($students->count());
            $progressBar->start();

            $deleted = 0;
            $failed = 0;

            foreach ($students as $student) {
                try {
                    DB::transaction(function () use ($student) {
                        $student->user->delete(); // Soft delete
                        $student->delete(); // Soft delete
                    });
                    $deleted++;
                } catch (\Exception $e) {
                    $failed++;
                    $this->newLine();
                    $this->error("Failed to delete student {$student->admission_no}: " . $e->getMessage());
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);

            $this->info("Cleanup completed!");
            $this->info("Deleted: {$deleted} student(s)");
            
            if ($failed > 0) {
                $this->warn("Failed: {$failed} student(s)");
            }

            return 0;
        } else {
            $this->info('Dry run completed. No changes were made.');
            $this->info("Would delete: {$students->count()} student(s)");
            return 0;
        }
    }
}
