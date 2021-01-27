<?php

namespace App\Repositories\Category;

use App\Repositories\RepositoryInterface;

interface CategoryRepositoryInterface extends RepositoryInterface
{
    /**
     * Lấy ra toàn bộ category parent theo OrderyBy và có sử dụng paginate
     *
     * @return void
     */
    public function getParentOrderBy();
    
    /**
     * Lấy ra toàn bộ category parent mà không kèm theo OrderBy
     *
     * @return void
     */
    public function getParentAll();

    /**
     * lấy ra toàn bộ category children với lấy ra quan hệ của 'books'
     *
     * @return void
     */
    public function getChildren();
}
