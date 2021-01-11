<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

Auth::routes();

Route::group(['middleware' => 'admin'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function () {
            Route::get('dashboard', 'HomeController@index')->name('dashboard');
            Route::get('publishers/export', 'PublisherController@export')->name('publishers.export');
            Route::resources([
                'publishers' => 'PublisherController',
                'users' => 'UserController',
                'authors' => 'AuthorController',
                'categories' => 'CategoryController',
                'books' => 'BookController',
            ]);
            Route::get('search-user', 'UserController@search')->name('search-user');
            Route::post('api-store-category', 'CategoryController@apiStore')->name('api-store-category');
            Route::get('search-book', 'BookController@search')->name('search-book');
            Route::get('category-popup', 'BookController@catePopup')->name('category-popup');
        });
    });
});

Route::get('/', 'BookController@index')->name('home');
Route::get('category-book/{categoryId}', 'BookController@getCategory')->name('category-book');
Route::get('detail/{book}', 'BookController@getDetailBook')->name('detail');
