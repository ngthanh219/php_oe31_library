<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Auth;
use DB;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function getRole()
    {
        return User::with('role')->orderBy('id', 'desc')->paginate(config('pagination.limit'));
    }

    public function getEmailOfUser($email)
    {
        return User::where('email', $email)->first();
    }

    public function search($key)
    {
        return User::where('name', 'LIKE', '%' . $key . '%')->orderBy('id', 'DESC')->get();
    }
    
    public function getRequest()
    {
        $status = [
            config('request.return'),
            config('request.reject'),
            config('request.forget'),
        ];

        return Auth::user()->load([
            'requests.books',
            'requests' => function ($query) use ($status) {
                $query->whereNotIn('status', $status)->withCount('books');
            },
        ]);
    }

    public function checkRequest($id = [])
    {
        return DB::table('book_request')
            ->whereIn('request_id', $id)
            ->pluck('book_id')
            ->toArray();
    }
}
