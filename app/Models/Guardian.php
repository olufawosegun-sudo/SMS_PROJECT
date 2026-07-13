<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guardian extends Model {
    use SoftDeletes;

    protected $fillable = ['school_id', 'user_id', 'occupation', 'address', 'relationship', 'status'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'guardian_students');
    }
}