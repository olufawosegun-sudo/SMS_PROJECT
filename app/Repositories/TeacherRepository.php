<?php

namespace App\Repositories;

use App\Models\Teacher;

class TeacherRepository extends BaseRepository
{
    public function __construct(Teacher $model)
    {
        parent::__construct($model);
    }

    /**
     * Get teachers by school with pagination
     */
    public function getBySchool(int $schoolId, int $perPage = 20, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get teachers by school and status
     */
    public function getBySchoolAndStatus(int $schoolId, string $status, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId)
            ->where('status', $status);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get teachers by department
     */
    public function getByDepartment(int $departmentId, array $relations = [])
    {
        $query = $this->model->where('department_id', $departmentId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get teacher statistics by school
     */
    public function getStatsBySchool(int $schoolId)
    {
        return [
            'total' => $this->model->where('school_id', $schoolId)->count(),
            'active' => $this->model->where('school_id', $schoolId)
                ->where('status', 'active')->count(),
            'inactive' => $this->model->where('school_id', $schoolId)
                ->where('status', 'inactive')->count(),
            'on_leave' => $this->model->where('school_id', $schoolId)
                ->where('status', 'on_leave')->count(),
            'male' => $this->model->where('school_id', $schoolId)
                ->whereHas('user', function ($q) {
                    $q->where('gender', 'male');
                })->count(),
            'female' => $this->model->where('school_id', $schoolId)
                ->whereHas('user', function ($q) {
                    $q->where('gender', 'female');
                })->count(),
        ];
    }

    /**
     * Find teacher by employee number
     */
    public function findByEmployeeNo(string $employeeNo, array $relations = [])
    {
        $query = $this->model->where('employee_no', $employeeNo);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Find teacher by user ID
     */
    public function findByUserId(int $userId, array $relations = [])
    {
        $query = $this->model->where('user_id', $userId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Search teachers by name or employee number
     */
    public function search(int $schoolId, string $keyword, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId)
            ->where(function ($q) use ($keyword) {
                $q->where('employee_no', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('first_name', 'LIKE', "%{$keyword}%")
                            ->orWhere('last_name', 'LIKE', "%{$keyword}%")
                            ->orWhere('email', 'LIKE', "%{$keyword}%");
                    });
            });

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get teachers assigned to a subject
     */
    public function getBySubject(int $subjectId, array $relations = [])
    {
        $query = $this->model->whereHas('subjects', function ($q) use ($subjectId) {
            $q->where('subjects.id', $subjectId);
        });

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }
}
