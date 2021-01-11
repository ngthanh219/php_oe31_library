<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer([
            'client.detail_book',
            'client.category',
            'client.category_book',
            'client.home',
            'client.modules.trending',
        ], function ($view) {
            $view->with([
                'categories' => Category::with('children')->where('parent_id', config('category.parent_id'))->get(),
                'authors' => Author::take(config('pagination.limit_author'))->get(),
                'newBooks' => Book::with('author')->orderBy('id', 'DESC')->take(config('pagination.limit'))->get(),
                'likeBooks' => Book::withCount(['likes' => function (Builder $query) {
                    $query->where('status', config('like.liked'));
                }])->having('likes_count', '<>', config('like.liked'))->orderBy('likes_count', 'desc')->take(config('pagination.limit'))->get(),
            ]);
        });
    }
}
