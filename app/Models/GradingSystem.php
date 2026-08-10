<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradingSystem extends Model
{
    protected $fillable = ['school_id', 'name', 'description', 'status'];

    public function scales()
    {
        return $this->hasMany(GradeScale::class);
    }
}
