<?php

namespace Tests\Unit\Controllers\Admin;

use App\Http\Controllers\Admin\PublisherController;
use App\Http\Requests\PublisherRequest;
use App\Models\Publisher;
use App\Models\User;
use App\Repositories\Publisher\PublisherRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Mockery;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class PublisherControllerTest extends TestCase
{
    protected $publisherRepository, $publisherControllerTest, $user, $export;

    public function setUp(): void
    {
        parent::setUp();
        $this->publisherRepository = Mockery::mock(PublisherRepositoryInterface::class)->makePartial();
        $this->publisherControllerTest = new PublisherController($this->publisherRepository);
        $this->userAuthorized = factory(User::class)->make();
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
        $this->data = [
            'name' => 'test case 1 ne',
            'image' => UploadedFile::fake()->image('avatar.jpg', '100', '100')->size(100),
            'email' => 'testcase123@gmail.com',
            'phone' => '0334736187',
            'address' => 'HĐ',
            'description' => '',
        ];
    }

    public function tearDown(): void
    {
        Mockery::close();
        unset($this->publisherRepository);
        unset($this->publisherControllerTest);
        unset($this->userAuthorized);
        unset($this->userUnauthorized);
        unset($this->data);
        parent::tearDown();
    }

    public function test_index_method_authorized()
    {
        $this->be($this->userAuthorized);
        $this->publisherRepository->shouldReceive('getAll');
        $view = $this->publisherControllerTest->index();
        $this->assertEquals('admin.publisher.index', $view->getName());
        $this->assertArrayHasKey('publishers', $view->getData());
    }

    public function test_index_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $this->publisherRepository->shouldReceive('getAll');
        $view = $this->publisherControllerTest->index();
        $this->assertEquals('admin.publisher.index', $view->getName());
        $this->assertArrayHasKey('publishers', $view->getData());
    }

    public function test_create_method_authorized()
    {
        $this->be($this->userAuthorized);
        $view = $this->publisherControllerTest->create();
        $this->assertEquals('admin.publisher.create', $view->getName());
    }

    public function test_create_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $view = $this->publisherControllerTest->create();
        $this->assertEquals('admin.publisher.create', $view->getName());
    }

    public function test_store_method_authorized()
    {
        $this->be($this->userAuthorized);
        $request = new PublisherRequest($this->data);
        $request->files = new FileBag([
            'image' => $this->data['image'],
        ]);
        $this->publisherRepository->shouldReceive('create')
            ->once()
            ->andReturn($this->data);
        $reponse = $this->publisherControllerTest->store($request);
        $this->assertEquals(route('admin.publishers.store'), $reponse->getTargetUrl());
    }

    public function test_store_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $request = new PublisherRequest($this->data);
        $reponse = $this->publisherControllerTest->store($request);
        $this->assertEquals(route('admin.publishers.store'), $reponse->getTargetUrl());
    }

    public function test_has_file_false_condition()
    {
        $this->be($this->userAuthorized);
        $this->data['image'] = '';
        $request = new PublisherRequest($this->data);
        $this->publisherRepository->shouldReceive('create')
            ->once()
            ->andReturn($this->data);
        $reponse = $this->publisherControllerTest->store($request);
        $this->assertEquals(route('admin.publishers.store'), $reponse->getTargetUrl());
    }

    public function test_store_method_false_request()
    {
        $this->be($this->userAuthorized);
        $data = [
            'name' => '',
            'image' => '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'description' => '',
        ];
        $request = new PublisherRequest($data);
        $this->publisherRepository->shouldReceive('create')
            ->once()
            ->andReturn(false);
        $reponse = $this->publisherControllerTest->store($request);
        $this->assertEquals(route('admin.publishers.store'), $reponse->getTargetUrl());
    }

    public function test_edit_method_authorized()
    {
        $this->data['image'] = '';
        $this->be($this->userAuthorized);
        $this->publisherRepository->shouldReceive('find')
            ->once()
            ->andReturn(true);
        $view = $this->publisherControllerTest->edit($this->data);
        $this->assertEquals('admin.publisher.edit', $view->getName());
    }

    public function test_edit_method_unauthorized()
    {
        $this->data['image'] = '';
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $view = $this->publisherControllerTest->edit($this->data);
        $this->assertEquals('admin.publisher.edit', $view->getName());
    }

    public function test_destroy_method_authorized()
    {
        $this->be($this->userAuthorized);
        $publisher = new Publisher($this->data);
        $this->publisherRepository->shouldReceive('loadBook')
            ->withAnyArgs($this->data)
            ->once()
            ->andReturn($publisher);
        $this->publisherRepository->shouldReceive('destroy')
            ->once()
            ->andReturn(true);
        $view = $this->publisherControllerTest->destroy($this->data);
        $this->assertEquals(route('admin.publishers.index'), $view->getTargetUrl());
    }

    public function test_destroy_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $view = $this->publisherControllerTest->destroy($this->data);
        $this->assertEquals(route('admin.publishers.index'), $view->getTargetUrl());
    }

    public function test_destroy_method_condition_empty_book()
    {
        $this->be($this->userAuthorized);
        $publisherId = 4;
        $bookId = 21;
        $this->data['id'] = $publisherId;
        $dataB = [
            'id' => $bookId,
            'image' => UploadedFile::fake()->image('avatar.jpg', '100', '100')->size(100),
            'name' => 'sach test 1',
            'author_id' => '1',
            'publisher_id' => '4',
            'in_stock' => '50',
            'total' => '50',
            'status' => '1',
            'description' => 'sach test 1',
        ];
        $publisher = new Publisher($this->data);
        $publisher->setRelation('books', new Collection($dataB));

        $this->publisherRepository->shouldReceive('loadBook')
            ->once()
            ->andReturn($publisher);
        $view = $this->publisherControllerTest->destroy($publisherId);
        $this->assertEquals(route('admin.publishers.index'), $view->getTargetUrl());
    }

    public function test_destroy_fail_method_authorized()
    {
        $this->be($this->userAuthorized);
        $publisher = new Publisher($this->data);
        $this->publisherRepository->shouldReceive('loadBook')
            ->withAnyArgs($this->data)
            ->once()
            ->andReturn($publisher);
        $this->publisherRepository->shouldReceive('destroy')
            ->once()
            ->andReturn(false);
        $view = $this->publisherControllerTest->destroy($this->data);
        $this->assertEquals(route('admin.publishers.index'), $view->getTargetUrl());
    }

    public function test_update_method_authorized()
    {
        $this->be($this->userAuthorized);
        $publisherId = 6;
        $this->data['id'] = $publisherId;
        $request = new PublisherRequest($this->data);
        $request->files = new FileBag([
            'image' => $this->data['image'],
        ]);
        $this->publisherRepository->shouldReceive('find')
            ->once()
            ->andReturn($request);
        $this->publisherRepository->shouldReceive('update')
            ->once()
            ->andReturn($this->data);
        $view = $this->publisherControllerTest->update($request, $publisherId);
        $this->assertEquals(route('admin.publishers.index'), $view->getTargetUrl());
    }

    public function test_update_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $publisherId = 6;
        $this->data['id'] = $publisherId;
        $this->expectException(HttpException::class);
        $request = new PublisherRequest($this->data);
        $view = $this->publisherControllerTest->update($request, $publisherId);
        $this->assertEquals('admin.publisher.update', $view->getName());
    }

    public function test_update_fail_method_authorized()
    {
        $this->be($this->userAuthorized);
        $publisherId = 6;
        $this->data['id'] = $publisherId;
        $request = new PublisherRequest($this->data);
        $request->files = new FileBag([
            'image' => $this->data['image'],
        ]);
        $this->publisherRepository->shouldReceive('find')
            ->once()
            ->andReturn($request);
        $this->publisherRepository->shouldReceive('update')
            ->once()
            ->andReturn(false);
        $view = $this->publisherControllerTest->update($request, $publisherId);
        $this->assertEquals(route('admin.publishers.index'), $view->getTargetUrl());
    }

    public function test_export_method_authorized()
    {
        $this->be($this->userAuthorized);
        $publisherId = 6;
        $this->data['id'] = $publisherId;
        $request = new PublisherRequest($this->data);
        $request->files = new FileBag([
            'image' => $this->data['image'],
        ]);
        $request = new Collection($request);
        $this->publisherRepository->shouldReceive('getAll')
            ->once()
            ->andReturn($request);
        $this->publisherRepository->shouldReceive('export')
            ->once()
            ->andReturn(true);
        $response = $this->publisherControllerTest->export();
        $this->assertEquals(true, $response);
    }

    public function test_export_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $response = $this->publisherControllerTest->export();
        $this->assertEquals(false, $response);
    }

    public function test_export_fail_method_authorized()
    {
        $this->be($this->userAuthorized);
        $data = [];
        $request = new PublisherRequest($data);
        $request = new Collection($request);
        $this->publisherRepository->shouldReceive('getAll')
            ->once()
            ->andReturn($request);
        $response = $this->publisherControllerTest->export();
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }
}
