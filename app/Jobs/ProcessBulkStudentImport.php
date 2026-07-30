<?php

namespace App\Jobs;

use App\Services\StudentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessBulkStudentImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $schoolId;
    protected $branchId;
    protected $userId;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 600; // 10 minutes for bulk operations

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, int $schoolId, ?int $branchId, int $userId)
    {
        $this->filePath = $filePath;
        $this->schoolId = $schoolId;
        $this->branchId = $branchId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(StudentService $studentService): void
    {
        try {
            Log::info('Starting bulk student import', [
                'file' => $this->filePath,
                'school_id' => $this->schoolId,
            ]);

            $imported = 0;
            $failed = 0;
            $errors = [];

            // Read the CSV file
            $fileContent = Storage::get($this->filePath);
            $rows = array_map('str_getcsv', explode("\n", $fileContent));
            $header = array_shift($rows); // Remove header row

            foreach ($rows as $index => $row) {
                if (empty(array_filter($row))) {
                    continue; // Skip empty rows
                }

                try {
                    // Map CSV columns to student data
                    $studentData = [
                        'first_name' => $row[0] ?? null,
                        'last_name' => $row[1] ?? null,
                        'email' => $row[2] ?? null,
                        'phone' => $row[3] ?? null,
                        'gender' => strtolower($row[4] ?? ''),
                        'date_of_birth' => $row[5] ?? null,
                        'class_id' => $row[6] ?? null,
                        'arm_id' => $row[7] ?? null,
                        'admission_no' => $row[8] ?? null,
                        'admission_date' => $row[9] ?? null,
                    ];

                    // Validate required fields
                    if (empty($studentData['first_name']) || empty($studentData['last_name']) || empty($studentData['class_id'])) {
                        throw new \Exception('Missing required fields');
                    }

                    // Create student
                    $studentService->createStudent($studentData, $this->schoolId, $this->branchId);
                    $imported++;

                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = [
                        'row' => $index + 2, // +2 because of header and 0-index
                        'data' => $row,
                        'error' => $e->getMessage(),
                    ];

                    Log::warning('Failed to import student', [
                        'row' => $index + 2,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('Bulk student import completed', [
                'imported' => $imported,
                'failed' => $failed,
                'school_id' => $this->schoolId,
            ]);

            // Clean up the uploaded file
            Storage::delete($this->filePath);

            // TODO: Send notification to the user who initiated the import
            // with summary of imported/failed records

        } catch (\Exception $e) {
            Log::error('Bulk student import failed', [
                'file' => $this->filePath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Bulk student import job failed', [
            'file' => $this->filePath,
            'school_id' => $this->schoolId,
            'error' => $exception->getMessage(),
        ]);

        // Clean up the file
        if (Storage::exists($this->filePath)) {
            Storage::delete($this->filePath);
        }
    }
}
