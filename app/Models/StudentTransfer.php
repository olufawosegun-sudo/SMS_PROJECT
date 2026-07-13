<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTransfer extends Model {
    protected $fillable = [
        'student_id', 'transfer_type', 'school_name', 'reason',
        'transfer_date', 'approved_by'
    ];

    protected $casts = [
        'transfer_date' => 'date'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }
}