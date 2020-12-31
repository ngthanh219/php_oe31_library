<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'image',
        'name',
        'date_of_born',
        'date_of_death',
        'description',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
