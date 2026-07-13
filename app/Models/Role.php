<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model {
    use SoftDeletes;

    protected $fillable = ['school_id', 'name', 'description'];

    public function school() {
        return $this->belongsTo(School::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
}