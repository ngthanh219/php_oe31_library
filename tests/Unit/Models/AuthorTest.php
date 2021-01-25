<?php

namespace Tests\Unit\Models;

use App\Models\Author;
use App\Models\Book;
use Tests\ModelTestCase;

class AuthorTest extends ModelTestCase
{
    protected $author;

    public function setUp(): void
    {
        parent::setUp();
        $this->author = new Author();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->author);
    }

    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Author(), [
            'image',
            'name',
            'date_of_born',
            'date_of_death',
            'description',
        ]);
    }

    public function test_books_relation()
    {
        $modelAuthor = new Author();
        $relation = $modelAuthor->books();

        $this->assertHasManyRelation($relation, $modelAuthor, new Book());
    }
}
