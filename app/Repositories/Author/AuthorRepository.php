<?php

namespace App\Repositories\Author;

use App\Models\Author;
use App\Repositories\BaseRepository;

class AuthorRepository extends BaseRepository implements AuthorRepositoryInterface
{
    public function getModel()
    {
        return Author::class;
    }

    public function getRelatedBook($id)
    {
        if ($id) {
            $authors = Author::with('books')->find($id);

            return $authors;
        }

        return false;
    }
}
