<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\CommentController;
use App\Repositories\Comment\CommentRepositoryInterface;
use Mockery;
use Tests\TestCase;
use App\Http\Requests\CommentRequest;
use App\Models\User;

class CommentControllerTest extends TestCase
{
    protected $cmtRepo, $cmtControllerTest;

    public function setUp(): void
    {
        parent::setUp();
        $this->cmtRepo = Mockery::mock(CommentRepositoryInterface::class)->makePartial();
        $this->cmtControllerTest = new CommentController($this->cmtRepo);
        $this->userAuthorized = factory(User::class)->make();
    }

    public function tearDown(): void
    {
        Mockery::close();
        unset($this->cmtRepo);
        unset($this->cmtController);
        parent::tearDown();
    }

    public function test_store_method()
    {
        $this->be($this->userAuthorized);
        $this->userAuthorized['id'] = 5;
        $data = [
            'user_id' => $this->userAuthorized['id'],
            'book_id' => 26,
            'comment' => 'Day la binh luan',
        ];
        $resClient = [
            'user_name' => $this->userAuthorized['name'],
            'comment' => $data['comment'],
            'status' => 'comment.success',
        ];
        $request = new CommentRequest($data);
        $this->cmtRepo->shouldReceive('create')
            ->once()
            ->andReturn($data);
        $response = $this->cmtControllerTest->store($request);
        $this->assertJson(json_encode($resClient), json_encode($response->getData()));
    }
}
