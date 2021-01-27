<?php

namespace Tests\Unit\Jobs;

use App\Jobs\ProcessCheckDate;
use App\Models\Book;
use App\Models\Request;
use App\Models\User;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Request\RequestRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Queue;
use Mockery as m;
use Tests\TestCase;

class ProcessCheckDateTest extends TestCase
{
    protected $request;
    protected $statusPending, $statusBorrow;
    protected $job;
    protected $bookRepo, $userRepo, $requestRepo;
    protected $bookId, $dataBook, $relation;
    protected $requestId, $dataRequest;
    protected $userId, $dataUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = factory(Request::class)->make();
        $this->book = factory(Book::class)->make();
        $this->user = factory(User::class)->make();
        $this->book->id = 1;
        $this->request->setRelation('books', [$this->book]);
        $this->user->id = 1;
        $this->request->setRelation('user', $this->user);
        $this->job = new ProcessCheckDate($this->request);
        $this->bookRepo = m::mock(BookRepositoryInterface::class)->makePartial();
        $this->requestRepo = m::mock(RequestRepositoryInterface::class)->makePartial();
        $this->userRepo = m::mock(UserRepositoryInterface::class)->makePartial();
        $this->statusPending = 0;
        $this->statusBorrow = 3;
        $this->requestId = 1;
        $this->relation = ['books'];
        $this->bookId = 1;
        $this->userId = 1;
        $this->dataBook = [
            'in_stock' => 10,
        ];
        $this->dataRequest = [
            'status' => 6,
        ];
        $this->dataUser = [
            'status' => 2,
        ];
    }

    public function tearDown(): void
    {
        m::close();

        unset($this->request);
        unset($this->job);
        unset($this->book);
        unset($this->bookRepo);
        unset($this->requestRepo);
        unset($this->userRepo);
        unset($this->statusPending);
        unset($this->statusBorrow);
        unset($this->requestId);
        unset($this->relation);
        unset($this->bookId);
        unset($this->dataBook);
        unset($this->dataRequest);
        unset($this->userId);
        unset($this->dataUser);
        unset($this->user);

        parent::tearDown();
    }

    public function test_handle_check_date_with_status_pending()
    {
        $this->request->status = $this->statusPending;
        
        $this->requestRepo->shouldReceive('withFind')
            ->withAnyArgs($this->requestId, $this->relation)
            ->once()
            ->andReturn($this->request);

        $this->bookRepo->shouldReceive('update')
            ->withAnyArgs($this->bookId, $this->dataBook)
            ->once()
            ->andReturn(true);

        $view = $this->requestRepo->shouldReceive('update')
            ->withAnyArgs($this->requestId, $this->dataRequest)
            ->once()
            ->andReturn(true);

        $response = $this->job->handle($this->requestRepo, $this->bookRepo, $this->userRepo);

        $this->assertTrue($response);
    }

    public function test_handle_check_date_without_status_pending_accept()
    {
        $this->request->status = $this->statusBorrow;

        $this->userRepo->shouldReceive('update')
            ->withAnyArgs($this->userId, $this->dataUser)
            ->once()
            ->andReturn(true);

        $this->requestRepo->shouldReceive('update')
            ->withAnyArgs($this->requestId, $this->dataRequest)
            ->once()
            ->andReturn(true);

        $response = $this->job->handle($this->requestRepo, $this->bookRepo, $this->userRepo);

        $this->assertTrue($response);
    }
}
