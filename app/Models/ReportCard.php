<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    protected $fillable = [
        'school_id', 'student_id', 'class_id', 'session_id', 'term_id',
        'average', 'overall_position', 'attendance', 'principal_comment',
        'teacher_comment', 'generated_by', 'generated_at', 'status',
        'approved_at', 'approved_by', 'published_at', 'published_by',
    ];

    protected $casts = [
        'average' => 'decimal:2',
        'generated_at' => 'datetime',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function term()
    {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    // Helper methods
    public function getGradeLabel()
    {
        if ($this->average >= 70) {
            return 'A - Excellent';
        }
        if ($this->average >= 60) {
            return 'B - Very Good';
        }
        if ($this->average >= 50) {
            return 'C - Good';
        }
        if ($this->average >= 40) {
            return 'D - Fair';
        }

        return 'F - Poor';
    }

    public function getPositionSuffix()
    {
        if (! $this->overall_position) {
            return '';
        }

        $position = $this->overall_position;
        $lastDigit = $position % 10;
        $lastTwoDigits = $position % 100;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 13) {
            return $position.'th';
        }

        switch ($lastDigit) {
            case 1: return $position.'st';
            case 2: return $position.'nd';
            case 3: return $position.'rd';
            default: return $position.'th';
        }
    }

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'published' => 'bg-success/10 text-success',
            'approved' => 'bg-blue-100 text-blue-700',
            'draft' => 'bg-warning/10 text-warning',
            default => 'bg-gray-100 text-gray-600'
        };
    }
}
