<?php

namespace App\Console\Commands;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Console\Command;

class PromoteStudentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'students:promote 
                            {school_id : The ID of the school}
                            {from_class_id : The current class ID}
                            {to_class_id : The target class ID}
                            {--arm= : Optional target arm ID}
                            {--status=active : Only promote students with this status}
                            {--dry-run : Run without making actual changes}';

    /**
     * The console command description.
     */
    protected $description = 'Promote students from one class to another';

    protected $studentService;

    /**
     * Create a new command instance.
     */
    public function __construct(StudentService $studentService)
    {
        parent::__construct();
        $this->studentService = $studentService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schoolId = $this->argument('school_id');
        $fromClassId = $this->argument('from_class_id');
        $toClassId = $this->argument('to_class_id');
        $armId = $this->option('arm');
        $status = $this->option('status');
        $isDryRun = $this->option('dry-run');

        $this->info('Starting student promotion process...');
        $this->newLine();

        // Validate classes exist
        $fromClass = SchoolClass::where('id', $fromClassId)
            ->where('school_id', $schoolId)
            ->first();

        $toClass = SchoolClass::where('id', $toClassId)
            ->where('school_id', $schoolId)
            ->first();

        if (! $fromClass) {
            $this->error("Source class with ID {$fromClassId} not found for this school.");

            return 1;
        }

        if (! $toClass) {
            $this->error("Target class with ID {$toClassId} not found for this school.");

            return 1;
        }

        // Get students to promote
        $students = Student::where('school_id', $schoolId)
            ->where('class_id', $fromClassId)
            ->where('status', $status)
            ->with(['user', 'schoolClass'])
            ->get();

        if ($students->isEmpty()) {
            $this->warn("No students found in {$fromClass->name} with status '{$status}'.");

            return 0;
        }

        $this->info("Found {$students->count()} student(s) to promote from {$fromClass->name} to {$toClass->name}");
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
                'Name' => $student->user->first_name.' '.$student->user->last_name,
                'Current Class' => $student->schoolClass->name ?? 'N/A',
                'Status' => $student->status,
            ];
        })->toArray();

        $this->table(
            ['ID', 'Admission No', 'Name', 'Current Class', 'Status'],
            $tableData
        );

        if (! $isDryRun) {
            if (! $this->confirm('Do you want to proceed with the promotion?')) {
                $this->info('Promotion cancelled.');

                return 0;
            }

            // Perform promotion
            $progressBar = $this->output->createProgressBar($students->count());
            $progressBar->start();

            $studentIds = $students->pluck('id')->toArray();

            try {
                $result = $this->studentService->promoteStudents(
                    $studentIds,
                    $toClassId,
                    $armId,
                    $schoolId
                );

                $progressBar->finish();
                $this->newLine(2);

                $this->info('Promotion completed successfully!');
                $this->info("Promoted: {$result['promoted_count']} student(s)");

                if ($result['failed_count'] > 0) {
                    $this->warn("Failed: {$result['failed_count']} student(s)");
                }

                return 0;

            } catch (\Exception $e) {
                $progressBar->finish();
                $this->newLine(2);
                $this->error('Promotion failed: '.$e->getMessage());

                return 1;
            }
        } else {
            $this->info('Dry run completed. No changes were made.');

            return 0;
        }
    }
}
