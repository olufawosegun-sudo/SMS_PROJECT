<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model {
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'school_id', 'user_id', 'admission_no', 'class_id',
        'arm_id', 'admission_date', 'photo', 'status'
    ];

    protected $casts = [
        'admission_date' => 'date'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function schoolClass() {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function arm() {
        return $this->belongsTo(ClassArm::class, 'arm_id');
    }

    public function guardians() {
        return $this->belongsToMany(Guardian::class, 'guardian_students');
    }

    public function documents() {
        return $this->hasMany(StudentDocument::class);
    }
}