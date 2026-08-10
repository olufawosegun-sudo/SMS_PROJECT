<?php

namespace App\Repositories;

use App\Models\Result;

class ResultRepository extends BaseRepository
{
    public function __construct(Result $model)
    {
        parent::__construct($model);
    }

    /**
     * Get results by school with pagination
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
     * Get results by student
     */
    public function getByStudent(int $studentId, ?int $sessionId = null, ?int $termId = null, array $relations = [])
    {
        $query = $this->model->where('student_id', $studentId);

        if ($sessionId) {
            $query->where('academic_session_id', $sessionId);
        }

        if ($termId) {
            $query->where('academic_term_id', $termId);
        }

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get results by class
     */
    public function getByClass(int $classId, int $sessionId, int $termId, array $relations = [])
    {
        $query = $this->model->where('class_id', $classId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get results by subject
     */
    public function getBySubject(int $subjectId, int $sessionId, int $termId, array $relations = [])
    {
        $query = $this->model->where('subject_id', $subjectId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Find result by student, subject, session, and term
     */
    public function findByStudentAndSubject(int $studentId, int $subjectId, int $sessionId, int $termId, array $relations = [])
    {
        $query = $this->model->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Check if result exists
     */
    public function exists(int $studentId, int $subjectId, int $sessionId, int $termId): bool
    {
        return $this->model->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId)
            ->exists();
    }

    /**
     * Get student average score
     */
    public function getStudentAverage(int $studentId, int $sessionId, int $termId)
    {
        return $this->model->where('student_id', $studentId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId)
            ->avg('total_score');
    }

    /**
     * Get student total score
     */
    public function getStudentTotalScore(int $studentId, int $sessionId, int $termId)
    {
        return $this->model->where('student_id', $studentId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId)
            ->sum('total_score');
    }

    /**
     * Get class statistics for a subject
     */
    public function getSubjectStatsByClass(int $classId, int $subjectId, int $sessionId, int $termId)
    {
        $results = $this->model->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId)
            ->get();

        if ($results->isEmpty()) {
            return null;
        }

        return [
            'total_students' => $results->count(),
            'highest_score' => $results->max('total_score'),
            'lowest_score' => $results->min('total_score'),
            'average_score' => $results->avg('total_score'),
            'pass_count' => $results->where('grade', '!=', 'F')->count(),
            'fail_count' => $results->where('grade', 'F')->count(),
            'grade_distribution' => $results->groupBy('grade')->map->count(),
        ];
    }

    /**
     * Get class rankings
     */
    public function getClassRankings(int $classId, int $sessionId, int $termId, array $relations = [])
    {
        // Get all students with their total scores
        $query = $this->model->where('class_id', $classId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId)
            ->selectRaw('student_id, SUM(total_score) as total_score, AVG(total_score) as average_score')
            ->groupBy('student_id')
            ->orderByDesc('total_score');

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get student position in class
     */
    public function getStudentPosition(int $studentId, int $classId, int $sessionId, int $termId): ?int
    {
        $studentTotal = $this->model->where('student_id', $studentId)
            ->where('class_id', $classId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId)
            ->sum('total_score');

        if (! $studentTotal) {
            return null;
        }

        // Count how many students have higher total scores
        $position = $this->model->where('class_id', $classId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId)
            ->selectRaw('student_id, SUM(total_score) as total')
            ->groupBy('student_id')
            ->havingRaw('SUM(total_score) > ?', [$studentTotal])
            ->count();

        return $position + 1;
    }

    /**
     * Bulk create or update results
     */
    public function bulkUpsert(array $results, array $uniqueBy = ['student_id', 'subject_id', 'academic_session_id', 'academic_term_id'])
    {
        return $this->model->upsert($results, $uniqueBy);
    }

    /**
     * Get students who failed a subject
     */
    public function getFailedStudents(int $subjectId, int $sessionId, int $termId, array $relations = [])
    {
        $query = $this->model->where('subject_id', $subjectId)
            ->where('academic_session_id', $sessionId)
            ->where('academic_term_id', $termId)
            ->where('grade', 'F');

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }
}
