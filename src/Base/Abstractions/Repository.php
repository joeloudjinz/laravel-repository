<?php

namespace Inz\Base\Abstractions;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Inz\Base\Interfaces\RepositoryInterface;
use Inz\Exceptions\NotEloquentModelException;
use Inz\Exceptions\TableHasNoColumnsException;
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
    /**
     * Unprocessed columns during insertion & updating.
     *
     * @var array
     */
    protected $excludedColumns = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function __construct()
    {
        $this->model = $this->resolveModel();
        $this->attributes = $this->resolveAttributes();
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
     * @return Model|null
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
     * @param String $operator default is '='
     *
     * @return Collection
     */
    public function findWhere(String $column, $value, String $operator = '=')
    {
        return $this->model->where($column, $operator, $value)->get();
    }

    /**
     * Fetches the first record based on the passed column name & it's value.
     *
     * @param String $column
     * @param mixed $value
     * @param String $operator default is '='
     *
     * @return Model|null
     */
    public function findFirstWhere(String $column, $value, String $operator = '=')
    {
        return $this->model->where($column, $operator, $value)->first();
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
     * Returns the count of records.
     *
     * @param mixed $id
     *
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Returns columns list of the model's table.
     *
     * @return array
     */
    public function getColumns()
    {
        return array_merge($this->attributes, $this->excludedColumns);
    }

    /**
     * Returns excluded columns list from the repository operations.
     *
     * @return array
     */
    public function getExcludedColumns()
    {
        return $this->excludedColumns;
    }

    /**
     * Returns columns list which the repository operates on.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
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
     * Resolves the model's attributes list.
     *
     * @throws TableHasNoColumnsException
     *
     * @return array
     */
    protected function resolveAttributes()
    {
        $columns = collect(Schema::getColumnListing($this->model->getTable()));

        if ($columns->isEmpty()) {
            // if we found that the list of columns of the table related to the model is
            // empty we will throw an exception, this means either the table is missing
            // from the database (not migrated yet) or it doesn't have any column.
            throw new TableHasNoColumnsException($this->model->getTable());
        }

        foreach ($this->excludedColumns as $unwanted) {
            $key = $columns->search($unwanted);
            if (!is_bool($key)) {
                $columns->forget($key);
            }
        }

        return $columns->toArray();
    }

    /**
     * Inserts the new values of the attributes found in data array and persists the new model.
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
}
