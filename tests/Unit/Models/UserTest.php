<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Tests\ModelTestCase;

class UserTest extends ModelTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->role = new Role();
        $this->permission = new Permission();
    }

    public function tearDown(): void
    {
        unset($this->user);
        parent::tearDown();
    }

    public function test_model_configuration()
    {
        $this->assertEquals([
            'name',
            'email',
            'password',
            'address',
            'phone',
            'role_id',
            'times',
            'status',
        ], $this->user->getFillable());
    }

    public function test_requests_relation()
    {
        $relation = $this->user->requests();

        $this->assertHasManyRelation($relation, $this->user, $this->user, 'user_id');
    }

    public function test_comments_relation()
    {
        $relation = $this->user->comments();

        $this->assertHasManyRelation($relation, $this->user, $this->user, 'user_id');
    }

    public function test_likes_relation()
    {
        $relation = $this->user->likes();

        $this->assertHasManyRelation($relation, $this->user, $this->user, 'user_id');
    }

    public function test_rates_relation()
    {
        $relation = $this->user->rates();

        $this->assertHasManyRelation($relation, $this->user, $this->user, 'user_id');
    }

    public function test_role_relation()
    {
        $relation = $this->user->role();

        $this->assertBelongsToRelation($relation, $this->user, $this->user, 'role_id');
    }

    public function test_hasPermission()
    {
        $result = $this->user->hasPermission($this->permission);

        $this->assertEquals(false, $result);
    }
}
