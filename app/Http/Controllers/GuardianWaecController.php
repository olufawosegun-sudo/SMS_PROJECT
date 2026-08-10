<?php

namespace App\Http\Controllers;

use App\Models\WaecCandidate;
use App\Models\WaecPayment;
use App\Services\WaecCandidateService;
use App\Services\WaecPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardianWaecController extends Controller
{
    protected $candidateService;

    protected $paymentService;

    public function __construct(
        WaecCandidateService $candidateService,
        WaecPaymentService $paymentService
    ) {
        $this->candidateService = $candidateService;
        $this->paymentService = $paymentService;
    }

    /**
     * Display guardian's WAEC candidates.
     */
    public function candidates()
    {
        $user = Auth::user();

        if (! $user->guardian) {
            return redirect()->route('dashboard')->with('error', 'Guardian profile not found.');
        }

        $candidates = $this->candidateService->getGuardianCandidates($user->guardian->id);

        return view('waec.guardian.candidates', compact('candidates'));
    }

    /**
     * Display WAEC payments for guardian's wards.
     */
    public function payments()
    {
        $user = Auth::user();

        if (! $user->guardian) {
            return redirect()->route('dashboard')->with('error', 'Guardian profile not found.');
        }

        $candidates = $this->candidateService->getGuardianCandidates($user->guardian->id);
        $candidateIds = $candidates->pluck('id');

        $payments = WaecPayment::whereIn('candidate_id', $candidateIds)
            ->with(['candidate.student.user', 'candidate.session', 'submitter', 'approver'])
            ->latest()
            ->paginate(20);

        return view('waec.guardian.payments.index', compact('payments', 'candidates'));
    }

    /**
     * Show payment submission form.
     */
    public function createPayment(Request $request)
    {
        $user = Auth::user();

        if (! $user->guardian) {
            return redirect()->route('dashboard')->with('error', 'Guardian profile not found.');
        }

        $candidateId = $request->query('candidate_id');

        if (! $candidateId) {
            return redirect()->route('waec.candidates')->with('error', 'Please select a candidate.');
        }

        $candidate = WaecCandidate::find($candidateId);

        if (! $candidate) {
            return redirect()->route('waec.candidates')->with('error', 'Candidate not found.');
        }

        // Verify guardian owns this candidate's student
        $this->authorize('view', $candidate);

        return view('waec.guardian.payments.create', compact('candidate'));
    }

    /**
     * Submit a new payment.
     */
    public function submitPayment(Request $request)
    {
        $user = Auth::user();

        if (! $user->guardian) {
            return redirect()->route('dashboard')->with('error', 'Guardian profile not found.');
        }

        $validated = $request->validate([
            'candidate_id' => 'required|exists:waec_candidates,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'bank_name' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'transaction_reference' => 'nullable|string|max:255',
            'payment_notes' => 'nullable|string',
            'proof_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $candidate = WaecCandidate::find($validated['candidate_id']);

        // Verify authorization
        $this->authorize('view', $candidate);

        try {
            // Handle file upload
            $proofPath = null;
            if ($request->hasFile('proof_document')) {
                $proofPath = $request->file('proof_document')->store('waec-payments', 'public');
            }

            $paymentData = [
                'candidate_id' => $validated['candidate_id'],
                'student_id' => $candidate->student_id,
                'guardian_id' => $user->guardian->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $validated['payment_date'],
                'bank_name' => $validated['bank_name'] ?? null,
                'account_name' => $validated['account_name'] ?? null,
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'payment_notes' => $validated['payment_notes'] ?? null,
                'proof_document' => $proofPath,
            ];

            $payment = $this->paymentService->submitPayment($paymentData, $user->school_id);

            return redirect()->route('waec.payments.show', $payment->id)
                ->with('success', 'Payment submitted successfully and is now pending approval.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to submit payment: '.$e->getMessage());
        }
    }

    /**
     * Show payment details.
     */
    public function showPayment($id)
    {
        $payment = WaecPayment::with([
            'candidate.student.user',
            'candidate.session',
            'candidate.schoolClass',
            'submitter',
            'approver',
            'rejecter',
            'approvals.user',
        ])->findOrFail($id);

        $this->authorize('view', $payment);

        return view('waec.guardian.payments.show', compact('payment'));
    }

    /**
     * Download payment receipt.
     */
    public function downloadReceipt($id)
    {
        $payment = WaecPayment::with([
            'candidate.student.user',
            'candidate.session',
            'candidate.schoolClass',
            'school',
            'approver',
        ])->findOrFail($id);

        $this->authorize('downloadReceipt', $payment);

        if ($payment->status !== 'approved') {
            return redirect()->back()->with('error', 'Receipt is only available for approved payments.');
        }

        // Generate PDF receipt (will be implemented in Phase 9)
        return view('waec.receipts.payment', compact('payment'));
    }
}
