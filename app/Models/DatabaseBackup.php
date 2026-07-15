<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseBackup extends Model {
    public $timestamps = true;

    const UPDATED_AT = null; // Table only has created_at, no updated_at

    protected $fillable = ['school_id', 'backup_name', 'backup_path', 'backup_size', 'status'];
}