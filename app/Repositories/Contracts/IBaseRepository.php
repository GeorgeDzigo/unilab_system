<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{

    /**
     * @return mixed
     */
    public function all();

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'));

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * @param $id
     * @return Model|null
     */
    public function find($id): ?Model;

    /**
     * @param $uniqueData
     * @param array $newData
     * @return mixed
     */
    public function updateOrCreate($uniqueData, $newData = []);

    /**
     * @return mixed
     */
    public function getModel();

    /**
     * @param $model
     * @return mixed
     */
    public function setModel($model);

    /**
     * @param $relations
     * @return mixed
     */
    public function with($relations);

    /**
     * @param $filter
     * @return mixed
     */
    public function adminFilter($filter);

}
