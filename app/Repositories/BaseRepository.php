<?php

namespace App\Repositories;

use App\Repositories\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    public function getAll()
    {
        return $this->model->orderBy('id', 'DESC')->paginate(config('pagination.limit_page'));
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    public function update($id, $attributes = [])
    {
        $result = $this->find($id);
        if ($result) {
            return $result->update($attributes);
        }

        return false;
    }

    public function destroy($id)
    {
        $result = $this->find($id);

        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }

    public function getAllItem()
    {
        return $this->model->get();
    }

    public function load($collection, $relation = [])
    {
        return $collection->load($relation);
    }

    public function with($relation = [])
    {
        return $this->model->with($relation)->get();
    }

    public function attach($collection, $relation, $param = [])
    {
        return $collection->$relation()->attach($param);
    }

    public function sync($collection, $relation, $items = [])
    {
        return $collection->$relation()->sync($items);
    }

    public function withFind($id, $relation = [])
    {
        return $this->model->with($relation)->find($id);
    }

    public function search($key)
    {
        return $this->model->where('name', 'LIKE', '%' . $request->key . '%', )->orderBy('id', 'DESC')->get();
    }

    public function getSoftDelete()
    {
        return $this->model->onlyTrashed()->paginate(config('pagination.limit_page'));
    }

    public function findSoftDelete($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    public function restoreSoftDelete($id)
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    public function hardDelete($id)
    {
        return $this->model->withTrashed()->findOrFail($id)->forceDelete();
    }
}
