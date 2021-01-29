<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function getAll();

    public function find($id);

    public function create($attributes = []);

    public function update($id, $attributes = []);

    public function destroy($id);

    /**
     * Lấy dữ liệu theo phương thức load
     *
     * @param collection $collection
     * @param string $relation
     * @return void
     */
    public function load($collection, $relation);
}
