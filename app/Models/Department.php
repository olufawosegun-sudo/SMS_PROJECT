<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model {
    use SoftDeletes;

    protected $fillable = ['school_id', 'name', 'description', 'status'];

    public function teachers() {
        return $this->hasMany(Teacher::class);
    }
}