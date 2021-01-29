<?php

namespace App\Repositories\Like;

use App\Models\Like;
use App\Repositories\BaseRepository;

class LikeRepository extends BaseRepository implements LikeRepositoryInterface
{
    public function getModel()
    {
        return Like::class;
    }

    public function getLikeForUser($userId, $bookId)
    {
        return $this->model->where('user_id', $userId)->where('book_id', $bookId)->first();
    }

    public function countOfLikeInBook($userId, $bookId)
    {
        return $this->model->where('book_id', $bookId)->get()->count();
    }
}
