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

     /**
     * Lấy danh sachs request của user
     *
     * @return void
     */
    public function getRequest();

    /**
     * Lấy ra id của các quyển sách đã mượn
     *
     * @param array $id là danh sách id của "book"
     * @return void
     */
    public function checkRequest($id = []);
}
