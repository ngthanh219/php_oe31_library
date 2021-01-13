<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page;
        $books = Book::with('categories')->orderBy('id', 'desc')->where('status', config('book.visible'))->paginate(config('pagination.limit'));

        return view('client.home', compact('books', 'page'));
    }

    public function getCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId)->load('books');

        return view('client.category_book', compact('category'));
    }

    public function getDetailBook($id)
    {
        $book = Book::findOrFail($id)->load(['publisher', 'likes.user' , 'categories.books' => function ($query) {
            $query->inRandomOrder()->get()->take(config('pagination.limit'));
        }]);

        return view('client.detail_book', compact('book'));
    }
}
