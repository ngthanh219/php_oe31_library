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
     * @param array $relation
     * @return void
     */
    public function load($collection, $relation = []);

    /**
     * Lấy ra row với quan hệ, có thể truyền vào id hoặc không
     *
     * @param collection $collection
     * @return void
     */
    public function with($relation = []);

    /**
     * Sử dụng hàm sync để update dư
     *
     * @param collection $collection
     * @param relation $relation
     * @param array $items
     * @return void
     */
    public function sync($collection, $relation, $items = []);

    /**
     * Dùng để lưu dữ liệu vào bảng trung gian "PivotTable"
     *
     * @param collection $collection
     * @param string $relation
     * @param array $param
     * @return void
     */
    public function attach($collection, $relation, $param = []);

    /**
     * Lấy ra toàn bộ row mà của table
     *
     * @return void
     */
    public function getAllItem();

    /**
     * Lấy ra 1 row cùng quan hệ của bảng đó
     *
     * @param int $id
     * @param array $relation
     * @return void
     */
    public function withFind($id, $relation = []);

    /**
     * Tìm kiếm với key truyền vào thường là với "name"
     *
     * @param string $key
     * @return void
     */
    public function search($key);

    /**
     * Lấy ra row bị xóa mềm của table
     *
     * @return void
     */
    public function getSoftDelete();
    
    /**
     * Tìm kiếm row bị xóa mềm
     *
     * @param int $id
     * @return void
     */
    public function findSoftDelete($id);

    /**
     * Restore lại các trường bị xóa mềm
     *
     * @param int $id
     * @return void
     */
    public function restoreSoftDelete($id);

    /**
     * Xóa cứng, xóa ra khỏi cơ sở dữ liệu
     *
     * @param int $id
     * @return void
     */
    public function hardDelete($id);
}
