<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicTerm extends Model
{
    protected $fillable = ['school_id', 'session_id', 'name', 'start_date', 'end_date', 'is_current'];

    protected $casts = [
        'is_current' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }
}
