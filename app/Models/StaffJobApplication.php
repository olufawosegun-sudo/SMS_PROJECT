<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StaffJobApplication extends Model
{
    use HasFactory;

    protected $table = 'staff_job_applications';

    protected $fillable = [
        'uuid',
        'school_id',
        'school_branch_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'position_applied',
        'qualification',
        'specialization',
        'years_of_experience',
        'previous_employer',
        'expected_salary',
        'cover_letter',
        'resume_cv',
        'certificates',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'staff_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'approved_at' => 'datetime',
        'years_of_experience' => 'integer',
        'expected_salary' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolBranch()
    {
        return $this->belongsTo(SchoolBranch::class, 'school_branch_id');
    }

    public function approvedByUser()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
