<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfiguration extends Model {
    protected $fillable = ['school_id', 'config_key', 'config_value', 'description'];
}