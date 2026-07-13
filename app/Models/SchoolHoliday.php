<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolHoliday extends Model {
    protected $fillable = ['school_id', 'session_id', 'term_id', 'title', 'holiday_date', 'description'];

    protected $casts = [
        'holiday_date' => 'date'
    ];
}