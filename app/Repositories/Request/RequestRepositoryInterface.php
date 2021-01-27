<?php

namespace App\Repositories\Request;

use App\Repositories\RepositoryInterface;

interface RequestRepositoryInterface extends RepositoryInterface
{
    /**
     * Láy ra danh sách yêu cầu mượn sách
     *
     * @return void
     */
    public function getRequest();

    /**
     * Lấy ra các "request" của user
     *
     * @return void
     */
    public function getUserRequest();

    /**
     * Lấy ra tổng số sách đã mượn
     *
     * @param collection $relation
     * @return void
     */
    public function getTotalBook($relation);

    /**
     * Biểu đồ hiển thị số lượng sách đang mượn theo từng tháng
     *
     * @return Collection
     */
    public function chart();
}
