<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Request;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;

class RequestController extends Controller
{
    public function index()
    {
        $requests = Auth::user()->requests()->paginate(config('pagination.list_request'));

        return view('client.list_request', compact('requests'));
    }

    public function show($id)
    {   
        $request = Request::findOrFail($id)->load('user');
        
        return view('client.detail_request', compact('request'));
    }

    public function cart()
    {
        $categories = Category::with('children')->where('parent_id', config('category.parent_id'))->get();
        $authors = Author::get()->take(config('pagination.limit_author'));
        $cart = session()->get('cart');

        return view('client.cart', compact('categories', 'authors', 'cart'));
    }

    public function addToCart($id)
    {
        $cart = session()->get('cart');
        $book = Book::findOrFail($id);
        if ($book->in_stock == config('book.visible')) {
            return response()->json([
                'message' => trans('request.out_of_stock'),
            ]);
        } else {
            if (empty($cart)) {
                $cart = [
                    $id => [
                        'id' => $id,
                        'image' => $book->image,
                        'name' => $book->name,
                    ],
                ];
                session()->put('cart', $cart);
                session()->save();

                return response()->json([
                    'message' => trans('request.add_cart'),
                ]);
            } else {
                if (isset($cart[$id])) {
                    return response()->json([
                        'message' => trans('request.add_only_book'),
                    ]);
                } else {
                    $cart[$id] = [
                        'id' => $id,
                        'image' => $book->image,
                        'name' => $book->name,
                    ];
                    session()->put('cart', $cart);
                    session()->save();

                    return response()->json([
                        'message' => trans('request.add_cart'),
                    ]);
                }
            }
        }
    }

    public function removeCart($id)
    {
        $cart = session()->get('cart');
        unset($cart[$id]);
        session()->put('cart', $cart);
        session()->save();

        return response()->json([
            'message' => trans('request.remove_from_cart'),
        ]);
    }

    public function request(OrderRequest $request)
    {
        $cart = session()->get('cart');
        $user = Auth::user()->load([
            'requests.books',
            'requests' => function ($query) {
                $query->where('status', '<>', config('request.return'))->where('status', '<>', config('request.reject'))->withCount('books');
            },
        ]);

        if ($user->status == config('user.block')) {
            return redirect()->route('cart')->with('mess', trans('request.over_allow'));
        }

        $totalBook = config('request.pending');
        $borrowedBook = DB::table('book_request')
            ->whereIn('request_id', $user->requests()->pluck('id')->toArray())
            ->pluck('book_id')
            ->toArray();
        $bookInCart = array_keys($cart);
        $notBorrowedBooks = array_diff($borrowedBook, $bookInCart);
        $restOfBook = array_diff($borrowedBook, $notBorrowedBooks);

        if ($restOfBook == true) {
            return redirect()->route('cart')->with('mess', trans('request.borrowed'));
        }

        $totalBook = $user->requests->sum('books_count');

        if ($totalBook == config('request.max_book')) {
            return redirect()->route('cart')->with('mess', trans('request.fail_max_book'));
        }

        $borrowedDate = Carbon::parse($request->borrowed_date);
        $returnDate = Carbon::parse($request->return_date);
        $totalDate = $returnDate->diffinDays($borrowedDate);

        if ($totalDate > config('request.max_date')) {
            return redirect()->back()->withInput()->with('mess', trans('request.fail_max_date'));
        }

        $req = new Request;
        $order = $req->create([
            'user_id' => Auth::id(),
            'status' => config('request.pending'),
            'borrowed_date' => $request->borrowed_date,
            'return_date' => $request->return_date,
        ]);
        foreach ($cart as $item) {
            $book = Book::findOrFail($item['id']);
            $book->update([
                'in_stock' => $book->in_stock - config('request.book'),
            ]);
            $order->books()->attach($book);
        }
        session()->forget('cart');
        session()->save();

        return redirect()->route('cart')->with('mess', trans('request.success_mess'));
    }
}
