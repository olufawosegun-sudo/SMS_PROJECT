<?php

namespace App\Services;

use App\Models\WaecCandidate;
use App\Models\WaecPayment;
use App\Repositories\WaecCandidateRepository;
use App\Repositories\WaecPaymentRepository;
use Carbon\Carbon;

class WaecReportService
{
    protected $candidateRepository;

    protected $paymentRepository;

    public function __construct(
        WaecCandidateRepository $candidateRepository,
        WaecPaymentRepository $paymentRepository
    ) {
        $this->candidateRepository = $candidateRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Get comprehensive financial summary.
     */
    public function getFinancialSummary(int $schoolId, ?int $sessionId = null, ?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        $candidateStats = $this->candidateRepository->getStatistics($schoolId, $sessionId);
        $paymentStats = $this->paymentRepository->getStatistics($schoolId, $sessionId, $startDate, $endDate);

        $collectionRate = $candidateStats['total_expected'] > 0
            ? round(($candidateStats['total_paid'] / $candidateStats['total_expected']) * 100, 2)
            : 0;

        return [
            'candidates' => $candidateStats,
            'payments' => $paymentStats,
            'collection_rate' => $collectionRate,
            'outstanding_balance' => $candidateStats['total_balance'],
        ];
    }

    /**
     * Get payments grouped by session.
     */
    public function getPaymentsBySession(int $schoolId)
    {
        return WaecPayment::forSchool($schoolId)
            ->join('waec_candidates', 'waec_payments.candidate_id', '=', 'waec_candidates.id')
            ->join('academic_sessions', 'waec_candidates.session_id', '=', 'academic_sessions.id')
            ->selectRaw('academic_sessions.id, academic_sessions.name, COUNT(waec_payments.id) as payment_count, SUM(waec_payments.amount) as total_amount')
            ->where('waec_payments.status', 'approved')
            ->groupBy('academic_sessions.id', 'academic_sessions.name')
            ->orderBy('academic_sessions.name', 'desc')
            ->get();
    }

    /**
     * Get payments grouped by class.
     */
    public function getPaymentsByClass(int $schoolId, ?int $sessionId = null)
    {
        $query = WaecPayment::forSchool($schoolId)
            ->join('waec_candidates', 'waec_payments.candidate_id', '=', 'waec_candidates.id')
            ->join('classes', 'waec_candidates.class_id', '=', 'classes.id')
            ->selectRaw('classes.id, classes.name, COUNT(waec_payments.id) as payment_count, SUM(waec_payments.amount) as total_amount')
            ->where('waec_payments.status', 'approved')
            ->groupBy('classes.id', 'classes.name')
            ->orderBy('classes.name');

        if ($sessionId) {
            $query->where('waec_candidates.session_id', $sessionId);
        }

        return $query->get();
    }

    /**
     * Get payments grouped by payment method.
     */
    public function getPaymentsByMethod(int $schoolId, ?int $sessionId = null)
    {
        $query = WaecPayment::forSchool($schoolId)
            ->selectRaw('payment_method, COUNT(*) as payment_count, SUM(amount) as total_amount')
            ->where('status', 'approved')
            ->groupBy('payment_method');

        if ($sessionId) {
            $query->whereHas('candidate', function ($q) use ($sessionId) {
                $q->where('session_id', $sessionId);
            });
        }

        return $query->get();
    }

    /**
     * Get payment trends over time.
     */
    public function getPaymentTrends(int $schoolId, ?int $sessionId = null, int $days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        $query = WaecPayment::forSchool($schoolId)
            ->selectRaw('DATE(payment_date) as date, COUNT(*) as payment_count, SUM(amount) as total_amount')
            ->where('status', 'approved')
            ->where('payment_date', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date');

        if ($sessionId) {
            $query->whereHas('candidate', function ($q) use ($sessionId) {
                $q->where('session_id', $sessionId);
            });
        }

        return $query->get();
    }

    /**
     * Get candidates by payment status breakdown.
     */
    public function getCandidatesByPaymentStatus(int $schoolId, ?int $sessionId = null)
    {
        $query = WaecCandidate::forSchool($schoolId)
            ->selectRaw('payment_status, COUNT(*) as count, SUM(total_fee) as total_fee, SUM(amount_paid) as total_paid, SUM(balance) as total_balance')
            ->groupBy('payment_status');

        if ($sessionId) {
            $query->forSession($sessionId);
        }

        return $query->get();
    }

    /**
     * Get top paying classes.
     */
    public function getTopPayingClasses(int $schoolId, ?int $sessionId = null, int $limit = 10)
    {
        $query = WaecCandidate::forSchool($schoolId)
            ->join('classes', 'waec_candidates.class_id', '=', 'classes.id')
            ->selectRaw('classes.id, classes.name, COUNT(waec_candidates.id) as candidate_count, SUM(waec_candidates.amount_paid) as total_paid')
            ->groupBy('classes.id', 'classes.name')
            ->orderByDesc('total_paid')
            ->limit($limit);

        if ($sessionId) {
            $query->where('waec_candidates.session_id', $sessionId);
        }

        return $query->get();
    }

    /**
     * Get recent payment activities.
     */
    public function getRecentActivities(int $schoolId, int $limit = 20)
    {
        return $this->paymentRepository->getRecentPayments(
            $schoolId,
            $limit,
            ['candidate.student.user', 'student.user', 'submitter', 'approver']
        );
    }

    /**
     * Export payment data for Excel/CSV.
     */
    public function exportPaymentData(int $schoolId, array $filters = [])
    {
        $query = WaecPayment::forSchool($schoolId)
            ->with(['candidate.student.user', 'candidate.session', 'candidate.schoolClass', 'guardian.user', 'approver']);

        // Apply filters
        if (! empty($filters['session_id'])) {
            $query->whereHas('candidate', function ($q) use ($filters) {
                $q->where('session_id', $filters['session_id']);
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->whereBetween('payment_date', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->orderBy('payment_date', 'desc')->get();
    }

    /**
     * Get payment approval statistics.
     */
    public function getApprovalStatistics(int $schoolId, ?int $sessionId = null)
    {
        $query = WaecPayment::forSchool($schoolId);

        if ($sessionId) {
            $query->whereHas('candidate', function ($q) use ($sessionId) {
                $q->where('session_id', $sessionId);
            });
        }

        return [
            'total' => $query->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'submitted' => (clone $query)->whereIn('status', ['submitted', 'under_review'])->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
            'approval_rate' => $query->count() > 0
                ? round(((clone $query)->where('status', 'approved')->count() / $query->count()) * 100, 2)
                : 0,
        ];
    }
}
