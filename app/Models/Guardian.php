<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guardian extends Model {
    use SoftDeletes;

    protected $fillable = [
        'school_id', 'user_id', 'occupation', 'address',
        'address_line_1', 'address_line_2', 'address_line_3', 'city', 'state', 'country',
        'relationship', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function school() {
        return $this->belongsTo(School::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'guardian_students')
            ->withPivot('relationship', 'is_primary', 'is_emergency_contact', 'school_id')
            ->withTimestamps();
    }
}