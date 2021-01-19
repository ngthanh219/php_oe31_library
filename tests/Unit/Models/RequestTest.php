<?php

namespace Tests\Unit\Models;

use App\Models\Request;
use App\Models\User;
use App\Models\Permission;
use Tests\ModelTestCase;

class RequestTest extends ModelTestCase
{
    public function test_model_configuration()
    {
        $this->runConfigurationAssertions(new Request(), [
            'note',
            'borrowed_date',
            'return_date',
            'user_id',
            'status',
        ]);
    }

    public function test_user_relation()
    {
        $m = new Request();
        $r = $m->user();

        $this->assertBelongsToRelation($r, $m, new User(), 'user_id');
    }

    public function test_books_relation()
    {
        $m = new Request();
        $r = $m->books();

        $this->assertBelongsToManyRelation($r, 'request_id', 'book_id');
    }
}
