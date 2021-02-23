<?php

namespace App\Repositories\Request;

use App\Models\Request;
use App\Repositories\BaseRepository;
use Auth;
use DB;

class RequestRepository extends BaseRepository implements RequestRepositoryInterface
{
    public function getModel()
    {
        return Request::class;
    }

    public function getRequest()
    {
        return $this->model->with('user')->orderBy('id', 'DESC')->paginate(config('pagination.list_request'));
    }

    public function getUserRequest()
    {
        return Auth::user()->requests()->paginate(config('pagination.list_request'));
    }

    public function getTotalBook($relation)
    {
        return $relation->sum('books_count');
    }

    public function chart()
    {
        return DB::table('requests')
            ->select(DB::raw('month(borrowed_date) as month'), DB::raw('count(book_request.id) as book'))
            ->join('book_request', 'requests.id', '=', 'book_request.request_id')
            ->whereNotIn('requests.status', ['0', '2'])
            ->groupBy(DB::raw('month(requests.borrowed_date)'))
            ->get();
    }
}
