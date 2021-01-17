<?php

namespace Tests\Unit\Models;

use App\Models\Author;
use App\Models\Book;
use Tests\ModelTestCase;

class AuthorTest extends ModelTestCase
{
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
        $m = new Author();
        $r = $m->books();

        $this->assertHasManyRelation($r, $m, new Book());
    }
}
