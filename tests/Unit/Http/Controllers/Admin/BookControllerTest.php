<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BookController;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\User;
use App\Repositories\Author\AuthorRepositoryInterface;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Publisher\PublisherRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Mockery as m;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    protected $publisherRepo, $bookRepo, $categoryRepo, $authorRepo;
    protected $book, $bookId;
    protected $bookController;
    protected $userAuthorized, $userUnauthorized;
    protected $relationCategory;
    protected $dataNotImage;
    protected $dataKey;
    protected $relation;

    public function setUp(): void
    {
        parent::setUp();

        $this->bookRepo = m::mock(BookRepositoryInterface::class)->makePartial();
        $this->authorRepo = m::mock(AuthorRepositoryInterface::class)->makePartial();
        $this->categoryRepo = m::mock(CategoryRepositoryInterface::class)->makePartial();
        $this->publisherRepo = m::mock(PublisherRepositoryInterface::class)->makePartial();
        $this->bookController = new BookController($this->bookRepo, $this->authorRepo, $this->categoryRepo, $this->publisherRepo);
        $this->userAuthorized = factory(User::class)->make();
        $this->relationCategory = 'categories';
        $this->relation =  ['author', 'publisher', 'categories'];
        $this->dataKey = ['key' => 'Key Search'];
        $this->bookId = 1;
        $this->userUnauthorized = new User([
            'name' => 'Unauthorized',
            'email' => 'Unauthorized@gmail.com',
            'password' => '123456',
            'address' => 'Hà Đông',
            'phone' => '0334736187',
            'role_id' => 10,
            'times' => 0,
            'status' => 0,
        ]);
        $this->book = factory(Book::class)->make();
        $this->data = [
            'name' => 'Book Test',
            'image' => UploadedFile::fake()->image('book.jpg', '100', '100')->size(100),
            'author_id' => '1',
            'publisher_id' => '1',
            'category_id' => ['1'],
            'in_stock' => '1',
            'total' => '1',
            'status' => '1',
            'description' => 'Lorem',
        ];
        $this->dataNotImage = [
            'name' => 'Book Test',
            'author_id' => '1',
            'publisher_id' => '1',
            'category_id' => ['1'],
            'in_stock' => '1',
            'total' => '1',
            'status' => '1',
            'description' => 'Lorem',
        ];
        $this->session = ['language' => 'vi'];
    }

    public function tearDown(): void
    {
        m::close();

        unset($this->bookRepo);
        unset($this->publisherRepo);
        unset($this->categoryRepo);
        unset($this->authorRepo);
        unset($this->bookController);
        unset($this->userAuthorized);
        unset($this->userUnauthorized);
        unset($this->relationCategory);
        unset($this->book);
        unset($this->data);
        unset($this->bookId);
        unset($this->dataNotImage);
        unset($this->dataKey);
        unset($this->relation);

        parent::tearDown();
    }

    public function test_index_method_authorized()
    {
        $this->be($this->userAuthorized);
        $this->bookRepo->shouldReceive('getAll')
            ->once()
            ->andReturn();
        $view = $this->bookController->index();
        $this->assertEquals('admin.book.index', $view->getName());
        $this->assertArrayHasKey('books', $view->getData());
    }

    public function test_index_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $this->bookRepo->shouldReceive('getAll');
        $view = $this->bookController->index();
        $this->assertEquals('admin.book.index', $view->getName());
        $this->assertArrayHasKey('books', $view->getData());
    }

    public function test_create_method_authorized()
    {
        $this->be($this->userAuthorized);
        $this->categoryRepo->shouldReceive('getChildren');
        $this->authorRepo->shouldReceive('getAll');
        $this->publisherRepo->shouldReceive('getAll');
        $view = $this->bookController->create();
        $this->assertEquals('admin.book.create', $view->getName());
        $this->assertArrayHasKey('categories', $view->getData());
        $this->assertArrayHasKey('authors', $view->getData());
        $this->assertArrayHasKey('publishers', $view->getData());
    }

    public function test_create_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $this->categoryRepo->shouldReceive('getChildren');
        $this->authorRepo->shouldReceive('getAll');
        $this->publisherRepo->shouldReceive('getAll');
        $view = $this->bookController->create();
        $this->assertEquals('admin.book.create', $view->getName());
        $this->assertArrayHasKey('categories', $view->getData());
        $this->assertArrayHasKey('authors', $view->getData());
        $this->assertArrayHasKey('publishers', $view->getData());
    }

    public function test_store_book_has_image_method_authorized()
    {
        $this->be($this->userAuthorized);
        $request = new BookRequest($this->data);
        $request->files = new FileBag([
            'image' => $this->data['image'],
        ]);
        $data = $request->all();
        $this->bookRepo->shouldReceive('create')
            ->withAnyArgs($data)
            ->once()
            ->andReturn(true);
        $this->bookRepo->shouldReceive('attach')
            ->withAnyArgs($this->book, $this->relationCategory, $request->category_id)
            ->once()
            ->andReturn(true);
        $view = $this->bookController->store($request)->withSession($this->session);
        $this->assertEquals(route('admin.books.index'), $view->getTargetUrl());
    }

    public function test_store_book_has_not_image_method_authorized()
    {
        $this->be($this->userAuthorized);
        $request = new BookRequest($this->dataNotImage);
        $data = $request->all();
        $this->bookRepo->shouldReceive('create')
            ->withAnyArgs($data)
            ->once()
            ->andReturn(true);
        $this->bookRepo->shouldReceive('attach')
            ->withAnyArgs($this->book, $this->relationCategory, $request->category_id)
            ->once()
            ->andReturn(true);
        $view = $this->bookController->store($request);
        $this->assertEquals(route('admin.books.index'), $view->getTargetUrl());
    }

    public function test_store_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $request = new BookRequest($this->data);
        $request->files = new FileBag([
            'image' => $this->data['image'],
        ]);
        $data = $request->all();
        $this->bookRepo->shouldReceive('create');
        $this->bookRepo->shouldReceive('attach');
        $view = $this->bookController->store($request)->withSession($this->session);
        $this->assertEquals(route('admin.books.index'), $view->getTargetUrl());
    }

    public function test_show_method_authorized()
    {
        $this->be($this->userAuthorized);
        $this->bookRepo->shouldReceive('find')
            ->with($this->bookId)
            ->once()
            ->andReturn($this->book);
        $this->bookRepo->shouldReceive('load')
            ->with($this->book,  $this->relation)
            ->once()
            ->andReturn($this->book);
        $view = $this->bookController->show($this->bookId);
        $this->assertEquals('admin.book.detail', $view->getName());
        $this->assertArrayHasKey('book', $view->getData());
    }

    public function test_show_undefined_book_method_authorized()
    {
        $this->be($this->userAuthorized);
        $this->bookRepo->shouldReceive('find')
            ->with($this->bookId)
            ->once()
            ->andReturn(false);
        $this->bookRepo->shouldReceive('load');
        $view = $this->bookController->show($this->bookId);
        $this->assertEquals(route('admin.books.index'), $view->getTargetUrl());
    }

    public function test_show_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $this->bookRepo->shouldReceive('find');
        $this->bookRepo->shouldReceive('load');
        $view = $this->bookController->show($this->bookId);
        $this->assertEquals('admin.book.detail', $view->getName());
        $this->assertArrayHasKey('book', $view->getData());
    }

    public function test_edit_authorized()
    {
        $this->be($this->userAuthorized);
        $this->categoryRepo->shouldReceive('getChildren');
        $this->authorRepo->shouldReceive('with')
            ->with(['books'])
            ->once()
            ->andReturn(true);
        $this->publisherRepo->shouldReceive('with')
            ->with(['books'])
            ->once()
            ->andReturn(true);
        $this->bookRepo->shouldReceive('find')
            ->with($this->bookId)
            ->once()
            ->andReturn($this->book);
        $this->bookRepo->shouldReceive('load')
            ->withAnyArgs($this->book,  $this->relation)
            ->once()
            ->andReturn($this->book);
        $view = $this->bookController->edit($this->bookId);
        $this->assertEquals('admin.book.edit', $view->getName());
        $this->assertArrayHasKey('categories', $view->getData());
        $this->assertArrayHasKey('authors', $view->getData());
        $this->assertArrayHasKey('publishers', $view->getData());
        $this->assertArrayHasKey('book', $view->getData());
    }

    public function test_edit_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $this->categoryRepo->shouldReceive('getChildren');
        $this->authorRepo->shouldReceive('with');
        $this->publisherRepo->shouldReceive('with');
        $this->bookRepo->shouldReceive('find');
        $this->bookRepo->shouldReceive('load');
        $view = $this->bookController->edit($this->bookId);
        $this->assertEquals('admin.book.edit', $view->getName());
        $this->assertArrayHasKey('categories', $view->getData());
        $this->assertArrayHasKey('authors', $view->getData());
        $this->assertArrayHasKey('publishers', $view->getData());
        $this->assertArrayHasKey('book', $view->getData());
    }

    public function test_update_has_image_find_authorized()
    {
        $this->be($this->userAuthorized);
        $request = new BookRequest($this->dataNotImage);
        $request->files = new FileBag([
            'image' => $this->data['image'],
        ]);
        $data = $request->all();
        $this->bookRepo->shouldReceive('find')
            ->with($this->bookId)
            ->once()
            ->andReturn($this->book);
        $this->bookRepo->shouldReceive('sync')
            ->withAnyArgs($this->book, $this->relationCategory, $data['category_id'])
            ->once()
            ->andReturn(true);
        $this->bookRepo->shouldReceive('update')
            ->withAnyArgs($this->bookId, $data)
            ->once()
            ->andReturn($this->book);
        $view = $this->bookController->update($request, $this->bookId);
        $this->assertEquals(route('admin.books.index'), $view->getTargetUrl());
    }

    public function test_update_has_not_image_find_authorized()
    {
        $this->be($this->userAuthorized);
        $request = new BookRequest($this->dataNotImage);
        $data = $request->all();
        $this->bookRepo->shouldReceive('find')
            ->with($this->bookId)
            ->once()
            ->andReturn($this->book);
        $this->bookRepo->shouldReceive('sync')
            ->withAnyArgs($this->book, $this->relationCategory, $data['category_id'])
            ->once()
            ->andReturn(true);
        $this->bookRepo->shouldReceive('update')
            ->withAnyArgs($this->bookId, $data)
            ->once()
            ->andReturn($this->book);
        $view = $this->bookController->update($request, $this->bookId);
        $this->assertEquals(route('admin.books.index'), $view->getTargetUrl());
    }

    public function test_update_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $request = new BookRequest($this->dataNotImage);
        $request->files = new FileBag([
            'image' => $this->data['image'],
        ]);
        $data = $request->all();
        $this->bookRepo->shouldReceive('find');
        $this->bookRepo->shouldReceive('sync')
            ->withAnyArgs($this->book, $this->relationCategory, $data['category_id']);
        $this->bookRepo->shouldReceive('update');
        $view = $this->bookController->update($request, $this->bookId);
        $this->assertEquals(route('admin.books.index'), $view->getTargetUrl());
    }

    public function test_destroy_authorized()
    {
        $this->be($this->userAuthorized);
        $this->bookRepo->shouldReceive('destroy')
            ->with($this->bookId)
            ->once()
            ->andReturn(true);
        $view = $this->bookController->destroy($this->bookId);
        $this->assertEquals(route('admin.books.index'), $view->getTargetUrl());
    }

    public function test_destroy_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $this->bookRepo->shouldReceive('destroy');
        $view = $this->bookController->destroy($this->bookId);
        $this->assertEquals(route('admin.books.index'), $view->getTargetUrl());
    }

    public function test_search_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $request = new Request($this->dataKey);
        $this->bookRepo->shouldReceive('search');
        $view = $this->bookController->search($request);
        $this->assertEquals('admin.book.search', $view->getName());
    }

    public function test_search_authorized()
    {
        $this->be($this->userAuthorized);
        $request = new Request($this->dataKey);
        $this->bookRepo->shouldReceive('search')
            ->with($request->key)
            ->once()
            ->andReturn($this->book);
        $view = $this->bookController->search($request);
        $this->assertEquals('admin.book.search', $view->getName());
        $this->assertArrayHasKey('books', $view->getData());
    }

    public function test_cate_popup_method()
    {
        $this->categoryRepo->shouldReceive('getParentAll');
        $view = $this->bookController->catePopup();
        $this->assertEquals('admin.book.category_popup', $view->getName());
        $this->assertArrayHasKey('categoryParents', $view->getData());
    }

    public function test_list_deleted_book()
    {
        $this->bookRepo->shouldReceive('getSoftDelete');
        $view = $this->bookController->listDeleteBook();
        $this->assertEquals('admin.book.delete', $view->getName());
        $this->assertArrayHasKey('books', $view->getData());
    }

    public function test_restore_book_success_method()
    {
        $this->bookRepo->shouldReceive('restoreSoftDelete')
            ->with($this->bookId)
            ->once()
            ->andReturn(true);
        $view = $this->bookController->restoreBook($this->bookId);
        $this->assertEquals(route('admin.book-delete'), $view->getTargetUrl());
    }

    public function test_restore_book_fail_method()
    {
        $this->bookRepo->shouldReceive('restoreSoftDelete')
            ->with($this->bookId)
            ->once()
            ->andReturn(false);
        $view = $this->bookController->restoreBook($this->bookId);
        $this->assertEquals(route('admin.book-delete'), $view->getTargetUrl());
    }

    public function test_hard_delete_success_method()
    {
        $this->bookRepo->shouldReceive('findSoftDelete')
            ->with($this->bookId)
            ->once()
            ->andReturn($this->book);
        $book = $this->book;
        $this->bookRepo->shouldReceive('sync')
            ->withAnyArgs($book, $this->relationCategory)
            ->once()
            ->andReturn(true);
            $this->bookRepo->shouldReceive('hardDelete')
            ->with($this->bookId)
            ->once()
            ->andReturn(true);
        $view = $this->bookController->hardDelete($this->bookId);
        $this->assertEquals(route('admin.book-delete'), $view->getTargetUrl());
    }

    public function test_hard_delete_fail_method()
    {
        $this->bookRepo->shouldReceive('findSoftDelete')
            ->with($this->bookId)
            ->once()
            ->andReturn($this->book);
        $book = $this->book;
        $this->bookRepo->shouldReceive('sync')
            ->withAnyArgs($book, $this->relationCategory)
            ->once()
            ->andReturn(true);
            $this->bookRepo->shouldReceive('hardDelete')
            ->with($this->bookId)
            ->once()
            ->andReturn(false);
        $view = $this->bookController->hardDelete($this->bookId);
        $this->assertEquals(route('admin.book-delete'), $view->getTargetUrl());
    }
}
