<?php

namespace Tests\Unit\Models;

use App\Models\Book;
use App\Models\Like;
use App\Models\User;
use Tests\ModelTestCase;

class LikeTest extends ModelTestCase
{
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
        $m = new Like();
        $r = $m->user();

        $this->assertBelongsToRelation($r, $m, new User(), 'user_id');
    }

    public function test_book_relation()
    {
        $m = new Like();
        $r = $m->book();

        $this->assertBelongsToRelation($r, $m, new User(), 'book_id');
    }
}
