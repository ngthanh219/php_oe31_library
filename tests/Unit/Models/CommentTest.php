<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use Tests\ModelTestCase;

class CommentTest extends ModelTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->comment = new Comment();
    }

    public function tearDown(): void
    {
        unset($this->comment);
        parent::tearDown();
    }

    public function test_model_configuration()
    {
        $this->assertEquals([
            'image',
            'name',
            'comment',
            'user_id',
            'book_id',
        ], $this->comment->getFillable());
    }

    public function test_user_relation()
    {
        $relation = $this->comment->user();

        $this->assertBelongsToRelation($relation, $this->comment, $this->comment, 'user_id');
    }

    public function test_book_relation()
    {
        $relation = $this->comment->book();

        $this->assertBelongsToRelation($relation, $this->comment, $this->comment, 'book_id');
    }
}
