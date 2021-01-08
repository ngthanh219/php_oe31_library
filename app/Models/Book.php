<?php

namespace App\Models;

use App\Models\Author;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Publisher;
use App\Models\Rate;
use App\Models\Request;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'image',
        'name',
        'description',
        'category_id',
        'publisher_id',
        'author_id',
    ];

    public function requests()
    {
        return $this->belongsToMany(Request::class, 'book_request', 'book_id', 'request_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_category', 'book_id', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
}
