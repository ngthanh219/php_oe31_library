<?php

namespace Tests\Unit\Models;

use App\Models\Author;
use App\Models\Book;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Publisher;
use App\Models\Rate;
use Tests\ModelTestCase;

class BookTest extends ModelTestCase
{
    protected $book;

    public function setUp(): void
    {
        parent::setUp();
        $this->book = new Book();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->book);
    }

    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Book(), [
            'image',
            'name',
            'author_id',
            'publisher_id',
            'in_stock',
            'total',
            'status',
            'description',
        ]);
    }

    public function test_author_relation()
    {
        $modelBook = new Book();
        $relation = $modelBook->author();

        $this->assertBelongsToRelation($relation, $modelBook, new Author(), 'author_id');
    }

    public function test_publisher_relation()
    {
        $modelBook = new Book();
        $relation = $modelBook->publisher();

        $this->assertBelongsToRelation($relation, $modelBook, new Publisher(), 'publisher_id');
    }

    public function test_categories_relation()
    {
        $modelBook = new Book();
        $relation = $modelBook->categories();

        $this->assertBelongsToManyRelation($relation, null, 'category_id');
    }

    public function test_requests_relation()
    {
        $modelBook = new Book();
        $relation = $modelBook->requests();

        $this->assertBelongsToManyRelation($relation, null, 'request_id');
    }

    public function test_comments_relation()
    {
        $modelBook = new Book();
        $relation = $modelBook->comments();

        $this->assertHasManyRelation($relation, $modelBook, new Comment());
    }

    public function test_likes_relation()
    {
        $modelBook = new Book();
        $relation = $modelBook->likes();

        $this->assertHasManyRelation($relation, $modelBook, new Like());
    }

    public function test_rates_relation()
    {
        $modelBook = new Book();
        $relation = $modelBook->rates();

        $this->assertHasManyRelation($relation, $modelBook, new Rate());
    }
}
