<?php

namespace App\Repositories\Rate;

use App\Models\Rate;
use App\Repositories\BaseRepository;

class RateRepository extends BaseRepository implements RateRepositoryInterface
{
    public function getModel()
    {
        return Rate::class;
    }

    public function getRateForUser($userId, $bookId)
    {
        return $this->model->where('user_id', $userId)->where('book_id', $bookId)->first();
    }
}
