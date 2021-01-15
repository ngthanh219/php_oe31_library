<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('book.index')) {
            $books = Book::orderBy('id', 'DESC')->paginate(config('pagination.limit'));

            return view('admin.book.index', compact('books'));
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('book.create')) {
            $categories = Category::where('parent_id', '<>', config('category.parent_id'))->get();
            $authors = Author::all();
            $publishers = Publisher::all();

            return view('admin.book.create', compact('categories', 'authors', 'publishers'));
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        if (Auth::user()->can('book.store')) {
            $data = $request->all();
            $data['in_stock'] = $data['total'];
            if (!isset($data['image'])) {
                $data['image'] = '';
            } else {
                $image = time() . '_' . $data['image']->getClientOriginalName();
                $data['image']->move('upload/book', $image);
                $data['image'] = $image;
            }
            $book = Book::create($data);
            foreach ($data['category_id'] as $category) {
                $item = Category::findOrFail($category);
                $book->categories()->attach($item);
            }
            $request->session()->flash('infoMessage', trans('book.create_book_success'));

            return redirect()->route('admin.books.index');
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->can('book.show')) {
            $book = Book::findOrFail($id)->load('author', 'publisher', 'categories');
            if ($book) {
                return view('admin.book.detail', compact('book'));
            } else {
                session()->flash('infoMessage', trans('book.isset_id'));

                return redirect()->route('admin.books.index');
            }
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('book.edit')) {
            $categories = Category::with('books')->where('parent_id', '<>', config('category.parent_id'))->get();
            $authors = Author::with('books')->get();
            $publishers = Publisher::with('books')->get();
            $book = Book::with('author', 'publisher', 'categories')->findOrFail($id);

            return view('admin.book.edit', compact('categories', 'authors', 'publishers', 'book'));
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
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
        if (Auth::user()->can('book.update')) {
            $book = Book::findOrFail($id);
            $data = $request->all();
            if (isset($data['image'])) {
                $image = time() . '_' . $data['image']->getClientOriginalName();
                $data['image']->move('upload/book', $image);
                $data['image'] = $image;
            } else {
                $data['image'] = $book->image;
            }
            $book->categories()->sync($data['category_id']);
            $book->update($data);
            $request->session()->flash('infoMessage', trans('book.create_book_success'));

            return redirect()->route('admin.books.index');
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('book.destroy')) {
            $book = Book::findOrFail($id);
            $book->categories()->sync([]);
            $book->delete();
            session()->flash('infoMessage', trans('book.delete_book_success'));

            return redirect()->route('admin.books.index');
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    public function search(Request $request)
    {
        if (Auth::user()->can('book.search')) {
            $books = Book::where('name', 'LIKE', '%' . $request->key . '%', )->orderBy('id', 'DESC')->get();

            return view('admin.book.search', compact('books'));
        } else {
            abort(Response::HTTP_NOT_FOUND);
        }
    }

    public function catePopup()
    {
        $categoryParents = Category::where('parent_id', config('category.parent_id'))->get();

        return view('admin.book.category_popup', compact('categoryParents'));
    }
}
