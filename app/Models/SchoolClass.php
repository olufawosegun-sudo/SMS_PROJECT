<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model {
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = ['school_id', 'name', 'level', 'description', 'status'];

    public function arms() {
        return $this->hasMany(ClassArm::class, 'class_id');
    }

    public function students() {
        return $this->hasMany(Student::class, 'class_id');
    }
}