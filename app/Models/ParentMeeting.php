<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentMeeting extends Model
{
    protected $table = 'parent_meetings';

    protected $fillable = ['school_id', 'title', 'meeting_date', 'venue', 'description', 'created_by'];

    protected $casts = [
        'meeting_date' => 'date',
    ];
}
