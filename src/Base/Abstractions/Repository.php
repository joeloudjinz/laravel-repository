<?php

namespace Inz\Base\Abstractions;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Inz\Base\Interfaces\RepositoryInterface;
use Inz\Exceptions\NotEloquentModelException;
use Illuminate\Pagination\LengthAwarePaginator;
use Inz\Exceptions\MissingModelMethodException;

abstract class Repository implements RepositoryInterface
{
    /**
     * Model instance related to current repository.
     *
     * @var Model
     */
    protected $model;
    /**
     * Attributes of the model.
     *
     * @var array
     */
    protected $attributes;

    public function __construct()
    {
        $this->model = $this->resolveModel();
        $this->attributes = $this->model->attributesToArray();
    }

    abstract public function model();

    /**
     * Fetches all columns data, or specific ones.
     *
     * @param array $cols
     *
     * @return Collection
     */
    public function all($cols = ['*'])
    {
        return $this->model->all($cols);
    }

    /**
     * Fetches the first row's data.
     *
     * @return Model|null
     */
    public function first()
    {
        return $this->model->first();
    }

    /**
     * Fetches a record based on the passed id.
     *
     * @param mixed $id
     *
     * @return Collection|null
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Fetches records based on the passed column name & it's value.
     *
     * @param String $column
     * @param mixed $value
     *
     * @return Collection
     */
    public function findWhere(String $column, $value)
    {
        return $this->model->where($column, $value)->get();
    }

    /**
     * Fetches the first record based on the passed column name & it's value.
     *
     * @param String $column
     * @param mixed $value
     *
     * @return Collection|null
     */
    public function findFirstWhere(String $column, $value)
    {
        return $this->model->where($column, $value)->first();
    }

    /**
     * Fetches paginated records.
     *
     * @param int $count
     *
     * @return LengthAwarePaginator
     */
    public function paginate(int $count = 10)
    {
        return $this->model->paginate($count);
    }

    /**
     * Saves a new record based on the passed set of attributes
     *
     * @param array $data a key value pair where the key is the attribute's name.
     *
     * @return bool
     */
    public function save(array $data)
    {
        $temp = app()->make($this->model());
        return $this->persist($temp, $data);
    }

    /**
     * Updates a record based on the passed set of attributes.
     *
     * @param mixed $id
     * @param array $data
     *
     * @return bool
     */
    public function update($id, array $data)
    {
        $temp = $this->find($id);

        if (is_null($temp)) {
            return false;
        }

        return $this->persist($temp, $data);
    }

    /**
     * Deletes a record of the given id.
     *
     * @param mixed $id
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * Instantiating the model object.
     *
     * @throws MissingModelMethodException
     * @throws NotEloquentModelException
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function resolveModel()
    {
        if (!method_exists($this, 'model')) {
            throw new MissingModelMethodException();
        }

        $model = app()->make($this->model());

        if (!$model instanceof Model) {
            throw new NotEloquentModelException($this->model());
        }

        return $model;
    }

    /**
     * Inserts the new values of the attributes in data array and persist the new model.
     *
     * @param Model $model
     * @param array $data
     *
     * @return bool
     */
    private function persist(&$model, array $data)
    {
        foreach ($this->attributes as $attribute) {
            if (Arr::has($data, $attribute)) {
                $model[$attribute] = $data[$attribute];
            }
        }

        return $model->save();
    }

    /**
     * Build pagination.
     *
     * @param Builder  $query
     * @param null|int $paginate
     *
     * @return Collection
     */
    // private function processPagination($query, $paginate)
    // {
    //     return $paginate ? $query->paginate($paginate) : $query->get();
    // }

    // if (!$model) {
    //     throw (new ModelNotFoundException())->setModel(
    //         get_class($this->model->getModel())
    //     );
    // }

    /**
     * {@inheritdoc}
     */
    // public function findWhereLike($columns, $value)
    // {
    //     $query = $this->model;

    //     if (is_string($columns)) {
    //         $columns = [$columns];
    //     }

    //     foreach ($columns as $column) {
    //         $query->orWhere($column, 'like', $value);
    //     }

    //     return $query->get();
    // }
}
