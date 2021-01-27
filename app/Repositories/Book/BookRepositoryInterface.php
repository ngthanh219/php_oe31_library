<?php

namespace App\Repositories\Book;

use App\Repositories\RepositoryInterface;

interface BookRepositoryInterface extends RepositoryInterface
{
    /**
     * Lấy ra danh sách hiển thị
     *
     * @return void
     */
    public function getVisibleBook();

    /**
     * Lấy ra chi tiết sách
     *
     * @param int $id
     * @param int $userId
     * @return void
     */
    public function getDetailBook($id, $userId);

    /**
     * Tìm kiếm sách hiển thị
     *
     * @param string $key
     * @return void
     */
    public function searchBookVisible($key);
}
