<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function getModel()
    {
        return Category::class;
    }

    public function getParentOrderBy()
    {
        return $this->model->where('parent_id', config('category.parent_id'))
            ->orderBy('id', 'DESC')
            ->paginate(config('pagination.limit_page'));
    }

    public function getParentAll() 
    {
        return  $this->model->where('parent_id', config('category.parent_id'))->get();
    }
}
