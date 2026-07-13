<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemModule extends Model {
    protected $fillable = ['school_id', 'module_name', 'is_enabled', 'description'];

    protected $casts = [
        'is_enabled' => 'boolean'
    ];
}