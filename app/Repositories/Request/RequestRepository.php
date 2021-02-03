<?php

namespace App\Repositories\Request;

use App\Models\Request;
use App\Repositories\BaseRepository;
use Auth;

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
}
