<?php

namespace App\Repositories\Like;

use App\Repositories\RepositoryInterface;

interface LikeRepositoryInterface extends RepositoryInterface
{
    /**
     * Lấy ra người nào, quyển sách nào đang được like
     *
     * @param int $userId
     * @param int $bookId
     * @return void
     */
    public function getLikeForUser($userId, $bookId);

    /**
     * Đếm ra số like của của quyển sách
     *
     * @param int $userId
     * @param int $bookId
     * @return void
     */
    public function countOfLikeInBook($userId, $bookId);
}
