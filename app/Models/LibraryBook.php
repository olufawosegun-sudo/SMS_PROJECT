<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model {
    protected $fillable = [
        'school_id', 'book_category_id', 'isbn', 'accession_number',
        'title', 'author', 'publisher', 'publication_year', 'edition',
        'quantity', 'available', 'shelf_location', 'status'
    ];

    public function category() {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }
}