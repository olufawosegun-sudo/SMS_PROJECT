<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionDocument extends Model {
    protected $fillable = ['application_id', 'document_name', 'file', 'uploaded_at'];

    protected $casts = [
        'uploaded_at' => 'datetime'
    ];

    public function application() {
        return $this->belongsTo(AdmissionApplication::class, 'application_id');
    }
}