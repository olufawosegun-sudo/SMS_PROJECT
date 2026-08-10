<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['student_id', 'certificate_type', 'certificate_number', 'issued_date', 'issued_by', 'status'];

    protected $casts = [
        'issued_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
