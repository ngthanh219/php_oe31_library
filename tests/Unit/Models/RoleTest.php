<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Tests\ModelTestCase;

class RoleTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Role(), [
            'name',
        ]);
    }

    public function test_users_relation()
    {
        $m = new Role();
        $r = $m->users();

        $this->assertHasManyRelation($r, $m, new User());
    }

    public function test_permissions_relation()
    {
        $m = new Role();
        $r = $m->permissions();

        $this->assertBelongsToManyRelation($r, 'role_id', 'per_id');
    }
}
