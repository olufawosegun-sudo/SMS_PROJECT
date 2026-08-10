<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassArm extends Model
{
    use SoftDeletes;

    protected $fillable = ['school_id', 'school_branch_id', 'class_id', 'teacher_id', 'name', 'capacity', 'status'];

    public function schoolBranch()
    {
        return $this->belongsTo(SchoolBranch::class, 'school_branch_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Staff::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'arm_id');
    }
}
