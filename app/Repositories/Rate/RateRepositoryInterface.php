<?php

namespace App\Repositories\Rate;

use App\Repositories\RepositoryInterface;

interface RateRepositoryInterface extends RepositoryInterface
{
    /**
     * Lấy ra người nào, quyển sách nào đang được Rate
     *
     * @param int $userId
     * @param int $bookId
     * @return void
     */
    public function getRateForUser($userId, $bookId);
}
