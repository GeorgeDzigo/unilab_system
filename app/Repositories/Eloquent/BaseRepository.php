<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IBaseRepository;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements IBaseRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all instances of model
     *
     * @return \Illuminate\Database\Eloquent\Collection|Model[]|mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * update record in the database
     *
     * @param array $data
     * @param $id
     * @return bool
     */
    public function update(array $data, $id)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * Eager load database relationships
     *
     * @param $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * remove record from the database
     *
     * @param $id
     * @return int|mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @param $uniqueData
     * @param array $newData
     * @return mixed
     */
    public function updateOrCreate($uniqueData, $newData = [])
    {
        return $this->model->updateOrCreate($uniqueData, $newData);
    }

    /**
     * Get the associated model
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the associated model
     *
     * @param $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @param $filter
     * @return mixed
     */
    public function adminFilter($filter)
    {
        return $this->model->AdminFilters($filter);
    }

}
