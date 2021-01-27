<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Request\RequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Auth;

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
        if ($request->status !== config('request.pending') && $request->status !== config('request.reject')) {
            abort(Response::HTTP_NOT_FOUND);
        }

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

    public function reject($id)
    {
        $request = $this->requestRepo->find($id);
        if ($request->status !== config('request.pending') && $request->status !== config('request.accept')) {
            abort(Response::HTTP_NOT_FOUND);
        }

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

    public function undo($id)
    {
        $request = $this->requestRepo->find($id);

        switch ($request->status) {
            case config('request.accept'):
                $result = $this->requestRepo->update($id, [
                    'status' => config('request.pending'),
                ]);

                break;
            case config('request.reject'):
                foreach ($request->books as $book) {
                    $this->bookRepo->update($book->id, [
                        'in_stock' => $book->in_stock - config('request.book'),
                    ]);
                }
                $result = $this->requestRepo->update($id, [
                    'status' => config('request.pending'),
                ]);

                break;
            case config('request.borrow'):
                $result = $this->requestRepo->update($id, [
                    'status' => config('request.accept'),
                ]);

                break;
            case config('request.return'):
                foreach ($request->books as $book) {
                    $this->bookRepo->update($book->id, [
                        'in_stock' => $book->in_stock - config('request.book'),
                    ]);
                }
                $result = $this->requestRepo->update($id, [
                    'status' => config('request.borrow'),
                ]);

                break;
            default:
                abort(Response::HTTP_NOT_FOUND);
        }

        if ($result) {
            return redirect()->back()->with('infoMessage',
                trans('message.request_undo_success'));
        }

        return redirect()->back()->with('infoMessage',
            trans('message.request_undo_fail'));
    }

    public function borrowedBook($id)
    {
        $request = $this->requestRepo->find($id);
        if ($request->status !== config('request.accept')) {
            abort(Response::HTTP_NOT_FOUND);
        }

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

    public function returnBook($id)
    {
        $request = $this->requestRepo->find($id);
        if ($request->status !== config('request.borrow')) {
            abort(Response::HTTP_NOT_FOUND);
        }

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
}
