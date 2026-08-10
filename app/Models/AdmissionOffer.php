<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionOffer extends Model
{
    protected $fillable = [
        'application_id', 'offered_class_id', 'offered_by', 'status',
        'offer_date', 'accepted_at',
    ];

    protected $casts = [
        'offer_date' => 'date',
        'accepted_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(AdmissionApplication::class, 'application_id');
    }

    public function offeredClass()
    {
        return $this->belongsTo(SchoolClass::class, 'offered_class_id');
    }
}
