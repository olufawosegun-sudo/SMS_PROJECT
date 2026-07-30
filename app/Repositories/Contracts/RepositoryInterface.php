<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    /**
     * Get all records
     */
    public function all(array $columns = ['*'], array $relations = []);

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []);

    /**
     * Find a record by ID
     */
    public function find(int $id, array $columns = ['*'], array $relations = []);

    /**
     * Find a record by specific column
     */
    public function findBy(string $column, $value, array $columns = ['*'], array $relations = []);

    /**
     * Find records by specific column
     */
    public function findAllBy(string $column, $value, array $columns = ['*'], array $relations = []);

    /**
     * Create a new record
     */
    public function create(array $data);

    /**
     * Update a record
     */
    public function update(int $id, array $data);

    /**
     * Delete a record
     */
    public function delete(int $id);

    /**
     * Get records with specific conditions
     */
    public function where(array $conditions, array $columns = ['*'], array $relations = []);

    /**
     * Count records
     */
    public function count(array $conditions = []);
}
