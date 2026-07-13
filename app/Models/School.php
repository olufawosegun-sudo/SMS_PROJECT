<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model {
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'name', 'school_code', 'email', 'phone', 'website',
        'address', 'city', 'state', 'country', 'logo', 'motto'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function roles() {
        return $this->hasMany(Role::class);
    }

    public function classes() {
        return $this->hasMany(SchoolClass::class);
    }

    public function sessions() {
        return $this->hasMany(AcademicSession::class);
    }

    public function settings() {
        return $this->hasMany(Setting::class);
    }
}