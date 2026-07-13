<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportAssignment extends Model {
    protected $fillable = ['route_id', 'vehicle_id', 'driver_id', 'student_id', 'session_id'];

    public function route() {
        return $this->belongsTo(TransportRoute::class);
    }

    public function vehicle() {
        return $this->belongsTo(TransportVehicle::class);
    }

    public function driver() {
        return $this->belongsTo(TransportDriver::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}