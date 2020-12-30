<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $fillable = [
        'image',
        'name',
        'email',
        'address',
        'phone',
        'description',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
