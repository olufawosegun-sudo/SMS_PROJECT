<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model {
    protected $fillable = ['assignment_id', 'student_id', 'file', 'score', 'remark', 'submitted_at'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'score' => 'decimal:2'
    ];

    public function assignment() {
        return $this->belongsTo(Assignment::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}