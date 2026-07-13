<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolBranch extends Model {
    protected $fillable = [
        'school_id', 'name', 'address', 'city', 'state',
        'country', 'phone', 'email', 'status'
    ];
}