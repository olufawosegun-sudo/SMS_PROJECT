<?php

namespace App\Repositories;

use App\Models\Payment;
use Carbon\Carbon;

class PaymentRepository extends BaseRepository
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    /**
     * Get payments by school with pagination
     */
    public function getBySchool(int $schoolId, int $perPage = 20, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get payments by student
     */
    public function getByStudent(int $studentId, array $relations = [])
    {
        $query = $this->model->where('student_id', $studentId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Get payments by invoice
     */
    public function getByInvoice(int $invoiceId, array $relations = [])
    {
        $query = $this->model->where('invoice_id', $invoiceId);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get payments by date range
     */
    public function getByDateRange(int $schoolId, Carbon $startDate, Carbon $endDate, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId)
            ->whereBetween('payment_date', [$startDate, $endDate]);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get payment statistics by school
     */
    public function getStatsBySchool(int $schoolId, ?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        $query = $this->model->where('school_id', $schoolId);

        if ($startDate && $endDate) {
            $query->whereBetween('payment_date', [$startDate, $endDate]);
        }

        return [
            'total_payments' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'confirmed_amount' => $query->where('status', 'confirmed')->sum('amount'),
            'pending_amount' => $query->where('status', 'pending')->sum('amount'),
            'failed_amount' => $query->where('status', 'failed')->sum('amount'),
            'by_method' => $query->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('payment_method')
                ->get(),
        ];
    }

    /**
     * Get payments by payment method
     */
    public function getByPaymentMethod(int $schoolId, string $method, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId)
            ->where('payment_method', $method);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get payments by status
     */
    public function getByStatus(int $schoolId, string $status, array $relations = [])
    {
        $query = $this->model->where('school_id', $schoolId)
            ->where('status', $status);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Get pending payments
     */
    public function getPendingPayments(int $schoolId, array $relations = [])
    {
        return $this->getByStatus($schoolId, 'pending', $relations);
    }

    /**
     * Find payment by reference
     */
    public function findByReference(string $reference, array $relations = [])
    {
        $query = $this->model->where('reference', $reference);

        if (! empty($relations)) {
            $query->with($relations);
        }

        return $query->first();
    }

    /**
     * Get total amount paid by student
     */
    public function getTotalPaidByStudent(int $studentId, ?int $sessionId = null, ?int $termId = null)
    {
        $query = $this->model->where('student_id', $studentId)
            ->where('status', 'confirmed');

        if ($sessionId) {
            $query->where('academic_session_id', $sessionId);
        }

        if ($termId) {
            $query->where('academic_term_id', $termId);
        }

        return $query->sum('amount');
    }
}
