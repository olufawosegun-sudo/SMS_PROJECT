<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    protected $fillable = ['school_id', 'name', 'description'];

    public function books()
    {
        return $this->hasMany(LibraryBook::class);
    }
}
