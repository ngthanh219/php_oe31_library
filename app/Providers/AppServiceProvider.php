<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Repositories\Author\AuthorRepository;
use App\Repositories\Author\AuthorRepositoryInterface;
use App\Repositories\Book\BookRepository;
use App\Repositories\Book\BookRepositoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Like\LikeRepository;
use App\Repositories\Like\LikeRepositoryInterface;
use App\Repositories\Publisher\PublisherRepository;
use App\Repositories\Publisher\PublisherRepositoryInterface;
use App\Repositories\Rate\RateRepository;
use App\Repositories\Rate\RateRepositoryInterface;
use App\Repositories\Request\RequestRepository;
use App\Repositories\Request\RequestRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
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
        $this->app->singleton(
            PublisherRepositoryInterface::class,
            PublisherRepository::class,
        );

        $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->singleton(
            AuthorRepositoryInterface::class,
            AuthorRepository::class
        );

        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->singleton(
            BookRepositoryInterface::class,
            BookRepository::class
        );

        $this->app->singleton(
            LikeRepositoryInterface::class,
            LikeRepository::class
        );

        $this->app->singleton(
            RateRepositoryInterface::class,
            RateRepository::class
        );

        $this->app->singleton(
            RequestRepositoryInterface::class,
            RequestRepository::class
        );

        $this->app->singleton(
            RoleRepositoryInterface::class,
            RoleRepository::class
        );

        $this->app->singleton(
            PermissionRepositoryInterface::class,
            PermissionRepository::class
        );

        $this->app->singleton(
            CommentRepositoryInterface::class,
            CommentRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $votes = [
            config('rate.five'),
            config('rate.four'),
            config('rate.three'),
            config('rate.two'),
            config('rate.one'),
        ];
        view()->composer([
            'client.detail_book',
            'client.category',
            'client.category_book',
            'client.home',
            'client.modules.trending',
        ], function ($view) use ($votes) {
            $view->with([
                'categories' => Category::with('children')->where('parent_id', config('category.parent_id'))->get(),
                'authors' => Author::take(config('author.take'))->get(),
                'newBooks' => Book::with('author')->orderBy('id', 'DESC')->take(config('book.take'))->get(),
                'likeBooks' => Book::withCount(['likes' => function (Builder $query) {
                    $query->where('status', config('like.liked'));
                }])->having('likes_count', '<>', config('like.count'))->orderBy('likes_count', 'desc')->take(config('like.take'))->get(),
                'votes' => $votes,
            ]);
        });
    }
}
