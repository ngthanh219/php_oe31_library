<?php

namespace App\Repositories\Publisher;

use App\Models\Publisher;
use App\Repositories\BaseRepository;
use Maatwebsite\Excel\Facades\Excel;

class PublisherRepository extends BaseRepository implements PublisherRepositoryInterface
{
    public function getModel()
    {
        return Publisher::class;
    }

    /**
     * loadBook
     * Lấy ra các sách của một publisher
     * @param int $id
     * @return void
     */
    public function loadBook($id)
    {
        return Publisher::find($id)->load('books');
    }

    /**
     * Export
     * Export thông tin của publisher
     * @param collection $model
     * @param string $nameFile
     * @return void
     */
    public function export($model, $nameFile)
    {
        return Excel::download($model, $nameFile);
    }
}
