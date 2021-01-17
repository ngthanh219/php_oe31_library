<?php

namespace App\Repositories\Author;

interface AuthorRepositoryInterface
{
    public function getRelatedBook($id);
}
