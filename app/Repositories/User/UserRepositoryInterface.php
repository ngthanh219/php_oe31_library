<?php

namespace App\Repositories\User;

use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    /**
     * getRole
     * Lấy ra quan hệ của user với role
     * @return void
     */
    public function getRole();

    /**
     * getEmailOfUser
     * Kiểm tra email có tồn tại không với tham số truyền vào là 1 email
     * @param string $email
     * @return void
     */
    public function getEmailOfUser($email);

    /**
     * search
     * Search user với tham số truyền vào là 1 họ tên
     * @param string $key
     * @return void
     */
    public function search($key);
}
