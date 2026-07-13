<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMedicalRecord extends Model {
    protected $fillable = [
        'student_id', 'blood_group', 'genotype', 'allergies',
        'medical_condition', 'doctor_name', 'hospital', 'emergency_contact'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }
}