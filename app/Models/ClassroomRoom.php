<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassroomRoom extends Model
{
    protected $fillable = ['school_id', 'name', 'building', 'floor', 'capacity', 'status'];
}
