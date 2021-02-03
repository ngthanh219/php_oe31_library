<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Http\Controllers\Admin\RequestController;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Request;
use App\Models\User;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Request\RequestRepositoryInterface;
use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class RequestControllerTest extends TestCase
{
    protected $requestRepo;
    protected $bookRepo;
    protected $requestControllerTest;
    protected $user;
    protected $book;
    protected $author;
    protected $categories;
    protected $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->requestRepo = Mockery::mock(RequestRepositoryInterface::class)->makePartial();
        $this->bookRepo = Mockery::mock(BookRepositoryInterface::class)->makePartial();
        $this->requestControllerTest = new RequestController(
            $this->requestRepo,
            $this->bookRepo
        );
        $this->user = factory(User::class)->make();
        $this->book = factory(Book::class)->make();
        $this->author = factory(Author::class)->make();
        $this->category = factory(Category::class)->make();
        $this->request = factory(Request::class)->make();
    }

    public function tearDown(): void
    {
        Mockery::close();
        unset($this->requestRepo);
        unset($this->bookRepo);
        unset($this->requestControllerTest);
        unset($this->user);
        unset($this->book);
        unset($this->author);
        unset($this->category);
        unset($this->request);
        parent::tearDown();
    }

    public function test_index_method()
    {
        $this->requestRepo->shouldReceive('getRequest');
        $response = $this->requestControllerTest->index();

        $this->assertEquals('admin.request.index', $response->getName());
        $this->assertArrayHasKey('requests', $response->getData());
    }

    public function test_show_method()
    {
        $this->request->id = 1;
        $id = $this->request->id;

        $this->book->setRelation('author', $this->author);
        $this->book->setRelation('categories', $this->category);
        $this->request->setRelation('books', $this->book);
        $this->request->setRelation('user', $this->user);
        $this->requestRepo->shouldReceive('withFind')
            ->once()
            ->andReturn($this->request);

        $response = $this->requestControllerTest->show($this->request);
        $this->assertEquals('admin.request.show', $response->getName());
    }

    public function test_accept_method()
    {
        $this->request->id = 1;
        $id = $this->request->id;
        $status = 1;

        $this->request->setRelation('books', $this->book);
        $this->requestRepo->shouldReceive('withFind')
            ->once()
            ->andReturn($this->request);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn($id, $status);

        $response = $this->requestControllerTest->accept($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_accept_method_fail()
    {
        $this->request->id = 1;
        $id = $this->request->id;

        $this->request->setRelation('books', $this->book);
        $this->requestRepo->shouldReceive('withFind')
            ->once()
            ->andReturn($this->request);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $response = $this->requestControllerTest->accept($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_accept_method_exception()
    {
        $this->request->id = 1;
        $id = $this->request->id;
        $this->request->status = 1;

        $this->request->setRelation('books', $this->book);
        $this->expectException(HttpException::class);
        $this->requestRepo->shouldReceive('withFind')
            ->once()
            ->andReturn($this->request);

        $response = $this->requestControllerTest->accept($this->request);
        $this->assertEquals(false, $response);
    }

    public function test_reject_method()
    {
        $idBook = $this->book->id = 10;
        $this->book->in_stock += 1;
        $idRequest = $this->request->id = 1;
        $status = 2;
        $book = collect();
        $book->push($this->book);

        $this->request->setRelation('books', $book);
        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->bookRepo->shouldReceive('update')
            ->once()
            ->andReturn($idBook, $this->book);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn($idRequest, $status);

        $response = $this->requestControllerTest->reject($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_reject_method_fail()
    {
        $idBook = $this->book->id = 10;
        $this->book->in_stock += 1;
        $idRequest = $this->request->id = 1;
        $status = 2;
        $book = collect();
        $book->push($this->book);

        $this->request->setRelation('books', $book);
        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->bookRepo->shouldReceive('update')
            ->once()
            ->andReturn($idBook, $this->book);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $response = $this->requestControllerTest->reject($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_reject_method_exception()
    {
        $this->request->id = 1;
        $this->request->status = 5;

        $this->request->setRelation('books', $this->book);
        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->expectException(HttpException::class);

        $response = $this->requestControllerTest->reject($this->request);
        $this->assertEquals(false, $response);
    }

    public function test_borrowedBook_method()
    {
        $id = $this->request->id = 1;
        $this->request->status = 1;
        $status = 3;

        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn($id, $status);

        $response = $this->requestControllerTest->borrowedBook($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_borrowedBook_fail_method()
    {
        $id = $this->request->id = 1;
        $this->request->status = 1;
        $status = 3;

        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $response = $this->requestControllerTest->borrowedBook($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_borrowedBook_method_exception()
    {
        $id = $this->request->id = 1;
        $this->request->status = 2;

        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->expectException(HttpException::class);

        $response = $this->requestControllerTest->borrowedBook($this->request);
        $this->assertEquals(false, $response);
    }

    public function test_returnBook_method()
    {
        $idBook = $this->book->id = 10;
        $this->book->in_stock += 1;
        $idRequest = $this->request->id = 1;
        $status = $this->request->status = 3;
        $book = collect();
        $book->push($this->book);

        $this->request->setRelation('books', $book);
        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->bookRepo->shouldReceive('update')
            ->once()
            ->andReturn($idBook, $this->book);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn($idRequest, $status);

        $response = $this->requestControllerTest->returnBook($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_returnBook_method_fail()
    {
        $idBook = $this->book->id = 10;
        $this->book->in_stock += 1;
        $idRequest = $this->request->id = 1;
        $status = $this->request->status = 3;
        $book = collect();
        $book->push($this->book);

        $this->request->setRelation('books', $book);
        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->bookRepo->shouldReceive('update')
            ->once()
            ->andReturn($idBook, $this->book);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $response = $this->requestControllerTest->returnBook($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_returnBook_method_exception()
    {
        $this->request->id = 1;
        $this->request->status = 5;

        $this->request->setRelation('books', $this->book);
        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->expectException(HttpException::class);

        $response = $this->requestControllerTest->returnBook($this->request);
        $this->assertEquals(false, $response);
    }

    public function test_undo_method_condition_accept()
    {
        $idRequest = $this->request->id = 1;
        $this->request->status = 1;
        $status = 0;

        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn($idRequest, $status);

        $response = $this->requestControllerTest->undo($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_undo_method_condition_reject()
    {
        $idBook = $this->book->id = 10;
        $idRequest = $this->request->id = 1;
        $this->request->status = 2;
        $status = 0;
        $book = collect();
        $book->push($this->book);

        $this->request->setRelation('books', $book);

        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->bookRepo->shouldReceive('update')
            ->once()
            ->andReturn($idBook, $this->book);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn($idRequest, $status);

        $response = $this->requestControllerTest->undo($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_undo_method_condition_borrow()
    {
        $idRequest = $this->request->id = 1;
        $this->request->status = 3;
        $status = 1;

        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn($idRequest, $status);

        $response = $this->requestControllerTest->undo($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_undo_method_condition_return()
    {
        $idBook = $this->book->id = 10;
        $idRequest = $this->request->id = 1;
        $this->request->status = 4;
        $status = 3;
        $book = collect();
        $book->push($this->book);

        $this->request->setRelation('books', $book);

        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->bookRepo->shouldReceive('update')
            ->once()
            ->andReturn($idBook, $this->book);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn($idRequest, $status);

        $response = $this->requestControllerTest->undo($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_undo_method_fail()
    {
        $idRequest = $this->request->id = 1;
        $this->request->status = 3;
        $status = 1;

        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->requestRepo->shouldReceive('update')
            ->once()
            ->andReturn(false);

        $response = $this->requestControllerTest->undo($this->request);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_undo_method_condition_default()
    {
        $this->request->status = 10;

        $this->requestRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->request);
        $this->expectException(HttpException::class);

        $response = $this->requestControllerTest->undo($this->request);
        $this->assertEquals(false, $response);
    }
}
