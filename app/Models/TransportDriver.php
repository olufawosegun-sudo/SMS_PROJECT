<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportDriver extends Model
{
    protected $fillable = [
        'school_id', 'user_id', 'staff_id', 'license_number', 'phone', 'address',
        'address_line_1', 'address_line_2', 'address_line_3', 'city', 'state', 'country',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
