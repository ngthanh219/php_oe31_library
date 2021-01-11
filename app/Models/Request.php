<?php

namespace App\Models;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'note',
        'borrowed_date',
        'return_date',
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_request', 'request_id', 'book_id');
    }
}
