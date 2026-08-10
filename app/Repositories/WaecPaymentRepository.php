<?php

namespace App\Repositories;

use App\Models\WaecPayment;
use Carbon\Carbon;

class WaecPaymentRepository extends BaseRepository
{
    public function __construct(WaecPayment $model)
    {
        parent::__construct($model);
    }

    /**
     * Get payments for a school with pagination.
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
     * Get pending payments for a school.
     */
    public function getPendingPayments(int $schoolId, array $relations = [])
    {
        $query = $this->model
            ->forSchool($schoolId)
            ->submitted();

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest('submitted_at')->get();
    }

    /**
     * Get approved payments.
     */
    public function getApprovedPayments(int $schoolId, array $relations = [])
    {
        $query = $this->model
            ->forSchool($schoolId)
            ->approved();

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest('approved_at')->get();
    }

    /**
     * Get rejected payments.
     */
    public function getRejectedPayments(int $schoolId, array $relations = [])
    {
        $query = $this->model
            ->forSchool($schoolId)
            ->rejected();

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest('rejected_at')->get();
    }

    /**
     * Get payments by candidate.
     */
    public function getByCandidate(int $candidateId, array $relations = [])
    {
        $query = $this->model->forCandidate($candidateId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Get payments by student.
     */
    public function getByStudent(int $studentId, array $relations = [])
    {
        $query = $this->model->forStudent($studentId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Find payment by reference.
     */
    public function findByReference(string $reference, array $relations = [])
    {
        $query = $this->model->where('payment_reference', $reference);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Find payment by receipt number.
     */
    public function findByReceiptNumber(string $receiptNumber, array $relations = [])
    {
        $query = $this->model->where('receipt_number', $receiptNumber);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Get payments within date range.
     */
    public function getByDateRange(int $schoolId, Carbon $startDate, Carbon $endDate, array $relations = [])
    {
        $query = $this->model
            ->forSchool($schoolId)
            ->whereBetween('payment_date', [$startDate, $endDate]);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest('payment_date')->get();
    }

    /**
     * Get payment statistics.
     */
    public function getStatistics(int $schoolId, ?int $sessionId = null, ?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        $query = $this->model->forSchool($schoolId);

        if ($sessionId) {
            $query->whereHas('candidate', function ($q) use ($sessionId) {
                $q->where('session_id', $sessionId);
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('payment_date', [$startDate, $endDate]);
        }

        return [
            'total_payments' => $query->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'submitted' => (clone $query)->whereIn('status', ['submitted', 'under_review'])->count(),
            'approved' => (clone $query)->approved()->count(),
            'rejected' => (clone $query)->rejected()->count(),
            'total_amount' => (clone $query)->sum('amount'),
            'approved_amount' => (clone $query)->approved()->sum('amount'),
            'pending_amount' => (clone $query)->whereIn('status', ['submitted', 'under_review'])->sum('amount'),
        ];
    }

    /**
     * Get payments by payment method.
     */
    public function getByPaymentMethod(int $schoolId, array $relations = [])
    {
        return $this->model
            ->forSchool($schoolId)
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();
    }

    /**
     * Get payments grouped by status.
     */
    public function getByStatus(int $schoolId)
    {
        return $this->model
            ->forSchool($schoolId)
            ->selectRaw('status, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('status')
            ->get();
    }

    /**
     * Get recent payments.
     */
    public function getRecentPayments(int $schoolId, int $limit = 10, array $relations = [])
    {
        $query = $this->model->forSchool($schoolId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->limit($limit)->get();
    }
}
