<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable {
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'uuid', 'school_id', 'role_id', 'first_name', 'last_name', 'other_name',
        'email', 'email_verified_at', 'phone', 'gender', 'dob',
        'profile_photo', 'password', 'status', 'last_login'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'last_login' => 'datetime',
        ];
    }

    public function getNameAttribute() {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function school() {
        return $this->belongsTo(School::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function teacher() {
        return $this->hasOne(Teacher::class);
    }

    public function student() {
        return $this->hasOne(Student::class);
    }

    public function guardian() {
        return $this->hasOne(Guardian::class);
    }
}