<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseBackup extends Model {
    public $timestamps = false;

    protected $fillable = ['school_id', 'backup_name', 'backup_path', 'backup_size', 'status'];
}