<?php

namespace Inz\Base\Traits;

/**
 * Provides a set of methods to deal with trashed records of a certain table, be sure that
 * you are using soft delete trait in the appropriate model class.
 */
trait TrashedOperations
{
    /**
     * Fetches all column's or specific ones data of soft-deleted records.
     *
     * @param array $cols
     *
     * @return Collection
     */
    public function allTrashed($cols = ['*'])
    {
        return $this->model->onlyTrashed()->all($cols);
    }
    /**
     * Fetches all column's or specific ones data of both existing & soft-deleted records.
     *
     * @param array $cols
     *
     * @return Collection
     */
    public function allWithTrashed($cols = ['*'])
    {
        return $this->model->withTrashed()->all($cols);
    }
    /**
     * Fetches the first trashed row's data.
     *
     * @return Model|null
     */
    public function firstTrashed()
    {
        return $this->model->onlyTrashed()->first();
    }
    /**
     * Fetches a record based on the passed id from soft-deleted records only.
     *
     * @param mixed $id
     *
     * @return Model|null
     */
    public function findTrashed($id)
    {
        return $this->model->onlyTrashed()->find($id);
    }
    /**
     * Fetches a record based on the passed id in all table's records.
     *
     * @param mixed $id
     *
     * @return Model|null
     */
    public function findWithTrashed($id)
    {
        return $this->model->withTrashed()->find($id);
    }

    /**
     * Returns the count of soft-deleted records only.
     *
     * @return int
     */
    public function countTrashed()
    {
        return $this->model->onlyTrashed()->count();
    }

    /**
     * Returns the count of all records.
     *
     * @return int
     */
    public function countWithTrashed()
    {
        return $this->model->withTrashed()->count();
    }

    /**
     * Restores a soft-deleted record of the given id, it will
     * look for the record in trashed ones only., if the
     * record wasn't found, it will return **false**.
     *
     * @param mixed $id
     *
     * @return bool
     */
    public function restore($id)
    {
        $temp = $this->findTrashed($id);

        if (is_null($temp)) {
            return false;
        }

        return $temp->restore();
    }

    /**
     * Permanently erase a record of the given id, it will
     * look for the record in all the whole table's, if
     * the record wasn't found, it will return **false**.
     *
     * @param mixed $id
     *
     * @return bool
     */
    public function erase($id)
    {
        $temp = $this->findWithTrashed($id);

        if (is_null($temp)) {
            return false;
        }

        return $temp->forceDelete($id);
    }
}
