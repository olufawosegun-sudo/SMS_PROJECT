<?php

namespace App\Services;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Services\PaymentGateways\PaymentGatewayFactory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Get paginated payments for a school
     */
    public function getSchoolPayments(int $schoolId, int $perPage = 20)
    {
        return $this->paymentRepository->getBySchool(
            $schoolId,
            $perPage,
            ['student.user', 'invoice', 'academicSession', 'academicTerm']
        );
    }

    /**
     * Get payment statistics
     */
    public function getSchoolStats(int $schoolId, ?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        return $this->paymentRepository->getStatsBySchool($schoolId, $startDate, $endDate);
    }

    /**
     * Find payment by ID
     */
    public function findPayment(int $paymentId, int $schoolId)
    {
        $payment = $this->paymentRepository->find(
            $paymentId,
            ['*'],
            ['student.user', 'invoice', 'academicSession', 'academicTerm']
        );

        if ($payment && $payment->school_id !== $schoolId) {
            throw new \Exception('Unauthorized access to this payment.');
        }

        return $payment;
    }

    /**
     * Record a new payment
     */
    public function recordPayment(array $data, int $schoolId)
    {
        return DB::transaction(function () use ($data, $schoolId) {
            $reference = $data['reference'] ?? $this->generatePaymentReference();

            $payment = $this->paymentRepository->create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $schoolId,
                'student_id' => $data['student_id'],
                'invoice_id' => $data['invoice_id'] ?? null,
                'academic_session_id' => $data['academic_session_id'] ?? null,
                'academic_term_id' => $data['academic_term_id'] ?? null,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'payment_date' => $data['payment_date'] ?? now(),
                'reference' => $reference,
                'status' => $data['status'] ?? 'pending',
                'description' => $data['description'] ?? null,
                'received_by' => $data['received_by'] ?? null,
            ]);

            return $payment->load(['student.user', 'invoice']);
        });
    }

    /**
     * Confirm a payment
     */
    public function confirmPayment(int $paymentId, int $schoolId, ?int $confirmedBy = null)
    {
        $payment = $this->findPayment($paymentId, $schoolId);

        if (! $payment) {
            throw new \Exception('Payment not found.');
        }

        if ($payment->status === 'confirmed') {
            throw new \Exception('Payment is already confirmed.');
        }

        $payment->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => $confirmedBy,
        ]);

        // TODO: Fire PaymentConfirmed event
        // event(new PaymentConfirmed($payment));

        return $payment->fresh();
    }

    /**
     * Reject a payment
     */
    public function rejectPayment(int $paymentId, int $schoolId, string $reason)
    {
        $payment = $this->findPayment($paymentId, $schoolId);

        if (! $payment) {
            throw new \Exception('Payment not found.');
        }

        $payment->update([
            'status' => 'failed',
            'rejection_reason' => $reason,
        ]);

        return $payment->fresh();
    }

    /**
     * Get payments by student
     */
    public function getStudentPayments(int $studentId, ?int $sessionId = null, ?int $termId = null)
    {
        $payments = $this->paymentRepository->getByStudent(
            $studentId,
            ['invoice', 'academicSession', 'academicTerm']
        );

        if ($sessionId) {
            $payments = $payments->where('academic_session_id', $sessionId);
        }

        if ($termId) {
            $payments = $payments->where('academic_term_id', $termId);
        }

        return $payments;
    }

    /**
     * Get total paid by student
     */
    public function getStudentTotalPaid(int $studentId, ?int $sessionId = null, ?int $termId = null)
    {
        return $this->paymentRepository->getTotalPaidByStudent($studentId, $sessionId, $termId);
    }

    /**
     * Get payments by date range
     */
    public function getPaymentsByDateRange(int $schoolId, Carbon $startDate, Carbon $endDate)
    {
        return $this->paymentRepository->getByDateRange(
            $schoolId,
            $startDate,
            $endDate,
            ['student.user', 'invoice']
        );
    }

    /**
     * Get pending payments
     */
    public function getPendingPayments(int $schoolId)
    {
        return $this->paymentRepository->getPendingPayments(
            $schoolId,
            ['student.user', 'invoice']
        );
    }

    /**
     * Generate unique payment reference
     */
    protected function generatePaymentReference(): string
    {
        do {
            $reference = 'PAY'.date('Ymd').strtoupper(Str::random(6));
        } while ($this->paymentRepository->findByReference($reference));

        return $reference;
    }

    /**
     * Verify payment by reference
     */
    public function verifyPaymentByReference(string $reference)
    {
        return $this->paymentRepository->findByReference(
            $reference,
            ['student.user', 'invoice', 'academicSession', 'academicTerm']
        );
    }

    /**
     * Initialize gateway payment using Factory & Strategy pattern.
     */
    public function initializeGatewayPayment(int $paymentId, int $schoolId, string $driver, array $options = []): array
    {
        $payment = $this->findPayment($paymentId, $schoolId);

        if (! $payment) {
            throw new \Exception('Payment record not found.');
        }

        $gateway = PaymentGatewayFactory::make($driver);

        $result = $gateway->initializePayment($payment, $options);

        if (($result['status'] ?? '') === 'confirmed') {
            $this->confirmPayment($payment->id, $schoolId);
        }

        return $result;
    }

    /**
     * Verify gateway transaction using Strategy pattern.
     */
    public function verifyGatewayPayment(string $reference, string $driver): array
    {
        $gateway = PaymentGatewayFactory::make($driver);

        $verification = $gateway->verifyPayment($reference);

        $payment = $this->paymentRepository->findByReference($reference);

        if ($payment && ($verification['paid'] ?? false) && $payment->status !== 'confirmed') {
            $this->confirmPayment($payment->id, $payment->school_id);
        }

        return $verification;
    }

    /**
     * Handle gateway webhook using Strategy pattern.
     */
    public function handleGatewayWebhook(string $driver, array $payload): bool
    {
        $gateway = PaymentGatewayFactory::make($driver);

        return $gateway->handleWebhook($payload);
    }
}
