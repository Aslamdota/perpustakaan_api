<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'publisher', 'isbn', 'publication_year', 'stock', 'description', 'category_id', 'cover_image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}