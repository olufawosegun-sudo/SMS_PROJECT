<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model {
    use SoftDeletes;

    protected $table = 'staffs';

    protected $fillable = [
        'school_id', 'user_id', 'department_id', 'staff_no',
        'qualification', 'employment_date', 'salary', 'status',
        'staff_type'
    ];

    protected static function booted()
    {
        static::addGlobalScope('teacher', function ($builder) {
            $builder->where('staff_type', 'Teacher');
        });

        static::creating(function ($teacher) {
            $teacher->staff_type = 'Teacher';
        });
    }

    protected $casts = [
        'employment_date' => 'date',
        'salary' => 'decimal:2'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function teacherSubjects() {
        return $this->hasMany(TeacherSubject::class, 'staff_id');
    }

    public function subjects() {
        return $this->belongsToMany(Subject::class, 'teacher_subjects', 'staff_id', 'subject_id')
            ->withPivot('class_id', 'session_id', 'term_id')
            ->withTimestamps();
    }
}