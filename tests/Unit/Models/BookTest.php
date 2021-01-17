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
        $m = new Book();
        $r = $m->author();

        $this->assertBelongsToRelation($r, $m, new Author(), 'author_id');
    }

    public function test_publisher_relation()
    {
        $m = new Book();
        $r = $m->publisher();

        $this->assertBelongsToRelation($r, $m, new Publisher(), 'publisher_id');
    }

    public function test_categories_relation()
    {
        $m = new Book();
        $r = $m->categories();

        $this->assertBelongsToManyRelation($r, null, 'category_id');
    }

    public function test_requests_relation()
    {
        $m = new Book();
        $r = $m->requests();

        $this->assertBelongsToManyRelation($r, null, 'request_id');
    }

    public function test_comments_relation()
    {
        $m = new Book();
        $r = $m->comments();

        $this->assertHasManyRelation($r, $m, new Comment());
    }

    public function test_likes_relation()
    {
        $m = new Book();
        $r = $m->likes();

        $this->assertHasManyRelation($r, $m, new Like());
    }

    public function test_rates_relation()
    {
        $m = new Book();
        $r = $m->rates();

        $this->assertHasManyRelation($r, $m, new Rate());
    }
}
