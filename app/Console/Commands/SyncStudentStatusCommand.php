<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;

class SyncStudentStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'students:sync-status 
                            {school_id : The ID of the school}
                            {--fix-mismatches : Automatically fix user/student status mismatches}';

    /**
     * The console command description.
     */
    protected $description = 'Sync student status with their user account status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schoolId = $this->argument('school_id');
        $shouldFix = $this->option('fix-mismatches');

        $this->info('Checking student status synchronization...');
        $this->newLine();

        // Find students where status doesn't match user status
        $students = Student::where('school_id', $schoolId)
            ->whereHas('user', function ($query) {
                $query->whereColumn('users.status', '!=', 'students.status');
            })
            ->with(['user', 'schoolClass'])
            ->get();

        if ($students->isEmpty()) {
            $this->info('All student statuses are synchronized with user accounts.');

            return 0;
        }

        $this->warn("Found {$students->count()} student(s) with status mismatches");
        $this->newLine();

        // Display mismatches
        $tableData = $students->map(function ($student) {
            return [
                'ID' => $student->id,
                'Admission No' => $student->admission_no,
                'Name' => $student->user->first_name.' '.$student->user->last_name,
                'Student Status' => $student->status,
                'User Status' => $student->user->status,
            ];
        })->toArray();

        $this->table(
            ['ID', 'Admission No', 'Name', 'Student Status', 'User Status'],
            $tableData
        );

        if ($shouldFix) {
            if (! $this->confirm('Do you want to sync these statuses? (User status will be updated to match student status)')) {
                $this->info('Sync cancelled.');

                return 0;
            }

            $progressBar = $this->output->createProgressBar($students->count());
            $progressBar->start();

            $fixed = 0;
            $failed = 0;

            foreach ($students as $student) {
                try {
                    $student->user->update(['status' => $student->status]);
                    $fixed++;
                } catch (\Exception $e) {
                    $failed++;
                    $this->newLine();
                    $this->error("Failed to sync student {$student->admission_no}: ".$e->getMessage());
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);

            $this->info('Sync completed!');
            $this->info("Fixed: {$fixed} student(s)");

            if ($failed > 0) {
                $this->warn("Failed: {$failed} student(s)");
            }

            return 0;
        } else {
            $this->info('Run with --fix-mismatches to automatically fix these issues.');

            return 0;
        }
    }
}
