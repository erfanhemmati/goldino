<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    /**
     * Get all records.
     * 
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Get paginated records.
     * 
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Create a new record.
     * 
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update a record.
     * 
     * @param array $data
     * @param int $id
     * @return Model
     */
    public function update(array $data, int $id): Model;

    /**
     * Delete a record.
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Find a record by ID.
     * 
     * @param int $id
     * @param array $columns
     * @return Model|null
     */
    public function find(int $id, array $columns = ['*']): ?Model;

    /**
     * Find a record by field.
     * 
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return Model|null
     */
    public function findByField(string $field, mixed $value, array $columns = ['*']): ?Model;

    /**
     * Find records where field matches value.
     * 
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return Collection
     */
    public function findWhere(string $field, mixed $value, array $columns = ['*']): Collection;

    /**
     * Find records based on multiple conditions.
     * 
     * @param array $conditions
     * @param array $columns
     * @return Collection
     */
    public function findWhereMany(array $conditions, array $columns = ['*']): Collection;
} 