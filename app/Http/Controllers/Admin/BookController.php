<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Repositories\Author\AuthorRepositoryInterface;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Publisher\PublisherRepositoryInterface;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    protected $bookRepo, $categoryRepo, $authorRepo, $publisherRepo;

    public function __construct(
        BookRepositoryInterface $bookRepo,
        AuthorRepositoryInterface $authorRepo,
        CategoryRepositoryInterface $categoryRepo,
        PublisherRepositoryInterface $publisherRepo
    ) {
        $this->bookRepo = $bookRepo;
        $this->authorRepo = $authorRepo;
        $this->categoryRepo = $categoryRepo;
        $this->publisherRepo = $publisherRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('book.index')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $books = $this->bookRepo->getAll();

        return view('admin.book.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('book.create')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $categories = $this->categoryRepo->getChildren();
        $authors = $this->authorRepo->getAll();
        $publishers = $this->publisherRepo->getAll();

        return view('admin.book.create', compact('categories', 'authors', 'publishers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        if (!Auth::user()->can('book.store')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $data = $request->all();
        $data['in_stock'] = $data['total'];
        if (!isset($data['image'])) {
            $data['image'] = '';
        } else {
            $image = time() . '_' . $data['image']->getClientOriginalName();
            $data['image']->move('upload/book', $image);
            $data['image'] = $image;
        }
        $book = $this->bookRepo->create($data);
        $this->bookRepo->attach($book, 'categories', $data['category_id']);

        return redirect()->route('admin.books.index')->with('infoMessage', trans('book.create_book_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('book.show')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $book = $this->bookRepo->find($id);

        if ($book) {
            $book = $this->bookRepo->load($book, ['author', 'publisher', 'categories']);
            return view('admin.book.detail', compact('book'));
        }

        return redirect()->route('admin.books.index')->with('infoMessage', trans('book.isset_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('book.edit')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $categories = $this->categoryRepo->getChildren();
        $authors = $this->authorRepo->with(['books']);
        $publishers = $this->publisherRepo->with(['books']);
        $book = $this->bookRepo->find($id);
        $book = $this->bookRepo->load($book, 'author', 'publisher', 'categories');

        return view('admin.book.edit', compact('categories', 'authors', 'publishers', 'book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, $id)
    {
        if (!Auth::user()->can('book.update')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $book = $this->bookRepo->find($id);
        $data = $request->all();

        if (isset($data['image'])) {
            $image = time() . '_' . $data['image']->getClientOriginalName();
            $data['image']->move('upload/book', $image);
            $data['image'] = $image;
        } else {
            $data['image'] = $book->image;
        }

        $this->bookRepo->sync($book, 'categories', $data['category_id']);
        $this->bookRepo->update($id, $data);

        return redirect()->route('admin.books.index')->with('infoMessage', trans('book.create_book_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('book.destroy')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $book = $this->bookRepo->destroy($id);

        return redirect()->route('admin.books.index')->with('infoMessage', trans('book.delete_book_success'));
    }

    public function search(Request $request)
    {
        if (!Auth::user()->can('book.search')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $books = $this->bookRepo->search($request->key);

        return view('admin.book.search', compact('books'));
    }

    public function catePopup()
    {
        $categoryParents = $this->categoryRepo->getParentAll();

        return view('admin.book.category_popup', compact('categoryParents'));
    }

    public function listDeleteBook()
    {
        $books = $this->bookRepo->getSoftDelete();

        return view('admin.book.delete', compact('books'));
    }

    public function restoreBook($id)
    {
        $result = $this->bookRepo->restoreSoftDelete($id);

        if ($result) {
            return redirect()->route('admin.book-delete')->with('infoMessage',
                trans('message.book_restore_success'));
        }

        return redirect()->route('admin.book-delete')->with('infoMessage',
            trans('message.book_restore_fail'));
    }

    public function hardDelete($id)
    {
        $book = $this->bookRepo->findSoftDelete($id);
        $this->bookRepo->sync($book, 'categories');
        $result = $this->bookRepo->hardDelete($id);

        if ($result) {
            return redirect()->route('admin.book-delete')->with('infoMessage',
                trans('message.book_hard_delete_success'));
        }

        return redirect()->route('admin.book-delete')->with('infoMessage',
            trans('message.book_hard_delete_fail'));
    }
}
