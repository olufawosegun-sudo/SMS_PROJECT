<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Notification;
use App\Models\User;
use App\Models\WaecCandidate;
use App\Models\WaecPayment;
use App\Models\WaecRemittance;
use App\Repositories\WaecRemittanceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WaecRemittanceService
{
    protected $remittanceRepository;

    public function __construct(WaecRemittanceRepository $remittanceRepository)
    {
        $this->remittanceRepository = $remittanceRepository;
    }

    /**
     * Get financial summary of collected fees vs remitted to WAEC.
     */
    public function getSummary(int $schoolId, ?int $sessionId = null): array
    {
        $paymentsQuery = WaecPayment::where('school_id', $schoolId)->where('status', 'approved');
        $remittancesQuery = WaecRemittance::where('school_id', $schoolId);
        $candidatesQuery = WaecCandidate::where('school_id', $schoolId)->where('status', '!=', 'cancelled');

        if ($sessionId) {
            $paymentsQuery->whereHas('candidate', fn ($q) => $q->where('session_id', $sessionId));
            $remittancesQuery->where('session_id', $sessionId);
            $candidatesQuery->where('session_id', $sessionId);
        }

        $totalCollected = (float) $paymentsQuery->sum('amount');
        $totalRemitted = (float) $remittancesQuery->sum('total_amount');
        $unremittedBalance = max(0, $totalCollected - $totalRemitted);

        $totalCandidates = $candidatesQuery->count();
        $paidCandidatesCount = (clone $candidatesQuery)->where('payment_status', 'paid')->count();
        $remittedCandidatesCount = (clone $candidatesQuery)->whereNotNull('waec_remittance_id')->count();

        return [
            'total_collected' => $totalCollected,
            'total_remitted' => $totalRemitted,
            'unremitted_balance' => $unremittedBalance,
            'total_candidates' => $totalCandidates,
            'paid_candidates' => $paidCandidatesCount,
            'remitted_candidates' => $remittedCandidatesCount,
            'pending_remittance_candidates' => max(0, $paidCandidatesCount - $remittedCandidatesCount),
        ];
    }

    /**
     * Get eligible candidates ready for remittance to WAEC.
     */
    public function getEligibleCandidates(int $schoolId, ?int $sessionId = null)
    {
        $query = WaecCandidate::forSchool($schoolId)
            ->whereIn('payment_status', ['paid', 'partial'])
            ->whereNull('waec_remittance_id')
            ->where('status', '!=', 'cancelled')
            ->with(['student.user', 'schoolClass', 'session']);

        if ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->get();
    }

    /**
     * Create a WAEC remittance record.
     */
    public function createRemittance(array $data, int $schoolId, int $userId): WaecRemittance
    {
        return DB::transaction(function () use ($data, $schoolId, $userId) {
            $candidateIds = $data['candidate_ids'] ?? [];
            if (empty($candidateIds)) {
                throw new \Exception('Please select at least one student candidate for WAEC remittance.');
            }

            // Verify candidates belong to the school and are eligible
            $candidates = WaecCandidate::where('school_id', $schoolId)
                ->whereIn('id', $candidateIds)
                ->whereNull('waec_remittance_id')
                ->get();

            if ($candidates->isEmpty()) {
                throw new \Exception('No valid unremitted candidates selected.');
            }

            $batchRef = 'WAEC-REM-'.date('Ymd').'-'.strtoupper(Str::random(5));
            $calculatedAmount = $candidates->sum('amount_paid');

            $remittance = $this->remittanceRepository->create([
                'school_id' => $schoolId,
                'session_id' => $data['session_id'],
                'batch_reference' => $batchRef,
                'waec_transaction_reference' => $data['waec_transaction_reference'] ?? null,
                'total_candidates_count' => $candidates->count(),
                'total_amount' => $data['total_amount'] ?? $calculatedAmount,
                'payment_method' => $data['payment_method'] ?? 'bank_transfer',
                'bank_name' => $data['bank_name'] ?? null,
                'payment_date' => $data['payment_date'] ?? now()->toDateString(),
                'proof_document' => $data['proof_document'] ?? null,
                'status' => 'completed',
                'notes' => $data['notes'] ?? null,
                'remitted_by' => $userId,
                'remitted_at' => now(),
            ]);

            // Link candidates and update status
            foreach ($candidates as $candidate) {
                $candidate->update([
                    'waec_remittance_id' => $remittance->id,
                    'status' => 'exam_ready',
                ]);
            }

            // Auto-record in Expense ledger so exam fees are tracked in school Financial Reports
            $remitCategory = ExpenseCategory::firstOrCreate([
                'school_id' => $schoolId,
                'name' => 'WAEC Examination Fees & Remittance',
            ]);

            Expense::create([
                'school_id' => $schoolId,
                'expense_category_id' => $remitCategory->id,
                'title' => 'WAEC Remittance: '.$batchRef.' ('.$candidates->count().' candidates)',
                'amount' => $data['total_amount'] ?? $calculatedAmount,
                'expense_date' => $data['payment_date'] ?? now()->toDateString(),
                'description' => 'Official remittance payment to WAEC for '.$candidates->count().' candidates. Ref: '.($data['waec_transaction_reference'] ?? $batchRef),
                'approved_by' => $userId,
            ]);

            // Notify Owner and Principal
            $this->notifyAdmins($remittance);

            return $remittance->load(['session', 'remitter', 'candidates.student.user']);
        });
    }

    /**
     * Get paginated remittances for a school.
     */
    public function getSchoolRemittances(int $schoolId, int $perPage = 20)
    {
        return WaecRemittance::forSchool($schoolId)
            ->with(['session', 'remitter', 'candidates'])
            ->latest('payment_date')
            ->paginate($perPage);
    }

    /**
     * Find remittance details.
     */
    public function findRemittance(int $id, int $schoolId): WaecRemittance
    {
        $remittance = WaecRemittance::with(['session', 'remitter', 'candidates.student.user', 'candidates.schoolClass', 'candidates.payments'])
            ->findOrFail($id);

        if ($remittance->school_id !== $schoolId) {
            throw new \Exception('Unauthorized access to this remittance record.');
        }

        return $remittance;
    }

    /**
     * Notify Admins (Owner & Principal) about WAEC Remittance.
     */
    protected function notifyAdmins(WaecRemittance $remittance)
    {
        $admins = User::where('school_id', $remittance->school_id)
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['Owner', 'Principal', 'Vice Principal']);
            })
            ->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'WAEC Remittance Payment Recorded',
                'message' => "WAEC remittance {$remittance->batch_reference} for {$remittance->total_candidates_count} candidates recorded.",
                'type' => 'waec_remittance',
                'link' => route(
                    Auth::user()->role->name === 'Owner' ? 'owner.waec.remittance.show' : 'principal.waec.remittance.show',
                    $remittance->id
                ),
                'is_read' => false,
            ]);
        }
    }
}
