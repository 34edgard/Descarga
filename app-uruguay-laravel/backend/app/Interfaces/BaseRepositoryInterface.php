<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Create a new record
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Insert records
     *
     * @param array $attributes
     * @return bool
     */
    public function insert(array $attributes): bool;

    /**
     * Insert and get ID
     *
     * @param array $attributes
     * @return bool
     */
    public function insertGetId(array $attributes): bool;

    /**
     * Update a record
     *
     * @param array $attributes
     * @param int $id
     * @return mixed
     */
    public function update(array $attributes, $id);

    /**
     * Insert or update records
     *
     * @param array $attributes
     * @param array $uniqueBy
     * @param array $update
     * @return bool
     */
    public function upsert(array $attributes, array $uniqueBy, array $update = []): bool;

    /**
     * Get all records
     *
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc');

    /**
     * Find a record by ID
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Find a record by ID or fail
     *
     * @param int $id
     * @return mixed
     */
    public function findOneOrFail(int $id);

    /**
     * Find records by criteria
     *
     * @param array $data
     * @return mixed
     */
    public function findBy(array $data);

    /**
     * Find one record by criteria
     *
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data);

    /**
     * Find one record by criteria or fail
     *
     * @param array $data
     * @return mixed
     */
    public function findOneByOrFail(array $data);


    /**
     * Get paginated records with filters
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function allPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    /**
     * Delete a record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Delete multiple records
     *
     * @param array $ids
     * @return bool
     */
    public function deleteMultiple(array $ids): bool;
}
