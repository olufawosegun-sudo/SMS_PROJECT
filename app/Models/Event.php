<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {
    protected $fillable = [
        'school_id', 'title', 'description', 'event_type',
        'event_date', 'start_time', 'end_time', 'location', 'created_by', 'status'
    ];

    protected $casts = [
        'event_date' => 'date'
    ];
}