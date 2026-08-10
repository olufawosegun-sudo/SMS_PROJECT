<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportVehicle extends Model
{
    protected $fillable = ['school_id', 'vehicle_name', 'plate_number', 'vehicle_type', 'capacity', 'status'];
}
