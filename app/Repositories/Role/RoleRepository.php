<?php

namespace App\Repositories\Role;

use App\Models\Role;
use App\Repositories\BaseRepository;
use DB;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function getModel()
    {
        return Role::class;
    }

    public function getRoleAdmins()
    {
        return DB::table('roles')
            ->select('id')
            ->whereIn('name', ['superadmin', 'admin'])
            ->get();
    }
}
