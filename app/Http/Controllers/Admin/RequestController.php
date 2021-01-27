<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Request\RequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Response;

class RequestController extends Controller
{
    protected $requestRepo, $bookRepo;

    public function __construct(
        RequestRepositoryInterface $requestRepo,
        BookRepositoryInterface $bookRepo
    ) {
        $this->requestRepo = $requestRepo;
        $this->bookRepo = $bookRepo;
    }

    public function index()
    {
        $requests = $this->requestRepo->getRequest();

        return view('admin.request.index', compact('requests'));
    }

    public function show($id)
    {
        $request = $this->requestRepo->withFind($id, 'books.author', 'books.categories', 'user');
        $borrowedDate = Carbon::parse($request->borrowed_date);
        $returnDate = Carbon::parse($request->return_date);
        $totalDate = $returnDate->diffinDays($borrowedDate);

        return view('admin.request.show', compact('request', 'totalDate'));
    }

    public function accept($id)
    {
        $request = $this->requestRepo->withFind($id, ['books']);

        if ($request->status === config('request.pending') || $request->status === config('request.reject')) {
            $result = $this->requestRepo->update($id, [
                'status' => config('request.accept'),
            ]);
            if ($result) {
                return redirect()->back()->with('infoMessage',
                    trans('message.request_accept_success'));
            }

            return redirect()->back()->with('infoMessage',
                trans('message.request_accept_fail'));
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function reject($id)
    {
        $request = $this->requestRepo->find($id);
        if ($request->status === config('request.pending') || $request->status === config('request.accept')) {
            foreach ($request->books as $book) {
                $this->bookRepo->update($book->id, [
                    'in_stock' => $book->in_stock + config('request.book'),
                ]);
            }
            $result = $this->requestRepo->update($id, [
                'status' => config('request.reject'),
            ]);
            if ($result) {
                return redirect()->back()->with('infoMessage',
                    trans('message.request_reject_success'));
            }

            return redirect()->back()->with('infoMessage',
                trans('message.request_reject_fail'));
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function undo($id)
    {
        $request = $this->requestRepo->find($id);
        if ($request->status === config('request.accept') || $request->status === config('request.reject') || $request->status === config('request.borrow') || $request->status === config('request.return')) {
            if ($request->status === config('request.accept')) {
                $result = $this->requestRepo->update($id, [
                    'status' => config('request.pending'),
                ]);
            } elseif ($request->status === config('request.reject')) {
                foreach ($request->books as $book) {
                    $this->bookRepo->update($book->id, [
                        'in_stock' => $book->in_stock - config('request.book'),
                    ]);
                }
                $result = $this->requestRepo->update($id, [
                    'status' => config('request.pending'),
                ]);
            } elseif ($request->status === config('request.borrow')) {
                $result = $this->requestRepo->update($id, [
                    'status' => config('request.accept'),
                ]);
            } elseif ($request->status === config('request.return')) {
                foreach ($request->books as $book) {
                    $this->bookRepo->update($book->id, [
                        'in_stock' => $book->in_stock - config('request.book'),
                    ]);
                }
                $result = $this->requestRepo->update($id, [
                    'status' => config('request.borrow'),
                ]);
            }
            if ($result) {
                return redirect()->back()->with('infoMessage',
                    trans('message.request_undo_success'));
            }

            return redirect()->back()->with('infoMessage',
                trans('message.request_undo_fail'));
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function borrowedBook($id)
    {
        $request = Request::findOrFail($id);
        if ($request->status === config('request.accept')) {
            $result = $this->requestRepo->update($id, [
                'status' => config('request.borrow'),
            ]);
            if ($result) {
                return redirect()->back()->with('infoMessage',
                    trans('message.request_reject_success'));
            }

            return redirect()->back()->with('infoMessage',
                trans('message.request_reject_fail'));
        }

        abort(Response::HTTP_NOT_FOUND);
    }

    public function returnBook($id)
    {
        $request = Request::findOrFail($id);
        if ($request->status === config('request.borrow')) {
            foreach ($request->books as $book) {
                $this->bookRepo->update($book->id, [
                    'in_stock' => $book->in_stock + config('request.book'),
                ]);
            }
            $result = $this->requestRepo->update($id, [
                'status' => config('request.return'),
            ]);
            if ($result) {
                return redirect()->back()->with('infoMessage',
                    trans('message.request_return_success'));
            }

            return redirect()->back()->with('infoMessage',
                trans('message.request_return_fail'));
        }

        abort(Response::HTTP_NOT_FOUND);
    }
}
