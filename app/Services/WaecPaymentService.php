<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\WaecPayment;
use App\Models\WaecPaymentApproval;
use App\Repositories\WaecCandidateRepository;
use App\Repositories\WaecPaymentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WaecPaymentService
{
    protected $paymentRepository;

    protected $candidateRepository;

    public function __construct(
        WaecPaymentRepository $paymentRepository,
        WaecCandidateRepository $candidateRepository
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->candidateRepository = $candidateRepository;
    }

    /**
     * Submit a new WAEC payment.
     */
    public function submitPayment(array $data, int $schoolId): WaecPayment
    {
        return DB::transaction(function () use ($data, $schoolId) {
            // Generate unique payment reference
            $reference = $this->generatePaymentReference();

            // Create payment
            $payment = $this->paymentRepository->create([
                'school_id' => $schoolId,
                'candidate_id' => $data['candidate_id'],
                'student_id' => $data['student_id'],
                'guardian_id' => $data['guardian_id'] ?? null,
                'payment_reference' => $reference,
                'payment_method' => $data['payment_method'],
                'gateway' => $data['gateway'] ?? null,
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'NGN',
                'payment_date' => $data['payment_date'] ?? now()->toDateString(),
                'proof_document' => $data['proof_document'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'account_name' => $data['account_name'] ?? null,
                'transaction_reference' => $data['transaction_reference'] ?? null,
                'payment_notes' => $data['payment_notes'] ?? null,
                'status' => 'submitted',
                'submitted_by' => Auth::id(),
                'submitted_at' => now(),
                'created_by' => Auth::id(),
            ]);

            // Log submission in approval history
            WaecPaymentApproval::logAction(
                $payment->id,
                Auth::id(),
                'submitted',
                null,
                'submitted',
                'Payment submitted for review'
            );

            // Notify Principal
            $this->notifyPrincipal($payment, 'New WAEC payment submitted for review');

            return $payment->load(['candidate.student.user', 'student.user', 'guardian.user']);
        });
    }

    /**
     * Approve a payment (Principal only).
     */
    public function approvePayment(int $paymentId, int $schoolId, ?string $comment = null): WaecPayment
    {
        return DB::transaction(function () use ($paymentId, $schoolId, $comment) {
            $payment = $this->findPayment($paymentId, $schoolId);

            if (! $payment->canBeApproved()) {
                throw new \Exception('Payment cannot be approved in its current status.');
            }

            $previousStatus = $payment->status;

            // Generate receipt number
            $receiptNumber = $this->generateReceiptNumber();

            // Update payment
            $payment->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'receipt_number' => $receiptNumber,
                'receipt_generated_at' => now(),
            ]);

            // Log approval in approval history
            WaecPaymentApproval::logAction(
                $payment->id,
                Auth::id(),
                'approved',
                $previousStatus,
                'approved',
                $comment,
                null
            );

            // Update candidate payment status (handled by model event)
            $payment->candidate->updatePaymentStatus();

            // Notify guardian/student
            $this->notifyGuardianAndStudent(
                $payment,
                'WAEC Payment Approved',
                "Your WAEC payment of {$payment->amount} has been approved. Receipt Number: {$receiptNumber}"
            );

            return $payment->fresh(['candidate.student.user', 'student.user', 'guardian.user', 'approver']);
        });
    }

    /**
     * Reject a payment (Principal only).
     */
    public function rejectPayment(int $paymentId, int $schoolId, string $reason, ?string $comment = null): WaecPayment
    {
        return DB::transaction(function () use ($paymentId, $schoolId, $reason, $comment) {
            $payment = $this->findPayment($paymentId, $schoolId);

            if (! $payment->canBeRejected()) {
                throw new \Exception('Payment cannot be rejected in its current status.');
            }

            if (empty($reason)) {
                throw new \Exception('Rejection reason is required.');
            }

            $previousStatus = $payment->status;

            // Update payment
            $payment->update([
                'status' => 'rejected',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);

            // Log rejection in approval history
            WaecPaymentApproval::logAction(
                $payment->id,
                Auth::id(),
                'rejected',
                $previousStatus,
                'rejected',
                $comment,
                $reason
            );

            // Notify guardian/student
            $this->notifyGuardianAndStudent(
                $payment,
                'WAEC Payment Rejected',
                "Your WAEC payment has been rejected. Reason: {$reason}"
            );

            return $payment->fresh(['candidate.student.user', 'student.user', 'guardian.user', 'rejecter']);
        });
    }

    /**
     * Find payment by ID.
     */
    public function findPayment(int $paymentId, int $schoolId)
    {
        $payment = $this->paymentRepository->find(
            $paymentId,
            ['*'],
            ['candidate.student.user', 'student.user', 'guardian.user', 'submitter', 'approver', 'rejecter', 'approvals.user']
        );

        if ($payment && $payment->school_id !== $schoolId) {
            throw new \Exception('Unauthorized access to this payment.');
        }

        return $payment;
    }

    /**
     * Get pending payments for review.
     */
    public function getPendingPayments(int $schoolId)
    {
        return $this->paymentRepository->getPendingPayments(
            $schoolId,
            ['candidate.student.user', 'student.user', 'guardian.user', 'submitter']
        );
    }

    /**
     * Get payment statistics.
     */
    public function getPaymentStatistics(int $schoolId, ?int $sessionId = null, ?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        return $this->paymentRepository->getStatistics($schoolId, $sessionId, $startDate, $endDate);
    }

    /**
     * Get payments by candidate.
     */
    public function getCandidatePayments(int $candidateId)
    {
        return $this->paymentRepository->getByCandidate(
            $candidateId,
            ['submitter', 'approver', 'rejecter']
        );
    }

    /**
     * Get payments by student.
     */
    public function getStudentPayments(int $studentId)
    {
        return $this->paymentRepository->getByStudent(
            $studentId,
            ['candidate.session', 'submitter', 'approver']
        );
    }

    /**
     * Get school payments with filters.
     */
    public function getSchoolPayments(int $schoolId, int $perPage = 20, array $filters = [])
    {
        $query = WaecPayment::forSchool($schoolId);

        // Apply filters
        if (! empty($filters['status'])) {
            if ($filters['status'] === 'pending_review') {
                $query->whereIn('status', ['submitted', 'under_review']);
            } else {
                $query->where('status', $filters['status']);
            }
        }

        if (! empty($filters['session_id'])) {
            $query->whereHas('candidate', function ($q) use ($filters) {
                $q->where('session_id', $filters['session_id']);
            });
        }

        if (! empty($filters['class_id'])) {
            $query->whereHas('candidate', function ($q) use ($filters) {
                $q->where('class_id', $filters['class_id']);
            });
        }

        if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
            $query->whereBetween('payment_date', [$filters['start_date'], $filters['end_date']]);
        }

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('payment_reference', 'like', "%{$filters['search']}%")
                    ->orWhere('receipt_number', 'like', "%{$filters['search']}%")
                    ->orWhere('transaction_reference', 'like', "%{$filters['search']}%")
                    ->orWhereHas('student.user', function ($q) use ($filters) {
                        $q->where('first_name', 'like', "%{$filters['search']}%")
                            ->orWhere('last_name', 'like', "%{$filters['search']}%");
                    });
            });
        }

        return $query->with(['candidate.student.user', 'candidate.session', 'student.user', 'guardian.user', 'submitter', 'approver'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Generate unique payment reference.
     */
    protected function generatePaymentReference(): string
    {
        do {
            $reference = 'WAEC'.date('Ymd').strtoupper(Str::random(6));
        } while ($this->paymentRepository->findByReference($reference));

        return $reference;
    }

    /**
     * Generate unique receipt number.
     */
    protected function generateReceiptNumber(): string
    {
        do {
            $receiptNumber = 'WAEC-RCT-'.date('Ymd').'-'.strtoupper(Str::random(6));
        } while ($this->paymentRepository->findByReceiptNumber($receiptNumber));

        return $receiptNumber;
    }

    /**
     * Notify Principal of new payment submission.
     */
    protected function notifyPrincipal(WaecPayment $payment, string $message)
    {
        // Get all principals for the school
        $principals = User::where('school_id', $payment->school_id)
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['Principal', 'Vice Principal', 'Owner']);
            })
            ->get();

        foreach ($principals as $principal) {
            Notification::create([
                'user_id' => $principal->id,
                'title' => 'WAEC Payment Submission',
                'message' => $message,
                'type' => 'waec_payment',
                'link' => route('principal.waec.payments.show', $payment->id),
                'is_read' => false,
            ]);
        }
    }

    /**
     * Notify guardian and student.
     */
    protected function notifyGuardianAndStudent(WaecPayment $payment, string $title, string $message)
    {
        // Notify student
        if ($payment->student && $payment->student->user) {
            Notification::create([
                'user_id' => $payment->student->user_id,
                'title' => $title,
                'message' => $message,
                'type' => 'waec_payment',
                'link' => route('waec.payments.show', $payment->id),
                'is_read' => false,
            ]);
        }

        // Notify guardian
        if ($payment->guardian && $payment->guardian->user) {
            Notification::create([
                'user_id' => $payment->guardian->user_id,
                'title' => $title,
                'message' => $message,
                'type' => 'waec_payment',
                'link' => route('waec.payments.show', $payment->id),
                'is_read' => false,
            ]);
        }
    }

    /**
     * Get payment by reference.
     */
    public function getPaymentByReference(string $reference)
    {
        return $this->paymentRepository->findByReference(
            $reference,
            ['candidate.student.user', 'student.user', 'guardian.user', 'approver']
        );
    }

    /**
     * Cancel a payment.
     */
    public function cancelPayment(int $paymentId, int $schoolId, string $reason): WaecPayment
    {
        return DB::transaction(function () use ($paymentId, $schoolId, $reason) {
            $payment = $this->findPayment($paymentId, $schoolId);

            if ($payment->isApproved()) {
                throw new \Exception('Cannot cancel an approved payment.');
            }

            $previousStatus = $payment->status;

            $payment->update([
                'status' => 'cancelled',
            ]);

            // Log cancellation
            WaecPaymentApproval::logAction(
                $payment->id,
                Auth::id(),
                'cancelled',
                $previousStatus,
                'cancelled',
                null,
                $reason
            );

            return $payment->fresh();
        });
    }
}
