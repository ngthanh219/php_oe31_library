<?php

namespace Tests\Unit\Models;

use App\Models\Rate;
use Tests\ModelTestCase;

class RateTest extends ModelTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->rate = new Rate();
    }

    public function tearDown(): void
    {
        unset($this->rate);
        parent::tearDown();
    }

    public function test_model_configuration()
    {
        $this->assertEquals([
            'vote',
            'user_id',
            'book_id',
        ], $this->rate->getFillable());
    }

    public function test_user_relation()
    {
        $relation = $this->rate->user();

        $this->assertBelongsToRelation($relation, $this->rate, $this->rate, 'user_id');
    }

    public function test_book_relation()
    {
        $relation = $this->rate->book();

        $this->assertBelongsToRelation($relation, $this->rate, $this->rate, 'book_id');
    }
}
