<?php

namespace App\Repositories\Publisher;

use App\Repositories\RepositoryInterface;

interface PublisherRepositoryInterface extends RepositoryInterface
{
    /**
     * loadBook
     * Lấy ra các sách của một publisher
     * @param int $id
     * @return void
     */
    public function loadBook($id);

    /**
     * export
     * Export thông tin của publisher
     * @param collection $model
     * @param string $nameFile
     * @return void
     */
    public function export($model, $nameFile);
}
