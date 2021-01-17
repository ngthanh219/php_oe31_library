<?php

namespace Tests\Unit\Models;

use App\Models\Request;
use App\Models\User;
use App\Models\Permission;
use Tests\ModelTestCase;

class RequestTest extends ModelTestCase
{
    protected $relationequest;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new Request();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->request);
    }

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
        $modelRequest = new Request();
        $relation = $modelRequest->user();

        $this->assertBelongsToRelation($relation, $modelRequest, new User(), 'user_id');
    }

    public function test_books_relation()
    {
        $modelRequest = new Request();
        $relation = $modelRequest->books();

        $this->assertBelongsToManyRelation($relation, 'request_id', 'book_id');
    }
}
