<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use Tests\ModelTestCase;

class CategoryTest extends ModelTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->category = new Category();
    }

    public function tearDown(): void
    {
        unset($this->category);
        parent::tearDown();
    }

    public function test_model_configuration()
    {
        $this->assertEquals([
            'name',
            'parent_id',
        ], $this->category->getFillable());
    }

    public function test_books_relation()
    {
        $relation = $this->category->books();

        $this->assertBelongsToManyRelation($relation);
    }

    public function test_parent_relation()
    {
        $relation = $this->category->parent();

        $this->assertBelongsToRelation($relation, $this->category, $this->category, 'parent_id', 'id');
    }

    public function test_children_relation()
    {
        $relation = $this->category->children();

        $this->assertHasManyRelation($relation, $this->category, $this->category, 'parent_id', 'id');
    }
}
