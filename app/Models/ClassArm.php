<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassArm extends Model {
    use SoftDeletes;

    protected $fillable = ['school_id', 'class_id', 'teacher_id', 'name', 'capacity', 'status'];

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function students() {
        return $this->hasMany(Student::class, 'arm_id');
    }
}