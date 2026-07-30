<?php

namespace App\Repositories;

use App\Models\Student;
use App\Models\User;

class StudentRepository extends BaseRepository
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    /**
     * Get students by school with pagination
     */
    public function getBySchool(int $schoolId, int $perPage = 20, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get students by school and status
     */
    public function getBySchoolAndStatus(int $schoolId, string $status, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId)
            ->where('status', $status);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get students by class
     */
    public function getByClass(int $classId, array $relations = [])
    {
        $query = $this->model->where('class_id', $classId);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get students by class and arm
     */
    public function getByClassAndArm(int $classId, ?int $armId = null, array $relations = [])
    {
        $query = $this->model->where('class_id', $classId);

        if ($armId) {
            $query->where('arm_id', $armId);
        }

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get student statistics by school
     */
    public function getStatsBySchool(int $schoolId)
    {
        return [
            'total' => $this->model->where('school_id', $schoolId)->count(),
            'active' => $this->model->where('school_id', $schoolId)
                ->where('status', 'active')->count(),
            'inactive' => $this->model->where('school_id', $schoolId)
                ->where('status', 'inactive')->count(),
            'graduated' => $this->model->where('school_id', $schoolId)
                ->where('status', 'graduated')->count(),
            'male' => $this->model->where('school_id', $schoolId)
                ->whereHas('user', function($q) {
                    $q->where('gender', 'male');
                })->count(),
            'female' => $this->model->where('school_id', $schoolId)
                ->whereHas('user', function($q) {
                    $q->where('gender', 'female');
                })->count(),
        ];
    }

    /**
     * Find student by admission number
     */
    public function findByAdmissionNo(string $admissionNo, array $relations = [])
    {
        $query = $this->model->where('admission_no', $admissionNo);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Find student by user ID
     */
    public function findByUserId(int $userId, array $relations = [])
    {
        $query = $this->model->where('user_id', $userId);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Create student with user account
     */
    public function createWithUser(array $userData, array $studentData)
    {
        // This will be handled by the service layer
        // Repository should focus on data access only
        return $this->create($studentData);
    }

    /**
     * Update student with user account
     */
    public function updateWithUser(int $studentId, array $userData, array $studentData)
    {
        $student = $this->find($studentId, ['*'], ['user']);
        
        if ($student && $student->user) {
            $student->user->update($userData);
        }
        
        return $this->update($studentId, $studentData);
    }

    /**
     * Soft delete student with user account
     */
    public function deleteWithUser(int $studentId)
    {
        $student = $this->find($studentId, ['*'], ['user']);
        
        if ($student && $student->user) {
            $student->user->delete();
        }
        
        return $this->delete($studentId);
    }

    /**
     * Search students by name or admission number
     */
    public function search(int $schoolId, string $keyword, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId)
            ->where(function($q) use ($keyword) {
                $q->where('admission_no', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('user', function($userQuery) use ($keyword) {
                        $userQuery->where('first_name', 'LIKE', "%{$keyword}%")
                            ->orWhere('last_name', 'LIKE', "%{$keyword}%")
                            ->orWhere('email', 'LIKE', "%{$keyword}%");
                    });
            });

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Check if admission number exists
     */
    public function admissionNumberExists(string $admissionNo, ?int $excludeStudentId = null)
    {
        $query = $this->model->where('admission_no', $admissionNo);

        if ($excludeStudentId) {
            $query->where('id', '!=', $excludeStudentId);
        }

        return $query->exists();
    }
}
