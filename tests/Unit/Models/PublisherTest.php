<?php

namespace Tests\Unit\Models;

use App\Models\Book;
use App\Models\Publisher;
use Tests\ModelTestCase;

class PublisherTest extends ModelTestCase
{
    protected $publisher;

    public function setUp(): void
    {
        parent::setUp();
        $this->publisher = new Publisher();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->publisher);
    }

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
        $modelPublisher = new Publisher();
        $relation = $modelPublisher->books();

        $this->assertHasManyRelation($relation, $modelPublisher, new Book());
    }
}
