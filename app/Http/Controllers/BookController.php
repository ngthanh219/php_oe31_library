<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Publisher\PublisherRepositoryInterface;
use Auth;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookRepo, $categoryRepo, $publisherRepo;

    public function __construct(
        BookRepositoryInterface $bookRepo,
        CategoryRepositoryInterface $categoryRepo,
        PublisherRepositoryInterface $publisherRepo
    ) {
        $this->bookRepo = $bookRepo;
        $this->categoryRepo = $categoryRepo;
        $this->publisherRepo = $publisherRepo;
    }

    public function index(Request $request)
    {
        $page = $request->page;
        $books = $this->bookRepo->getVisibleBook();

        return view('client.home', compact('books', 'page'));
    }

    public function getCategory($categoryId)
    {
        $category = $this->categoryRepo->withFind($categoryId, ['books']);

        return view('client.category_book', compact('category'));
    }

    public function getDetailBook($id)
    {
        $book = $this->bookRepo->getDetailBook($id, Auth::id());
     
        return view('client.detail_book', compact('book'));
    }

    public function search(Request $request)
    {
        $books = $this->bookRepo->searchBookVisible($request->key);

        return view('client.search_book', compact('books'));
    }
}
