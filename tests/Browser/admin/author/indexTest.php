<?php

namespace Tests\Browser\admin\author;

use App\Models\Author;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class indexTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_view_admin_author_Index_in_english()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $author = Author::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->assertSee($user->name)
                ->assertSee(trans('author.author'))
                ->assertSee(trans('role.role'))
                ->assertSee(trans('user.users_manager'))
                ->assertSee(trans('request.request'))
                ->assertSee(trans('publisher.publisher'))
                ->assertSee(trans('category.category'))
                ->assertSee(trans('author.author'))
                ->assertSee(trans('book.book'))
                ->assertSee(trans('book.books_manager_deleted'))
                ->assertSee(trans('author.author_list'))
                ->assertSee(trans('author.add_submit_button'))
                ->assertSee(trans('author.author_name'))
                ->assertSee(trans('author.avatar'))
                ->assertSee(trans('author.description'))
                ->assertSee(trans('author.date_of_born'))
                ->assertSee(trans('author.date_of_death'))
                ->assertSee(trans('author.actions'))
                ->assertSee('SUN *')
                ->assertSee('© 2020');
        });
    }

    public function test_view_admin_author_Index_in_vietnamese()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $author = Author::find(2);
            $browser->loginAs($user)
                ->visit('/')
                ->clickLink(trans('client.vi'))
                ->visit('/admin/authors')
                ->assertSee($user->name)
                ->assertSee('Quản lý tác giả')
                ->assertSee('Chức vụ')
                ->assertSee('Quản lý người dùng')
                ->assertSee('Yêu cầu')
                ->assertSee('Nhà xuất bản')
                ->assertSee('Danh mục')
                ->assertSee('Tác giả')
                ->assertSee('Sách')
                ->assertSee('Quản lý sách đã bị xóa')
                ->assertSee('Danh sách tác giả')
                ->assertSee('Thêm mới')
                ->assertSee('Tên tác giả')
                ->assertSee('Ảnh đại diện')
                ->assertSee('Mô tả')
                ->assertSee('Ngày sinh')
                ->assertSee('Ngày mất')
                ->assertSee('Hành động')
                ->assertSee('SUN *')
                ->assertSee('© 2020');
        });
    }

    public function test_action_edit()
    {
        $this->browse(function ($browser) {
            $author = Author::all()->last();
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-pencil')
                ->assertPathIs('/admin/authors/'.$author->id.'/edit');
        });
    }

    public function test_action_delete_confirm()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-trash')
                ->click('.confirm')
                ->assertPathIs('/admin/authors');
        });
    }

    public function test_action_delete()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-trash')
                ->click('.cancel')
                ->click('.confirm')
                ->assertPathIs('/admin/authors');
        });
    }

    public function test_action_logout()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.dropdown-toggle')
                ->click('@logout')
                ->assertPathIs('/');
        });
    }

    public function test_action_roles_manager()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-graduation-cap')
                ->assertPathIs('/admin/roles');
        });
    }

    public function test_action_users_manager()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-users')
                ->assertPathIs('/admin/users');
        });
    }

    public function test_action_users_requests_manager()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-shopping-cart')
                ->assertPathIs('/admin/request');
        });
    }

    public function test_action_users_categories_manager()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-tags')
                ->assertPathIs('/admin/categories');
        });
    }

    public function test_action_publishers_manager()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-building')
                ->assertPathIs('/admin/publishers');
        });
    }

    public function test_action_users_authors_manager()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-address-book')
                ->assertPathIs('/admin/authors');
        });
    }

    public function test_action_users_books_manager()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-book')
                ->assertPathIs('/admin/books');
        });
    }

    public function test_action_users_books_ban_manager()
    {
        $this->browse(function ($browser) {
            $user = User::find(2);
            $browser->loginAs($user)
                ->visit('/admin/authors')
                ->click('.fa-ban')
                ->assertPathIs('/admin/book-delete');
        });
    }
}
