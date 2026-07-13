<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model {
    protected $table = 'alumni';

    protected $fillable = [
        'student_id', 'graduation_year', 'current_occupation',
        'organization', 'phone', 'email'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }
}