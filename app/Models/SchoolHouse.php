<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolHouse extends Model
{
    protected $fillable = ['school_id', 'name', 'color', 'house_master', 'status'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_houses', 'house_id', 'student_id');
    }
}
