<?php

namespace App\Repositories;

use App\Models\WaecRemittance;

class WaecRemittanceRepository extends BaseRepository
{
    public function __construct(WaecRemittance $model)
    {
        parent::__construct($model);
    }

    /**
     * Get remittances for a school with pagination.
     */
    public function getBySchool(int $schoolId, int $perPage = 20, array $relations = [])
    {
        $query = $this->model->forSchool($schoolId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find by batch reference.
     */
    public function findByBatchReference(string $reference)
    {
        return $this->model->where('batch_reference', $reference)->first();
    }
}
