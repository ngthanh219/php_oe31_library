<?php

namespace App\Repositories\Publisher;
use App\Repositories\RepositoryInterface;

interface PublisherRepositoryInterface extends RepositoryInterface
{
    public function export($model, $nameFile);

    public function loadBook($id);
}
