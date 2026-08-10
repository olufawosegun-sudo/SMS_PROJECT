<?php

namespace App\Repositories;

use App\Models\WaecCandidate;

class WaecCandidateRepository extends BaseRepository
{
    public function __construct(WaecCandidate $model)
    {
        parent::__construct($model);
    }

    /**
     * Get candidates for a school with pagination.
     */
    public function getBySchool(int $schoolId, int $perPage = 20, array $relations = [])
    {
        $query = $this->model->forSchool($schoolId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get candidates for a specific session.
     */
    public function getBySession(int $schoolId, int $sessionId, array $relations = [])
    {
        $query = $this->model
            ->forSchool($schoolId)
            ->forSession($sessionId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Get candidates by payment status.
     */
    public function getByPaymentStatus(int $schoolId, string $status, array $relations = [])
    {
        $query = $this->model
            ->forSchool($schoolId)
            ->paymentStatus($status);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Get active candidates for a school.
     */
    public function getActiveCandidates(int $schoolId, array $relations = [])
    {
        $query = $this->model
            ->forSchool($schoolId)
            ->active();

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Check if student is already registered for a session.
     */
    public function isStudentRegistered(int $studentId, int $sessionId): bool
    {
        return $this->model
            ->where('student_id', $studentId)
            ->where('session_id', $sessionId)
            ->exists();
    }

    /**
     * Get candidate by student and session.
     */
    public function getByStudentAndSession(int $studentId, int $sessionId, array $relations = [])
    {
        $query = $this->model
            ->where('student_id', $studentId)
            ->where('session_id', $sessionId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Get candidates by class.
     */
    public function getByClass(int $schoolId, int $classId, array $relations = [])
    {
        $query = $this->model
            ->forSchool($schoolId)
            ->where('class_id', $classId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Get statistics for a school and session.
     */
    public function getStatistics(int $schoolId, ?int $sessionId = null)
    {
        $query = $this->model->forSchool($schoolId);

        if ($sessionId) {
            $query->forSession($sessionId);
        }

        return [
            'total_candidates' => $query->count(),
            'fully_paid' => (clone $query)->paymentStatus('paid')->count(),
            'partially_paid' => (clone $query)->paymentStatus('partial')->count(),
            'unpaid' => (clone $query)->paymentStatus('unpaid')->count(),
            'total_expected' => $query->sum('total_fee'),
            'total_paid' => $query->sum('amount_paid'),
            'total_balance' => $query->sum('balance'),
        ];
    }

    /**
     * Get candidates for a guardian's wards.
     */
    public function getGuardianCandidates(int $guardianId, array $relations = [])
    {
        $query = $this->model
            ->whereHas('student.guardians', function ($q) use ($guardianId) {
                $q->where('guardians.id', $guardianId);
            });

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }
}
