<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\BookController;
use App\Models\Book;
use App\Models\Category;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Publisher\PublisherRepositoryInterface;
use Illuminate\Http\Request;
use Mockery as m;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    protected $publisherRepo, $bookRepo, $categoryRepo;
    protected $book;
    protected $category;
    protected $bookController;
    protected $page, $userId, $bookId, $categoryId;
    protected $searchKey;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->bookRepo = m::mock(BookRepositoryInterface::class)->makePartial();
        $this->publisherRepo = m::mock(PublisherRepositoryInterface::class)->makePartial();
        $this->categoryRepo = m::mock(CategoryRepositoryInterface::class)->makePartial();
        $this->bookController = new BookController($this->bookRepo, $this->categoryRepo, $this->publisherRepo);
        $this->page = ['page' => 1];
        $this->categoryId = 1;
        $this->bookId = 1;
        $this->userId = 1;
        $this->book = factory(Book::class)->make();
        $this->category = factory(Category::class)->make();
        $this->category->setRelation('books', $this->book);
        $this->searchKey = ['key' => 'Search Key'];
    }

    public function tearDown(): void
    {
        m::close();

        unset($this->bookRepo);
        unset($this->publisherRepo);
        unset($this->categoryRepo);
        unset($this->bookController);
        unset($this->page);
        unset($this->categoryId);
        unset($this->bookId);
        unset($this->userId);
        unset($this->book);
        unset($this->searchKey);

        parent::tearDown();
    }

    public function test_index_method()
    {
        $request = new Request($this->page);
        $this->bookRepo->shouldReceive('getVisibleBook');
        $view = $this->bookController->index($request);
        $this->assertEquals('client.home', $view->getName());
        $this->assertArrayHasKey('books', $view->getData());
        $this->assertArrayHasKey('page', $view->getData());
    }

    public function test_get_category_method()
    {
        $this->categoryRepo->shouldReceive('withFind')
            ->with($this->categoryId, ['books'])
            ->once()
            ->andReturn($this->category);
        $view = $this->bookController->getCategory($this->categoryId);
        $this->assertEquals('client.category_book', $view->getName());
        $this->assertArrayHasKey('category', $view->getData());
    }

    public function test_get_detail_book()
    {
        $this->bookRepo->shouldReceive('getDetailBook')
            ->withAnyArgs($this->bookId, $this->userId)
            ->once()
            ->andReturn($this->book);
        $view = $this->bookController->getDetailBook($this->bookId);
        $this->assertEquals('client.detail_book', $view->getName());
        $this->assertArrayHasKey('book', $view->getData());
    }

    public function test_search_method()
    {
        $request = new Request($this->searchKey);
        $this->bookRepo->shouldReceive('searchBookVisible')
            ->with($request->key)
            ->once()
            ->andReturn($this->book);
        $view = $this->bookController->search($request);
        $this->assertEquals('client.search_book', $view->getName());
        $this->assertArrayHasKey('books', $view->getData());
    }
}
