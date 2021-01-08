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
            ]);
        });
    });
});
