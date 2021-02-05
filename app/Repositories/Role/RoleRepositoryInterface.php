<?php

namespace App\Repositories\Role;

use App\Repositories\RepositoryInterface;

interface RoleRepositoryInterface extends RepositoryInterface
{
    /**
     * getRoleAdmins
     * Function này em sử dụng query builder để lấy ra các role có quyền là admin và superadmin
     * @return void
     */
    public function getRoleAdmins();
}
