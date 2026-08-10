<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     */
    public function all(array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->select($columns);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get paginated records
     */
    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->select($columns);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->paginate($perPage);
    }

    /**
     * Find a record by ID
     */
    public function find(int $id, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->select($columns);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->find($id);
    }

    /**
     * Find a record by specific column
     */
    public function findBy(string $column, $value, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->select($columns)->where($column, $value);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Find records by specific column
     */
    public function findAllBy(string $column, $value, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->select($columns)->where($column, $value);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Create a new record
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a record
     */
    public function update(int $id, array $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);

        return $record->fresh();
    }

    /**
     * Delete a record
     */
    public function delete(int $id)
    {
        $record = $this->model->findOrFail($id);

        return $record->delete();
    }

    /**
     * Get records with specific conditions
     */
    public function where(array $conditions, array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->select($columns);

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                // Handle operators like ['>=', 100]
                $query->where($column, $value[0], $value[1]);
            } else {
                $query->where($column, $value);
            }
        }

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Count records
     */
    public function count(array $conditions = [])
    {
        $query = $this->model->query();

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $query->where($column, $value[0], $value[1]);
            } else {
                $query->where($column, $value);
            }
        }

        return $query->count();
    }

    /**
     * Get the model instance
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the model instance
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }
}
