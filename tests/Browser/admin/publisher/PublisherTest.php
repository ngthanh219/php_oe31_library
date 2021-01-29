<?php

namespace Tests\Browser\admin\publisher;

use App\Models\Publisher;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PublisherTest extends DuskTestCase
{
    public function test_view_index_publishers_in_english()
    {
        $this->browse(function (Browser $browser) {
            $user = User::all()->first();
            $browser->loginAs($user)
                ->visit('admin/publishers')
                ->assertSee($user->name)
                ->assertSee('Role')
                ->assertSee('Users manager')
                ->assertSee('Request')
                ->assertSee('Publisher')
                ->assertSee('Category')
                ->assertSee('Author')
                ->assertSee('Book')
                ->assertSee('Book Deleted Manager')
                ->assertSee('Publisher Manager')
                ->assertSee('Add New')
                ->assertSee('Export')
                ->assertSee('Publisher List')
                ->assertSee('Publisher')
                ->assertSee('Logo')
                ->assertSee('Email')
                ->assertSee('Phone')
                ->assertSee('Address')
                ->assertSee('Actions')
                ->assertSee('SUN *')
                ->assertSee('© 2020');
        });
    }

    public function test_view_index_publishers_in_vietnamese()
    {
        $this->browse(function (Browser $browser) {
            $user = User::all()->first();
            $browser->loginAs($user)
                ->visit('/')
                ->clickLink('Vietnamese')
                ->visit('/admin/publishers')
                ->assertSee($user->name)
                ->assertSee('Chức vụ')
                ->assertSee('Quản lý người dùng')
                ->assertSee('Yêu cầu')
                ->assertSee('Nhà xuất bản')
                ->assertSee('Danh mục')
                ->assertSee('Tác giả')
                ->assertSee('Sách')
                ->assertSee('Quản lý sách đã bị xóa')
                ->assertSee('Quản lý nhà quất bản')
                ->assertSee('Danh sách nhà xuất bản')
                ->assertSee('Thêm mới')
                ->assertSee('Nhà xuất bản')
                ->assertSee('Logo')
                ->assertSee('Email')
                ->assertSee('Số điện thoại')
                ->assertSee('Địa chỉ')
                ->assertSee('Hành động');
        });
    }

    public function test_edit_publishers()
    {
        $this->browse(function (Browser $browser) {
            $user = User::all()->first();
            $publishers = Publisher::find(6);
            $browser->loginAs($user)
                ->visit('admin/publishers')
                ->click('.fa-pencil')
                ->visit('admin/publishers/' . $publishers->id . '/edit')
                ->assertSee($user->name);
        });
    }

    public function test_delete_publishers()
    {
        $this->browse(function (Browser $browser) {
            $user = User::all()->first();
            $publishers = Publisher::find(6);
            $browser->loginAs($user)
                ->visit('admin/publishers')
                ->click('.fa-trash')
                ->click('.confirm')
                ->visit('/admin/publishers/' . $publishers->id)
                ->assertPathIs('/admin/publishers/' . $publishers->id);
        });
    }

    public function test_not_delete_publishers()
    {
        $this->browse(function (Browser $browser) {
            $user = User::all()->first();
            $publishers = Publisher::find(6);
            $browser->loginAs($user)
                ->visit('admin/publishers')
                ->click('.fa-trash')
                ->click('.cancel')
                ->click('.confirm')
                ->assertPathIs('/admin/publishers');
        });
    }

    public function test_export_publishers()
    {
        $this->browse(function (Browser $browser) {
            $user = User::all()->first();
            $publishers = Publisher::all();
            $browser->loginAs($user)
                ->visit('admin/publishers')
                ->clickLink('Export')
                ->assertPathIs('/admin/publishers');
        });
    }

    public function test_action_logout()
    {
        $this->browse(function ($browser) {
            $user = User::all()->first();
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.dropdown-toggle')
                ->click('.pull-right form .btn-flat')
                ->assertPathIs('/');
        });
    }

    public function test_action_roles_manager()
    {
        $this->browse(function ($browser) {
            $user = User::all()->first();
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-graduation-cap')
                ->assertPathIs('/admin/roles');
        });
    }

    public function test_action_users_manager()
    {
        $this->browse(function ($browser) {
            $user = User::all()->first();
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-users')
                ->assertPathIs('/admin/users');
        });
    }

    public function test_action_users_requests_manager()
    {
        $this->browse(function ($browser) {
            $user = User::all()->first();
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-shopping-cart')
                ->assertPathIs('/admin/request');
        });
    }

    public function test_action_users_categories_manager()
    {
        $this->browse(function ($browser) {
            $user = User::all()->first();
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-tags')
                ->assertPathIs('/admin/categories');
        });
    }

    public function test_action_users_authors_manager()
    {
        $this->browse(function ($browser) {
            $user = User::all()->first();
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-address-book')
                ->assertPathIs('/admin/authors');
        });
    }

    public function test_action_users_books_manager()
    {
        $this->browse(function ($browser) {
            $user = User::all()->first();
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-book')
                ->assertPathIs('/admin/books');
        });
    }

    public function test_action_users_books_ban_manager()
    {
        $this->browse(function ($browser) {
            $user = User::all()->first();
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-ban')
                ->assertPathIs('/admin/book-delete');
        });
    }
}
