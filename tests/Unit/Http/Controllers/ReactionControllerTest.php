<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\ReactionController;
use App\Models\Like;
use App\Models\Rate;
use App\Models\User;
use App\Repositories\Like\LikeRepositoryInterface;
use App\Repositories\Rate\RateRepositoryInterface;
use Illuminate\Http\Request;
use Mockery as m;
use Tests\TestCase;

class ReactionControllerTest extends TestCase
{
    protected $likeRepo, $rateRepo;
    protected $reactionController;
    protected $use, $likes, $rate;
    protected $userId, $bookId;
    protected $countLike, $dataLike, $likeDataJson;
    protected $dataRate, $mess;
    protected $langTrue, $langFalse;

    public function setUp(): void
    {
        parent::setUp();

        $this->likeRepo = m::mock(LikeRepositoryInterface::class)->makePartial();
        $this->rateRepo = m::mock(RateRepositoryInterface::class)->makePartial();
        $this->reactionController = new ReactionController($this->likeRepo, $this->rateRepo);
        $this->user = factory(User::class)->make();
        $this->likes = factory(Like::class)->make();
        $this->rate = factory(Rate::class)->make();
        $this->bookId = 1;
        $this->userId = 1;
        $this->countLike = 1;
        $this->dataLike = ['user_id' => $this->userId, 'book_id' => $this->bookId, 'status' => 1];
        $this->likeDataJson = ['count' => $this->countLike, 'like' => true];
        $this->dataRate = [
            'user_id' => 1,
            'book_id' => 1,
        ];
        $this->mess = json_encode(['message' => trans('rate.voted_success')]);
        $this->langTrue = ['language' => 'vi'];
        $this->langFalse = ['language' => 'ez'];
    }

    public function tearDown(): void
    {
        unset($this->likeRepo);
        unset($this->rateRepo);
        unset($this->reactionController);
        unset($this->user);
        unset($this->likes);
        unset($this->rate);
        unset($this->bookId);
        unset($this->userId);
        unset($this->countLike);
        unset($this->dataLike);
        unset($this->likeDataJson);
        unset($this->dataRate);
        unset($this->mess);
        unset($this->langTrue);
        unset($this->langFalse);
        
        m::close();
        parent::tearDown();
    }

    public function test_react_like_not_exist_method()
    {
        $this->be($this->user);

        $this->likeRepo->shouldReceive('getLikeForUser')
            ->withAnyArgs($this->userId, $this->bookId)
            ->once()
            ->andReturn(null);

        $this->likeRepo->shouldReceive('countOfLikeInBook')
            ->withAnyArgs($this->userId, $this->bookId)
            ->once()
            ->andReturn($this->countLike);

        $this->likeRepo->shouldReceive('create')
            ->withAnyArgs($this->dataLike)
            ->once()
            ->andReturn(true);

        $response = $this->reactionController->react($this->bookId);

        $this->assertJson(json_encode($this->likeDataJson), json_encode($response->getData()));
    }

    public function test_react_unlike()
    {
        $this->be($this->user);

        $this->likeRepo->shouldReceive('getLikeForUser')
            ->withAnyArgs($this->userId, $this->bookId)
            ->once()
            ->andReturn($this->likes);

        $this->likeRepo->shouldReceive('countOfLikeInBook')
            ->withAnyArgs($this->userId, $this->bookId)
            ->once()
            ->andReturn($this->countLike);

        $response = $this->reactionController->react($this->bookId);

        $this->assertJson(json_encode($this->likeDataJson), json_encode($response->getData()));
    }

    public function test_react_like_exist_but_null_method()
    {
        $this->likes->status = null;

        $this->be($this->user);

        $this->likeRepo->shouldReceive('getLikeForUser')
            ->withAnyArgs($this->userId, $this->bookId)
            ->once()
            ->andReturn($this->likes);

        $this->likeRepo->shouldReceive('countOfLikeInBook')
            ->withAnyArgs($this->userId, $this->bookId)
            ->once()
            ->andReturn($this->countLike);

        $response = $this->reactionController->react($this->bookId);

        $this->assertJson(json_encode($this->likeDataJson), json_encode($response->getData()));
    }

    public function test_react_vote_not_exist_method()
    {
        $this->be($this->user);

        $this->rateRepo->shouldReceive('getRateForUser')
            ->withAnyArgs($this->dataRate['user_id'], $this->dataRate['book_id'])
            ->once()
            ->andReturn(null);

        $this->rateRepo->shouldReceive('create')
            ->withAnyArgs($this->dataRate)
            ->once()
            ->andReturn(true);

        $request = new Request($this->dataRate);
        $response = $this->reactionController->vote($request);

        $this->assertJson($this->mess, json_encode($response->getData()));
    }

    public function test_react_vote_exist_method()
    {
        $this->be($this->user);

        $this->rateRepo->shouldReceive('getRateForUser')
            ->withAnyArgs($this->dataRate['user_id'], $this->dataRate['book_id'])
            ->once()
            ->andReturn($this->rate);

        $this->rateRepo->shouldReceive('update')
            ->withAnyArgs($this->dataRate)
            ->once()
            ->andReturn(true);

        $request = new Request($this->dataRate);
        $response = $this->reactionController->vote($request);

        $this->assertJson($this->mess, json_encode($response->getData()));
    }

    public function test_change_language_method()
    {
        $request = new Request($this->langTrue);
        $view = $this->reactionController->changeLanguage($request)->withSession($this->langTrue);

        $this->assertEquals('http://127.0.0.1:8000', $view->getTargetUrl());
    }

    public function test_change_language_not_exist()
    {
        $request = new Request($this->langFalse);
        $view = $this->reactionController->changeLanguage($request)->withSession($this->langFalse);

        $this->assertEquals('http://127.0.0.1:8000', $view->getTargetUrl());
    }
}
