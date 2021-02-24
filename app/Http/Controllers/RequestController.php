<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Http\Requests\OrderRequest;
use App\Models\Request;
use App\Repositories\Author\AuthorRepositoryInterface;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Publisher\PublisherRepositoryInterface;
use App\Repositories\Request\RequestRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\Admin\RequestNotification;
use DB;
use Illuminate\Support\Facades\Notification;
use Pusher\Pusher;

class RequestController extends Controller
{
    protected $requestRepo, $userRepo, $authorRepo, $bookRepo, $categoryRepo, $publisherRepo, $roleRepo;

    public function __construct(
        RequestRepositoryInterface $requestRepo,
        BookRepositoryInterface $bookRepo,
        CategoryRepositoryInterface $categoryRepo,
        PublisherRepositoryInterface $publisherRepo,
        AuthorRepositoryInterface $authorRepo,
        UserRepositoryInterface $userRepo,
        RoleRepositoryInterface $roleRepo
    ) {
        $this->requestRepo = $requestRepo;
        $this->bookRepo = $bookRepo;
        $this->categoryRepo = $categoryRepo;
        $this->publisherRepo = $publisherRepo;
        $this->authorRepo = $authorRepo;
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;
    }

    public function index()
    {
        $requests = $this->requestRepo->getUserRequest();

        return view('client.list_request', compact('requests'));
    }

    public function show($id)
    {
        $request = $this->requestRepo->withFind($id, ['user']);

        return view('client.detail_request', compact('request'));
    }

    public function cart()
    {
        $cart = session()->get('cart');

        return view('client.cart', compact('cart'));
    }

    public function addToCart($id)
    {
        $cart = session()->get('cart');
        $book = $this->bookRepo->find($id);

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
        $user = $this->userRepo->getRequest();
        $idBook = [];

        if ($user->status == config('user.block')) {
            return redirect()->route('cart')->with('mess', trans('request.over_allow'));
        }

        foreach ($user->requests as $req) {
            array_push($idBook, $req->id);
        }

        $totalBook = config('request.pending');
        $borrowedBook = $this->userRepo->checkRequest($idBook);
        $bookInCart = array_keys($cart);
        $notBorrowedBooks = array_diff($borrowedBook, $bookInCart);
        $restOfBook = array_diff($borrowedBook, $notBorrowedBooks);

        if ($restOfBook == true) {
            return redirect()->route('cart')->with('mess', trans('request.borrowed'));
        }

        $totalBook = $this->requestRepo->getTotalBook($user->requests);

        if ($totalBook == config('request.max_book')) {
            return redirect()->route('cart')->with('mess', trans('request.fail_max_book'));
        }

        $borrowedDate = Carbon::parse($request->borrowed_date);
        $returnDate = Carbon::parse($request->return_date);
        $totalDate = $returnDate->diffinDays($borrowedDate);

        if ($totalDate > config('request.max_date')) {
            return redirect()->back()->withInput()->with('mess', trans('request.fail_max_date'));
        }

        $order = $this->requestRepo->create([
            'user_id' => Auth::id(),
            'status' => config('request.pending'),
            'borrowed_date' => $request->borrowed_date,
            'return_date' => $request->return_date,
        ]);

        $roles = $this->roleRepo->getRoleAdmins();
        $roleList = $roles->pluck('id');

        $users = $this->userRepo->getUserHaveRoleAdmins($roleList);
        $userId = $users->pluck('id');

        $noti = [
            'request_id' => $order->id,
            'nameUser' => Auth::user()->name,
            'content' => trans('request.info_request'),
            'users' => $userId,
        ];

        Notification::send($users, new RequestNotification($noti));
        event(new NotificationEvent($noti));

        foreach ($cart as $item) {
            $book = $this->bookRepo->find($item['id']);
            $this->bookRepo->update($item['id'], [
                'in_stock' => $book->in_stock - config('request.book'),
            ]);
            $this->requestRepo->attach($order, 'books', $book);
        }

        session()->forget('cart');
        session()->save();

        return redirect()->route('cart')->with('mess', trans('request.success_mess'));
    }
}
