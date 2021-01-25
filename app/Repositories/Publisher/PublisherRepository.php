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

    public function loadBook($id)
    {
        return Publisher::find($id)->load('books');
    }

    public function export($model, $nameFile)
    {
        return Excel::download($model, $nameFile);
    }
}
