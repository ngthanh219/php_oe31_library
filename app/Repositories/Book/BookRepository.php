<?php

namespace App\Repositories\Book;

use App\Models\Book;
use App\Repositories\BaseRepository;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    public function getModel()
    {
        return Book::class;
    }

    public function getVisibleBook()
    {
        return $this->model->with('categories')->orderBy('id', 'desc')->where('status', config('book.visible'))->paginate(config('pagination.limit'));
    }

    public function getDetailBook($id, $userId)
    {
        return $this->model->findOrFail($id)->load([
            'publisher',
            'likes.user',
            'categories.books' => function ($query) {
                $query->inRandomOrder()->get()->take(config('pagination.limit'));
            },
            'rates' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            },
        ]);
    }

    public function searchBookVisible($key)
    {
        return $this->model->where('name', 'LIKE', '%' . $key . '%')
            ->where('status', config('book.visible'))
            ->orderBy('id', 'DESC')
            ->get();
    }
}
