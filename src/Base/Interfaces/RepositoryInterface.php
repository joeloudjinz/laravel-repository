<?php

namespace Inz\Base\Interfaces;

interface RepositoryInterface
{
    /**
     * Fetches all columns data, or specific ones.
     *
     * @return Collection
     */
    public function all($cols = ['*']);

    /**
     * Fetches the first row's data.
     *
     * @return Model|null
     */
    public function first();

    /**
     * Fetches a record based on the passed id.
     *
     * @param mixed $id
     *
     * @return Collection|null
     */
    public function find($id);

    /**
     * Fetches records based on the passed column name & it's value.
     *
     * @param String $column
     * @param mixed $value
     * @param String $operator default is '='
     *
     * @return Collection|null
     */
    public function findWhere(String $column, $value, String $operator = '=');

    /**
     * Fetches the first record based on the passed column name & it's value.
     *
     * @param String $column
     * @param mixed $value
     * @param String $operator default is '='
     *
     * @return Model|null
     */
    public function findFirstWhere(String $column, $value, String $operator = '=');

    /**
     * Fetches paginated records.
     *
     * @param int $count
     *
     * @return LengthAwarePaginator
     */
    public function paginate(int $count = 10);

    /**
     * Saves a new record based on the passed set of attributes
     *
     * @param array $data a key value pair where the key is the attribute's name.
     *
     * @return bool
     */
    public function save(array $data);

    /**
     * Updates a record based on the passed set of attributes.
     *
     * @param mixed $id
     * @param array $data
     *
     * @return bool
     */
    public function update($id, array $data);

    /**
     * Deletes a record of the given id.
     *
     * @param mixed $id
     *
     * @return bool
     */
    public function delete($id);
}
