<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

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
}
