<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel extends Model {
    protected $fillable = ['school_id', 'name', 'gender', 'capacity', 'warden_id', 'status'];

    public function rooms() {
        return $this->hasMany(HostelRoom::class);
    }
}