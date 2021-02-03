<?php

namespace Tests\Unit\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\PopupCategoryRequest;
use App\Models\Category;
use App\Models\User;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Support\Collection;
use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    protected $categoryRepo, $categoryControllerTest;

    public function setUp(): void
    {
        parent::setUp();
        $this->categoryRepo = Mockery::mock(CategoryRepositoryInterface::class)->makePartial();
        $this->categoryControllerTest = new CategoryController($this->categoryRepo);
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
            'id' => 1,
            'name' => 'Danh muc moi',
            'parent_id' => 0,
        ];
    }

    public function tearDown(): void
    {
        Mockery::close();
        unset($this->categoryRepo);
        unset($this->categoryControllerTest);
        unset($this->userAuthorized);
        unset($this->userUnauthorized);
        unset($this->data);
        parent::tearDown();
    }

    public function test_index_method_authorized()
    {
        $this->be($this->userAuthorized);
        $this->categoryRepo->shouldReceive('getParentOrderBy');
        $view = $this->categoryControllerTest->index();
        $this->assertEquals('admin.category.index', $view->getName());
        $this->assertArrayHasKey('categories', $view->getData());
    }

    public function test_index_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $view = $this->categoryControllerTest->index();
        $this->assertEquals('admin.category.index', $view->getName());
        $this->assertArrayHasKey('categories', $view->getData());
    }

    public function test_create_method_authorized()
    {
        $this->be($this->userAuthorized);
        $this->categoryRepo->shouldReceive('getParentOrderBy');
        $view = $this->categoryControllerTest->create();
        $this->assertEquals('admin.category.create', $view->getName());
        $this->assertArrayHasKey('categoryParents', $view->getData());
    }

    public function test_create_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $view = $this->categoryControllerTest->create();
        $this->assertEquals('admin.category.create', $view->getName());
        $this->assertArrayHasKey('categoryParents', $view->getData());
    }

    public function test_store_method_authorized()
    {
        $this->be($this->userAuthorized);
        $request = new CategoryRequest($this->data);
        $this->categoryRepo->shouldReceive('create')
            ->once()
            ->andReturn($this->data);
        $response = $this->categoryControllerTest->store($request);
        $this->assertEquals(route('admin.categories.store'), $response->getTargetUrl());
    }

    public function test_store_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $request = new CategoryRequest($this->data);
        $response = $this->categoryControllerTest->store($request);
        $this->assertEquals(route('admin.categories.store'), $response->getTargetUrl());
    }

    public function test_store_method_fail_authorized()
    {
        $this->be($this->userAuthorized);
        $this->data['name'] = '';
        $this->data['parent_id'] = null;
        $request = new CategoryRequest($this->data);
        $this->categoryRepo->shouldReceive('create')
            ->once()
            ->andReturn(false);
        $response = $this->categoryControllerTest->store($request);
        $this->assertEquals(route('admin.categories.store'), $response->getTargetUrl());
    }

    public function test_apiStore_method_authorized()
    {
        $this->be($this->userAuthorized);
        $this->data['parent_id'] = 0;
        $request = new PopupCategoryRequest($this->data);
        $this->categoryRepo->shouldReceive('create')
            ->once()
            ->andReturn($this->data);
        $this->data['parent_id'] = $this->data['id'];
        $this->data['id'] = 2;
        $this->data['name'] = 'Danh muc con of Danh muc moi';
        $request = new PopupCategoryRequest($this->data);
        $this->categoryRepo->shouldReceive('create')
            ->once()
            ->andReturn($this->data);
        $response = $this->categoryControllerTest->apiStore($request);
        $this->assertJson(json_encode($response->getData()), json_encode(['dataChild' => $this->data]));
    }

    public function test_apiStore_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $request = new PopupCategoryRequest($this->data);
        $response = $this->categoryControllerTest->apiStore($request);
        $this->assertJson(json_encode($response->getData()), json_encode(['dataChild' => $this->data]));
    }

    public function test_show_method_authorized()
    {
        $this->be($this->userAuthorized);
        $categories = new Category($this->data);
        $this->categoryRepo->shouldReceive('withFind')
            ->withAnyArgs($categories, 'children')
            ->once()
            ->andReturn(true);
        $view = $this->categoryControllerTest->show($categories);
        $this->assertEquals('admin.category.show', $view->getName());
        $this->assertArrayHasKey('category', $view->getData());
    }

    public function test_show_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $categories = new Category($this->data);
        $view = $this->categoryControllerTest->show($categories);
        $this->assertEquals('admin.category.show', $view->getName());
        $this->assertArrayHasKey('category', $view->getData());
    }

    public function test_show_method_not_find_condition_authorized()
    {
        $this->be($this->userAuthorized);
        $this->data['parent_id'] = '19999';
        $categories = new Category($this->data);
        $this->categoryRepo->shouldReceive('withFind')
            ->withAnyArgs($categories, 'children')
            ->once()
            ->andReturn(false);
        $view = $this->categoryControllerTest->show($categories);
        $this->assertEquals(route('admin.categories.index'), $view->getTargetUrl());
    }

    public function test_edit_method_authorized()
    {
        $this->be($this->userAuthorized);
        $this->categoryRepo->shouldReceive('find')
            ->once()
            ->andReturn($this->data['id']);
        $this->categoryRepo->shouldReceive('getAll');
        $view = $this->categoryControllerTest->edit($this->data['id']);
        $this->assertEquals('admin.category.edit', $view->getName());
    }

    public function test_edit_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $this->expectException(HttpException::class);
        $view = $this->categoryControllerTest->edit($this->data['id']);
        $this->assertEquals('admin.category.edit', $view->getName());
    }

    public function test_update_method_condition_children_authorized()
    {
        $this->be($this->userAuthorized);
        $dataChild = [
            'id' => 2,
            'name' => 'Danh muc con of danh muc cha',
            'parent_id' => 1,
        ];
        $request = new CategoryRequest($dataChild);
        $child = new Category($dataChild);
        $child->setRelation('parent', new Collection($this->data));
        $this->categoryRepo->shouldReceive('load')
            ->withAnyArgs($this->data, 'parent')
            ->once()
            ->andReturn($child);
        $this->categoryRepo->shouldReceive('find')
            ->once()
            ->andReturn($request->id);
        $this->categoryRepo->shouldReceive('update')
            ->once()
            ->andReturn($request->id, $request);
        $response = $this->categoryControllerTest->update($request, $request->id);
        $this->assertEquals(route('admin.categories.show', $child->parent_id), $response->getTargetUrl());
    }

    public function test_update_method_condition_parent_authorized()
    {
        $this->be($this->userAuthorized);
        $dataChild = [
            'id' => 5,
            'name' => 'Danh muc con of danh muc cha',
            'parent_id' => 2,
        ];
        $request = new CategoryRequest($this->data);
        $child = new Category($dataChild);
        $this->categoryRepo->shouldReceive('find')
            ->once()
            ->andReturn($request->id);
        $this->categoryRepo->shouldReceive('update')
            ->once()
            ->andReturn($request->id, $request);
        $this->categoryRepo->shouldReceive('load')
            ->withAnyArgs($child)
            ->once()
            ->andReturn(false);
        $response = $this->categoryControllerTest->update($request, $request->id);
        $this->assertEquals(route('admin.categories.index'), $response->getTargetUrl());
    }

    public function test_update_method_fail_authorized()
    {
        $this->be($this->userAuthorized);
        $this->data['name'] = '';
        $this->data['parent_id'] = null;
        $request = new CategoryRequest($this->data);
        $parent = new Category($this->data);
        $this->categoryRepo->shouldReceive('find')
            ->once()
            ->andReturn($request->id);
        $this->categoryRepo->shouldReceive('update')
            ->once()
            ->andReturn(false);
        $this->categoryRepo->shouldReceive('load')
            ->once()
            ->andReturn($parent);
        $response = $this->categoryControllerTest->update($request, $request->id);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_update_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $request = new CategoryRequest($this->data);
        $this->expectException(HttpException::class);
        $response = $this->categoryControllerTest->update($request, $request->id);
        $this->assertEquals('admin.category.update', $view->getName());
    }

    public function test_destroy_method_condition_check_has_children_authorized()
    {
        $this->be($this->userAuthorized);
        $category = new Category($this->data);
        $dataChild = [
            'id' => 2,
            'name' => 'Danh muc con of danh muc cha',
            'parent_id' => 1,
        ];
        $category->setRelation('children', new Collection($dataChild));
        $this->categoryRepo->shouldReceive('find')
            ->once()
            ->andReturn($category);
        $this->categoryRepo->shouldReceive('load')
            ->withAnyArgs($category, 'children')
            ->once()
            ->andReturn($category);
        $response = $this->categoryControllerTest->destroy($dataChild['id']);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_destroy_method_authorized()
    {
        $this->be($this->userAuthorized);
        $this->data['parent_id'] = 1;
        $category = new Category($this->data);
        $dataChild = [
            'id' => 2,
            'name' => 'Danh muc con of danh muc cha',
            'parent_id' => 1,
        ];
        $this->categoryRepo->shouldReceive('find')
            ->once()
            ->andReturn($category);
        $this->categoryRepo->shouldReceive('destroy')
            ->once()
            ->andReturn($dataChild['id']);
        $response = $this->categoryControllerTest->destroy($dataChild['id']);
        $this->assertEquals('http://127.0.0.1:8000', $response->getTargetUrl());
    }

    public function test_destroy_method_fali_authorized()
    {
        $this->be($this->userAuthorized);
        $this->data['parent_id'] = 1;
        $category = new Category($this->data);
        $dataChild = [
            'id' => 2,
            'name' => 'Danh muc con of danh muc cha',
            'parent_id' => 1,
        ];
        $this->categoryRepo->shouldReceive('find')
            ->once()
            ->andReturn($category);
        $this->categoryRepo->shouldReceive('destroy')
            ->once()
            ->andReturn(false);
        $response = $this->categoryControllerTest->destroy($dataChild['id']);
        $this->assertEquals(route('admin.categories.index'), $response->getTargetUrl());
    }

    public function test_destroy_method_unauthorized()
    {
        $this->be($this->userUnauthorized);
        $dataChild = [
            'id' => 2,
            'name' => 'Danh muc con of danh muc cha',
            'parent_id' => 1,
        ];
        $request = new CategoryRequest($this->data);
        $this->expectException(HttpException::class);
        $response = $this->categoryControllerTest->destroy($dataChild['id']);
        $this->assertEquals(route('admin.categories.index'), $response->getTargetUrl());
    }
}
