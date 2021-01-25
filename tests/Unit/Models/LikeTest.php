<?php

namespace Tests\Unit\Models;

use App\Models\Book;
use App\Models\Like;
use App\Models\User;
use Tests\ModelTestCase;

class LikeTest extends ModelTestCase
{
    protected $like;

    public function setUp(): void
    {
        parent::setUp();
        $this->like = new Like();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->like);
    }

    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Like(), [
            'status',
            'user_id',
            'book_id',
        ]);
    }

    public function test_user_relation()
    {
        $modelLike = new Like();
        $relation = $modelLike->user();

        $this->assertBelongsToRelation($relation, $modelLike, new User(), 'user_id');
    }

    public function test_book_relation()
    {
        $modelLike = new Like();
        $relation = $modelLike->book();

        $this->assertBelongsToRelation($relation, $modelLike, new User(), 'book_id');
    }
}
