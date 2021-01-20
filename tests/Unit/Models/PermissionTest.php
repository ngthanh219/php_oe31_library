<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use Tests\ModelTestCase;

class PermissionTest extends ModelTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->permission = new Permission();
    }

    public function tearDown(): void
    {
        unset($this->permission);
        parent::tearDown();
    }

    public function test_model_configuration()
    {
        $this->assertEquals([
            'name',
            'description',
        ], $this->permission->getFillable());
    }

    public function test_roles_relation()
    {
        $relation = $this->permission->roles();

        $this->assertBelongsToManyRelation($relation);
    }
}
