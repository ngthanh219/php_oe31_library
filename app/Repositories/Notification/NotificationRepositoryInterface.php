<?php

namespace App\Repositories\Notification;

use App\Repositories\RepositoryInterface;

interface NotificationRepositoryInterface extends RepositoryInterface
{
    /**
     * getNotificationByDB
     * Function này em sử dụng query builder để lấy ra dữ liệu
     * @return void
     */
    public function getNotificationByDB();
}
