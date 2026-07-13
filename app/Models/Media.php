<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model {
    protected $fillable = ['school_id', 'file_name', 'file_path', 'file_type', 'file_size', 'uploaded_by'];
}