<?php

namespace Tests\Unit\Models;

use App\Models\Book;
use App\Models\Publisher;
use Tests\ModelTestCase;

class PublisherTest extends ModelTestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Publisher(), [
            'image',
            'name',
            'email',
            'address',
            'phone',
            'description',
        ]);
    }

    public function test_books_relation()
    {
        $m = new Publisher();
        $r = $m->books();

        $this->assertHasManyRelation($r, $m, new Book());
    }
}
