<?php

namespace App\Services;

use App\Repositories\TeacherRepository;
use App\Models\User;
use App\Models\Role;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TeacherService
{
    protected $teacherRepository;

    public function __construct(TeacherRepository $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    /**
     * Get paginated teachers for a school
     */
    public function getSchoolTeachers(int $schoolId, int $perPage = 20)
    {
        return $this->teacherRepository->getBySchool(
            $schoolId,
            $perPage,
            ['user', 'department', 'schoolBranch', 'subjects']
        );
    }

    /**
     * Get teacher statistics for a school
     */
    public function getSchoolStats(int $schoolId)
    {
        return $this->teacherRepository->getStatsBySchool($schoolId);
    }

    /**
     * Find teacher by ID with relations
     */
    public function findTeacher(int $teacherId, int $schoolId)
    {
        $teacher = $this->teacherRepository->find(
            $teacherId,
            ['*'],
            ['user', 'department', 'subjects', 'schoolBranch']
        );

        if ($teacher && $teacher->school_id !== $schoolId) {
            throw new \Exception('Unauthorized access to this teacher.');
        }

        return $teacher;
    }

    /**
     * Create a new teacher with user account
     */
    public function createTeacher(array $data, int $schoolId, ?int $branchId = null)
    {
        return DB::transaction(function () use ($data, $schoolId, $branchId) {
            $teacherRole = Role::where('school_id', $schoolId)
                ->where('name', 'Teacher')
                ->firstOrFail();

            $photoPath = null;
            if (isset($data['profile_photo']) && $data['profile_photo']) {
                $photoPath = $data['profile_photo']->store('profile-photos', 'public');
            }

            $user = User::create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $schoolId,
                'school_branch_id' => $data['school_branch_id'] ?? $branchId,
                'role_id' => $teacherRole->id,
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

            $employeeNo = $data['employee_no'] ?? $this->generateEmployeeNumber($schoolId, $user->id);

            $teacher = $this->teacherRepository->create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $schoolId,
                'school_branch_id' => $data['school_branch_id'] ?? $branchId,
                'user_id' => $user->id,
                'employee_no' => $employeeNo,
                'department_id' => $data['department_id'] ?? null,
                'qualification' => $data['qualification'] ?? null,
                'specialization' => $data['specialization'] ?? null,
                'hire_date' => $data['hire_date'] ?? now(),
                'status' => 'active',
            ]);

            $teacher->load(['user', 'department']);

            // Assign subjects if provided
            if (!empty($data['subject_ids'])) {
                $teacher->subjects()->sync($data['subject_ids']);
            }

            return $teacher;
        });
    }

    /**
     * Update an existing teacher
     */
    public function updateTeacher(int $teacherId, array $data, int $schoolId)
    {
        return DB::transaction(function () use ($teacherId, $data, $schoolId) {
            $teacher = $this->findTeacher($teacherId, $schoolId);

            if (!$teacher) {
                throw new \Exception('Teacher not found.');
            }

            if (isset($data['profile_photo']) && $data['profile_photo']) {
                if ($teacher->user->profile_photo) {
                    Storage::disk('public')->delete($teacher->user->profile_photo);
                }
                
                $photoPath = $data['profile_photo']->store('profile-photos', 'public');
                $teacher->user->update(['profile_photo' => $photoPath]);
            }

            $teacher->user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'status' => $data['status'],
            ]);

            $teacher->update([
                'department_id' => $data['department_id'] ?? null,
                'qualification' => $data['qualification'] ?? null,
                'specialization' => $data['specialization'] ?? null,
                'status' => $data['status'],
            ]);

            // Update subject assignments if provided
            if (isset($data['subject_ids'])) {
                $teacher->subjects()->sync($data['subject_ids']);
            }

            return $teacher->fresh(['user', 'department', 'subjects']);
        });
    }

    /**
     * Delete a teacher
     */
    public function deleteTeacher(int $teacherId, int $schoolId)
    {
        return DB::transaction(function () use ($teacherId, $schoolId) {
            $teacher = $this->findTeacher($teacherId, $schoolId);

            if (!$teacher) {
                throw new \Exception('Teacher not found.');
            }

            if ($teacher->user->profile_photo) {
                Storage::disk('public')->delete($teacher->user->profile_photo);
            }

            $teacher->user->delete();
            $teacher->delete();

            return true;
        });
    }

    /**
     * Search teachers
     */
    public function searchTeachers(int $schoolId, string $keyword)
    {
        return $this->teacherRepository->search(
            $schoolId,
            $keyword,
            ['user', 'department', 'subjects']
        );
    }

    /**
     * Get teachers by department
     */
    public function getTeachersByDepartment(int $departmentId)
    {
        return $this->teacherRepository->getByDepartment(
            $departmentId,
            ['user', 'subjects']
        );
    }

    /**
     * Get teachers by subject
     */
    public function getTeachersBySubject(int $subjectId)
    {
        return $this->teacherRepository->getBySubject(
            $subjectId,
            ['user', 'department']
        );
    }

    /**
     * Generate employee number
     */
    protected function generateEmployeeNumber(int $schoolId, int $userId)
    {
        $prefix = 'TCH';
        $year = date('Y');
        $sequence = str_pad($userId, 5, '0', STR_PAD_LEFT);

        return $prefix . $year . $sequence;
    }
}
