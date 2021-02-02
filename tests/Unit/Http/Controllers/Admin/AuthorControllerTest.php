<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use App\Repositories\Author\AuthorRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Mockery as m;
use Symfony\Component\HttpFoundation\FileBag;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    protected $authorRepo;
    protected $authorControllerTest;

    public function setUp(): void
    {
        parent::setUp();

        $this->authorRepo = m::mock(AuthorRepositoryInterface::class)->makePartial();
        $this->authorControllerTest = new AuthorController($this->authorRepo);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        
        m::close();
        unset($this->authorControllerTest);
    }

    public function test_index_method()
    {
        $this->authorRepo->shouldReceive('getAll');
        $view = $this->authorControllerTest->index();
        $this->assertEquals('admin.author.index', $view->getName());
        $this->assertArrayHasKey('authors', $view->getData());
    }

    public function test_create_return_view_method()
    {
        $view = $this->authorControllerTest->create();
        $this->assertEquals('admin.author.create', $view->getName());
    }

    public function test_store_has_file_method()
    {
        $data = [
            'name' => 'Bui Quang Anh',
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
            'date_of_born' => '',
            'date_of_death' => '',
            'description' => 'Tac Gia Moi',
        ];
        $request = new AuthorRequest($data);
        $this->authorRepo
            ->shouldReceive('create')
            ->withAnyArgs($data)
            ->once()
            ->andReturn(true);

        $response = $this->authorControllerTest->store($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_store_no_file_method()
    {
        $data = [
            'name' => 'Bui Quang Anh',
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
            'date_of_born' => '',
            'date_of_death' => '',
            'description' => 'Tac Gia Moi',
        ];
        $bag = new FileBag([
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
        ]);
        $request = new AuthorRequest($data);
        $request->files = $bag;
        
        $this->authorRepo
            ->shouldReceive('create')
            ->withAnyArgs($request)
            ->once()
            ->andReturn(true);

        $response = $this->authorControllerTest->store($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_store_fail_method()
    {
        $data = [
            'name' => 'Bui Quang Anh',
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
            'date_of_born' => '',
            'date_of_death' => '',
            'description' => 'Tac Gia Moi',
        ];
        $request = new AuthorRequest($data);

        $this->authorRepo
            ->shouldReceive('create')
            ->withAnyArgs($data)
            ->once()
            ->andReturn(false);

        $response = $this->authorControllerTest->store($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_edit_method()
    {
        $id = 1;
        $this->authorRepo
            ->shouldReceive('find')
            ->withAnyArgs($id)
            ->once()
            ->andReturn(true);
        $view = $this->authorControllerTest->edit($id);

        $this->assertEquals('admin.author.edit', $view->getName());
        $this->assertArrayHasKey('author', $view->getData());
    }

    public function test_update_method()
    {
        $id = 1;
        $data = [
            'name' => 'Bui Quang Anh',
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
            'date_of_born' => '',
            'date_of_death' => '',
            'description' => 'Tac Gia Moi',
        ];

        $request = new AuthorRequest($data);

        $bag = new FileBag([
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
        ]);

        $request->files = $bag;

        $this->authorRepo
            ->shouldReceive('find')
            ->withAnyArgs($id)
            ->once()
            ->andReturn($request);

        $this->authorRepo
            ->shouldReceive('update')
            ->withAnyArgs($id, $request)
            ->once()
            ->andReturn($request);

        $view = $this->authorControllerTest->update($request, $id);
        $this->assertEquals(route('admin.authors.index'), $view->getTargetUrl());
    }

    public function test_update_fail_method()
    {
        $id = 1;
        $data = [
            'name' => 'Bui Quang Anh',
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
            'date_of_born' => '',
            'date_of_death' => '',
            'description' => 'Tac Gia Moi',
        ];

        $request = new AuthorRequest($data);

        $bag = new FileBag([
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
        ]);

        $request->files = $bag;

        $this->authorRepo
            ->shouldReceive('find')
            ->withAnyArgs($id)
            ->once()
            ->andReturn($request);

        $this->authorRepo
            ->shouldReceive('update')
            ->withAnyArgs($id, $request)
            ->once()
            ->andReturn(false);

        $view = $this->authorControllerTest->update($request, $id);
        $this->assertEquals(route('admin.authors.index'), $view->getTargetUrl());
    }

    public function test_author_destroy_method()
    {
        $id = 1;
        $data = [
            'name' => 'Bui Quang Anh',
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
            'date_of_born' => '',
            'date_of_death' => '',
            'description' => 'Tac Gia Moi',
        ];
        $author = new Author($data);

        $this->authorRepo->shouldReceive('getRelatedBook')
            ->withAnyArgs($id)
            ->once()
            ->andReturn($author);

        $this->authorRepo->shouldReceive('destroy')
            ->once()
            ->andReturn(true);

        $view = $this->authorControllerTest->destroy($id);
        $this->assertEquals(route('admin.authors.index'), $view->getTargetUrl());
    }

    public function test_author_destroy_fail_method()
    {
        $id = 1;
        $data = [
            'name' => 'Bui Quang Anh',
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
            'date_of_born' => '',
            'date_of_death' => '',
            'description' => 'Tac Gia Moi',
        ];
        $request = new Author($data);

        $this->authorRepo->shouldReceive('getRelatedBook')
            ->withAnyArgs($id)
            ->once()
            ->andReturn($request);

        $this->authorRepo->shouldReceive('destroy')
            ->once()
            ->andReturn(false);

        $view = $this->authorControllerTest->destroy($id);
        $this->assertEquals(route('admin.authors.index'), $view->getTargetUrl());
    }

    public function test_author_destroy_not_empty_relation_method()
    {
        $id = 1;
        $dataAuthor = [
            'name' => 'Bui Quang Anh',
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
            'date_of_born' => '',
            'date_of_death' => '',
            'description' => 'Tac Gia Moi',
        ];

        $dataBook = [
            'image' => UploadedFile::fake()->image('avatar', '100', '100')->size(100),
            'name' => 'Book Test',
            'author_id' => 1,
            'publisher_id' => 1,
        ];

        $author = new Author($dataAuthor);
        $author->setRelation('books', new Collection($dataBook));

        $this->authorRepo->shouldReceive('getRelatedBook')
            ->once()
            ->andReturn($author);

        $view = $this->authorControllerTest->destroy($id);
        $this->assertEquals(route('admin.authors.index'), $view->getTargetUrl());
    }
}
