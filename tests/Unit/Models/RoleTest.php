<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Tests\ModelTestCase;

class RoleTest extends ModelTestCase
{
    protected $relationole;

    public function setUp(): void
    {
        parent::setUp();
        $this->role = new Role();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->role);
    }

    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Role(), [
            'name',
        ]);
    }

    public function test_users_relation()
    {
        $modelRole = new Role();
        $relation = $modelRole->users();

        $this->assertHasManyRelation($relation, $modelRole, new User());
    }

    public function test_permissions_relation()
    {
        $modelRole = new Role();
        $relation = $modelRole->permissions();

        $this->assertBelongsToManyRelation($relation, 'role_id', 'per_id');
    }
}
