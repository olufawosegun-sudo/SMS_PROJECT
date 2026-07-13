<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultApproval extends Model {
    protected $fillable = ['result_id', 'approved_by', 'status', 'approved_at', 'remarks'];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    public function result() {
        return $this->belongsTo(Result::class);
    }
}