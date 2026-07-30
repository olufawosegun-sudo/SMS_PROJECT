<?php

namespace App\Services;

use App\Repositories\StudentRepository;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Events\StudentRegistered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepository $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    /**
     * Get paginated students for a school
     */
    public function getSchoolStudents(int $schoolId, int $perPage = 20)
    {
        return $this->studentRepository->getBySchool(
            $schoolId,
            $perPage,
            ['user', 'schoolClass', 'arm', 'schoolBranch']
        );
    }

    /**
     * Get student statistics for a school
     */
    public function getSchoolStats(int $schoolId)
    {
        return $this->studentRepository->getStatsBySchool($schoolId);
    }

    /**
     * Find student by ID with relations
     */
    public function findStudent(int $studentId, int $schoolId)
    {
        $student = $this->studentRepository->find(
            $studentId,
            ['*'],
            ['user', 'schoolClass', 'arm', 'guardians', 'schoolBranch']
        );

        // Verify student belongs to the school
        if ($student && $student->school_id !== $schoolId) {
            throw new \Exception('Unauthorized access to this student.');
        }

        return $student;
    }

    /**
     * Create a new student with user account
     */
    public function createStudent(array $data, int $schoolId, ?int $branchId = null)
    {
        return DB::transaction(function () use ($data, $schoolId, $branchId) {
            // Get student role
            $studentRole = Role::where('school_id', $schoolId)
                ->where('name', 'Student')
                ->firstOrFail();

            // Handle profile photo upload
            $photoPath = null;
            if (isset($data['profile_photo']) && $data['profile_photo']) {
                $photoPath = $data['profile_photo']->store('profile-photos', 'public');
            }

            // Create User account
            $user = User::create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $schoolId,
                'school_branch_id' => $data['school_branch_id'] ?? $branchId,
                'role_id' => $studentRole->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'profile_photo' => $photoPath,
                'password' => Hash::make($data['password'] ?? 'password123'),
                'status' => 'active',
            ]);

            // Generate admission number if not provided
            $admissionNo = $data['admission_no'] ?? $this->generateAdmissionNumber($schoolId, $user->id);

            // Create Student profile
            $student = $this->studentRepository->create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $schoolId,
                'school_branch_id' => $data['school_branch_id'] ?? $branchId,
                'user_id' => $user->id,
                'admission_no' => $admissionNo,
                'class_id' => $data['class_id'],
                'arm_id' => $data['arm_id'] ?? null,
                'admission_date' => $data['admission_date'] ?? now(),
                'status' => 'active',
            ]);

            // Load relationships
            $student->load(['user', 'schoolClass', 'arm']);

            // Fire event for student registration
            event(new StudentRegistered($student, $data['password'] ?? 'password123'));

            return $student;
        });
    }

    /**
     * Update an existing student
     */
    public function updateStudent(int $studentId, array $data, int $schoolId)
    {
        return DB::transaction(function () use ($studentId, $data, $schoolId) {
            $student = $this->findStudent($studentId, $schoolId);

            if (!$student) {
                throw new \Exception('Student not found.');
            }

            // Handle profile photo upload
            if (isset($data['profile_photo']) && $data['profile_photo']) {
                // Delete old photo if exists
                if ($student->user->profile_photo) {
                    Storage::disk('public')->delete($student->user->profile_photo);
                }
                
                $photoPath = $data['profile_photo']->store('profile-photos', 'public');
                $student->user->update(['profile_photo' => $photoPath]);
            }

            // Update User account
            $student->user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'status' => $data['status'],
            ]);

            // Update Student profile
            $student->update([
                'class_id' => $data['class_id'],
                'arm_id' => $data['arm_id'] ?? null,
                'status' => $data['status'],
            ]);

            return $student->fresh(['user', 'schoolClass', 'arm']);
        });
    }

    /**
     * Delete a student
     */
    public function deleteStudent(int $studentId, int $schoolId)
    {
        return DB::transaction(function () use ($studentId, $schoolId) {
            $student = $this->findStudent($studentId, $schoolId);

            if (!$student) {
                throw new \Exception('Student not found.');
            }

            // Delete profile photo if exists
            if ($student->user->profile_photo) {
                Storage::disk('public')->delete($student->user->profile_photo);
            }

            // Soft delete user and student
            $student->user->delete();
            $student->delete();

            return true;
        });
    }

    /**
     * Search students
     */
    public function searchStudents(int $schoolId, string $keyword)
    {
        return $this->studentRepository->search(
            $schoolId,
            $keyword,
            ['user', 'schoolClass', 'arm']
        );
    }

    /**
     * Get students by class
     */
    public function getStudentsByClass(int $classId, ?int $armId = null)
    {
        return $this->studentRepository->getByClassAndArm(
            $classId,
            $armId,
            ['user', 'schoolClass', 'arm']
        );
    }

    /**
     * Get students by status
     */
    public function getStudentsByStatus(int $schoolId, string $status)
    {
        return $this->studentRepository->getBySchoolAndStatus(
            $schoolId,
            $status,
            ['user', 'schoolClass', 'arm']
        );
    }

    /**
     * Promote students to next class
     */
    public function promoteStudents(array $studentIds, int $newClassId, ?int $newArmId = null, int $schoolId)
    {
        return DB::transaction(function () use ($studentIds, $newClassId, $newArmId, $schoolId) {
            $promoted = [];
            $failed = [];

            foreach ($studentIds as $studentId) {
                try {
                    $student = $this->findStudent($studentId, $schoolId);
                    
                    if ($student && $student->status === 'active') {
                        $student->update([
                            'class_id' => $newClassId,
                            'arm_id' => $newArmId,
                        ]);
                        $promoted[] = $student;
                    } else {
                        $failed[] = $studentId;
                    }
                } catch (\Exception $e) {
                    $failed[] = $studentId;
                }
            }

            return [
                'promoted' => $promoted,
                'failed' => $failed,
                'promoted_count' => count($promoted),
                'failed_count' => count($failed),
            ];
        });
    }

    /**
     * Change student status
     */
    public function changeStatus(int $studentId, string $status, int $schoolId)
    {
        $student = $this->findStudent($studentId, $schoolId);

        if (!$student) {
            throw new \Exception('Student not found.');
        }

        $student->update(['status' => $status]);
        $student->user->update(['status' => $status]);

        return $student->fresh(['user']);
    }

    /**
     * Generate admission number
     */
    protected function generateAdmissionNumber(int $schoolId, int $userId)
    {
        $prefix = 'STU';
        $year = date('Y');
        $sequence = str_pad($userId, 5, '0', STR_PAD_LEFT);

        $admissionNo = $prefix . $year . $sequence;

        // Ensure uniqueness
        $counter = 1;
        while ($this->studentRepository->admissionNumberExists($admissionNo)) {
            $admissionNo = $prefix . $year . $sequence . $counter;
            $counter++;
        }

        return $admissionNo;
    }

    /**
     * Check if admission number is available
     */
    public function isAdmissionNumberAvailable(string $admissionNo, ?int $excludeStudentId = null)
    {
        return !$this->studentRepository->admissionNumberExists($admissionNo, $excludeStudentId);
    }

    /**
     * Get student by admission number
     */
    public function findByAdmissionNumber(string $admissionNo)
    {
        return $this->studentRepository->findByAdmissionNo(
            $admissionNo,
            ['user', 'schoolClass', 'arm', 'guardians']
        );
    }
}
