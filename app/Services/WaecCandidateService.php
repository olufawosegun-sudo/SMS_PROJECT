<?php

namespace App\Services;

use App\Models\WaecCandidate;
use App\Models\WaecFeeConfiguration;
use App\Repositories\WaecCandidateRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WaecCandidateService
{
    protected $candidateRepository;

    public function __construct(WaecCandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    /**
     * Register a new WAEC candidate.
     */
    public function registerCandidate(array $data, int $schoolId): WaecCandidate
    {
        return DB::transaction(function () use ($data, $schoolId) {
            // Check if student is already registered for this session
            if ($this->candidateRepository->isStudentRegistered($data['student_id'], $data['session_id'])) {
                throw new \Exception('Student is already registered as a WAEC candidate for this session.');
            }

            // Get fee configuration for the session
            $fees = WaecFeeConfiguration::getSessionFees($schoolId, $data['session_id']);

            $examinationFee = $fees->where('fee_type', 'examination_fee')->first()?->amount ?? 0;
            $registrationFee = $fees->where('fee_type', 'registration_fee')->first()?->amount ?? 0;
            $otherCharges = $fees->where('fee_type', 'other')->sum('amount') ?? 0;

            $totalFee = $examinationFee + $registrationFee + $otherCharges;

            // Create candidate
            $candidate = $this->candidateRepository->create([
                'school_id' => $schoolId,
                'student_id' => $data['student_id'],
                'session_id' => $data['session_id'],
                'class_id' => $data['class_id'],
                'arm_id' => $data['arm_id'] ?? null,
                'examination_fee' => $examinationFee,
                'registration_fee' => $registrationFee,
                'other_charges' => $otherCharges,
                'total_fee' => $totalFee,
                'amount_paid' => 0,
                'balance' => $totalFee,
                'payment_status' => 'unpaid',
                'status' => 'registered',
                'registration_date' => now()->toDateString(),
                'notes' => $data['notes'] ?? null,
                'registered_by' => Auth::id(),
            ]);

            return $candidate->load(['student.user', 'session', 'schoolClass', 'arm']);
        });
    }

    /**
     * Get candidates for a school.
     */
    public function getSchoolCandidates(int $schoolId, int $perPage = 20, array $filters = [])
    {
        $query = WaecCandidate::forSchool($schoolId);

        // Apply filters
        if (! empty($filters['session_id'])) {
            $query->forSession($filters['session_id']);
        }

        if (! empty($filters['class_id'])) {
            $query->where('class_id', $filters['class_id']);
        }

        if (! empty($filters['payment_status'])) {
            $query->paymentStatus($filters['payment_status']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $query->whereHas('student.user', function ($q) use ($filters) {
                $q->where('first_name', 'like', "%{$filters['search']}%")
                    ->orWhere('last_name', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%");
            })->orWhere('candidate_number', 'like', "%{$filters['search']}%");
        }

        return $query->with(['student.user', 'session', 'schoolClass', 'arm', 'registrar'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get candidate by ID.
     */
    public function findCandidate(int $candidateId, int $schoolId)
    {
        $candidate = $this->candidateRepository->find(
            $candidateId,
            ['*'],
            ['student.user', 'session', 'schoolClass', 'arm', 'payments', 'registrar']
        );

        if ($candidate && $candidate->school_id !== $schoolId) {
            throw new \Exception('Unauthorized access to this candidate.');
        }

        return $candidate;
    }

    /**
     * Get candidate statistics.
     */
    public function getCandidateStatistics(int $schoolId, ?int $sessionId = null)
    {
        return $this->candidateRepository->getStatistics($schoolId, $sessionId);
    }

    /**
     * Get candidates for a guardian's wards.
     */
    public function getGuardianCandidates(int $guardianId)
    {
        return $this->candidateRepository->getGuardianCandidates(
            $guardianId,
            ['student.user', 'session', 'schoolClass', 'payments']
        );
    }

    /**
     * Get candidates by student.
     */
    public function getStudentCandidates(int $studentId)
    {
        return $this->candidateRepository->findAllBy(
            'student_id',
            $studentId,
            ['*'],
            ['session', 'schoolClass', 'payments']
        );
    }

    /**
     * Cancel/Remove a candidate.
     */
    public function cancelCandidate(int $candidateId, int $schoolId, string $reason): bool
    {
        $candidate = $this->findCandidate($candidateId, $schoolId);

        if (! $candidate) {
            throw new \Exception('Candidate not found.');
        }

        // Check if any approved payments exist
        if ($candidate->approvedPayments()->exists()) {
            throw new \Exception('Cannot cancel candidate with approved payments. Please contact administrator.');
        }

        return DB::transaction(function () use ($candidate, $reason) {
            $candidate->update([
                'status' => 'cancelled',
                'cancelled_by' => Auth::id(),
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            // Cancel pending payments
            $candidate->payments()->whereIn('status', ['pending', 'submitted', 'under_review'])->update([
                'status' => 'cancelled',
            ]);

            return true;
        });
    }

    /**
     * Assign candidate number (after full payment).
     */
    public function assignCandidateNumber(int $candidateId, string $candidateNumber): WaecCandidate
    {
        $candidate = $this->candidateRepository->find($candidateId);

        if (! $candidate->isFullyPaid()) {
            throw new \Exception('Candidate must be fully paid before assigning candidate number.');
        }

        $candidate->update([
            'candidate_number' => $candidateNumber,
            'status' => 'exam_ready',
        ]);

        return $candidate->fresh();
    }

    /**
     * Get candidates by class.
     */
    public function getCandidatesByClass(int $schoolId, int $classId)
    {
        return $this->candidateRepository->getByClass(
            $schoolId,
            $classId,
            ['student.user', 'session', 'arm', 'payments']
        );
    }

    /**
     * Update candidate fees.
     */
    public function updateCandidateFees(int $candidateId, array $fees): WaecCandidate
    {
        $candidate = $this->candidateRepository->find($candidateId);

        $totalFee = ($fees['examination_fee'] ?? $candidate->examination_fee)
                  + ($fees['registration_fee'] ?? $candidate->registration_fee)
                  + ($fees['other_charges'] ?? $candidate->other_charges);

        $candidate->update([
            'examination_fee' => $fees['examination_fee'] ?? $candidate->examination_fee,
            'registration_fee' => $fees['registration_fee'] ?? $candidate->registration_fee,
            'other_charges' => $fees['other_charges'] ?? $candidate->other_charges,
            'total_fee' => $totalFee,
        ]);

        return $candidate->fresh();
    }
}
