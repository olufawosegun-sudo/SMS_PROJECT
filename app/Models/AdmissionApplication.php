<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model {
    protected $fillable = [
        'school_id', 'application_no', 'first_name', 'last_name', 'other_name',
        'gender', 'dob', 'guardian_name', 'guardian_phone', 'guardian_email',
        'address', 'previous_school', 'applied_class_id', 'status', 'submitted_at'
    ];

    protected $casts = [
        'dob' => 'date',
        'submitted_at' => 'datetime'
    ];

    public function school() {
        return $this->belongsTo(School::class);
    }

    public function appliedClass() {
        return $this->belongsTo(SchoolClass::class, 'applied_class_id');
    }

    public function documents() {
        return $this->hasMany(AdmissionDocument::class, 'application_id');
    }

    public function offer() {
        return $this->hasOne(AdmissionOffer::class, 'application_id');
    }
}