<?php

namespace App\Repositories;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceRepository extends BaseRepository
{
    public function __construct(Attendance $model)
    {
        parent::__construct($model);
    }

    /**
     * Get attendance records by school with pagination
     */
    public function getBySchool(int $schoolId, int $perPage = 20, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest('date')->paginate($perPage);
    }

    /**
     * Get attendance by student
     */
    public function getByStudent(int $studentId, ?Carbon $startDate = null, ?Carbon $endDate = null, array $relations = [])
    {
        $query = $this->model->where('student_id', $studentId);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Get attendance by class
     */
    public function getByClass(int $classId, Carbon $date, array $relations = [])
    {
        $query = $this->model->where('class_id', $classId)
            ->whereDate('date', $date);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get attendance by date
     */
    public function getByDate(int $schoolId, Carbon $date, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId)
            ->whereDate('date', $date);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Check if attendance exists for student on date
     */
    public function existsForStudentOnDate(int $studentId, Carbon $date): bool
    {
        return $this->model->where('student_id', $studentId)
            ->whereDate('date', $date)
            ->exists();
    }

    /**
     * Get attendance statistics by student
     */
    public function getStatsByStudent(int $studentId, ?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        $query = $this->model->where('student_id', $studentId);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $total = $query->count();
        $present = $query->where('status', 'present')->count();
        $absent = $query->where('status', 'absent')->count();
        $late = $query->where('status', 'late')->count();
        $excused = $query->where('status', 'excused')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get attendance statistics by class
     */
    public function getStatsByClass(int $classId, Carbon $date)
    {
        $query = $this->model->where('class_id', $classId)
            ->whereDate('date', $date);

        $total = $query->count();
        $present = $query->where('status', 'present')->count();
        $absent = $query->where('status', 'absent')->count();
        $late = $query->where('status', 'late')->count();
        $excused = $query->where('status', 'excused')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Get attendance statistics by school for date range
     */
    public function getStatsBySchool(int $schoolId, Carbon $startDate, Carbon $endDate)
    {
        $query = $this->model->where('school_id', $schoolId)
            ->whereBetween('date', [$startDate, $endDate]);

        $total = $query->count();
        $present = $query->where('status', 'present')->count();
        $absent = $query->where('status', 'absent')->count();
        $late = $query->where('status', 'late')->count();
        $excused = $query->where('status', 'excused')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
            'daily_breakdown' => $query->selectRaw('DATE(date) as date, status, COUNT(*) as count')
                ->groupBy('date', 'status')
                ->get(),
        ];
    }

    /**
     * Get students with poor attendance
     */
    public function getStudentsWithPoorAttendance(int $schoolId, float $thresholdPercentage, Carbon $startDate, Carbon $endDate)
    {
        // This would require a more complex query or post-processing
        // For now, returning students who have been absent more than a certain number of times
        $absentThreshold = 5; // Configurable

        return $this->model->where('school_id', $schoolId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'absent')
            ->selectRaw('student_id, COUNT(*) as absent_count')
            ->groupBy('student_id')
            ->having('absent_count', '>=', $absentThreshold)
            ->with(['student.user'])
            ->get();
    }

    /**
     * Bulk create attendance records
     */
    public function bulkCreate(array $attendanceData)
    {
        return $this->model->insert($attendanceData);
    }
}
