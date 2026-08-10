<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\WaecCandidate;
use App\Models\WaecPayment;
use App\Services\WaecCandidateService;
use App\Services\WaecPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrincipalWaecController extends Controller
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
     * Display all WAEC candidates.
     */
    public function candidates(Request $request)
    {
        $this->authorize('viewAny', WaecCandidate::class);

        $filters = [
            'session_id' => $request->input('session_id'),
            'class_id' => $request->input('class_id'),
            'payment_status' => $request->input('payment_status'),
            'status' => $request->input('status'),
            'search' => $request->input('search'),
        ];

        $candidates = $this->candidateService->getSchoolCandidates(
            Auth::user()->school_id,
            20,
            array_filter($filters)
        );

        $sessions = AcademicSession::where('school_id', Auth::user()->school_id)
            ->orderBy('name', 'desc')
            ->get();

        $classes = SchoolClass::where('school_id', Auth::user()->school_id)
            ->orderBy('name')
            ->get();

        $statistics = $this->candidateService->getCandidateStatistics(
            Auth::user()->school_id,
            $filters['session_id']
        );

        return view('waec.principal.candidates.index', compact(
            'candidates',
            'sessions',
            'classes',
            'statistics',
            'filters'
        ));
    }

    /**
     * Show form to register new candidate.
     */
    public function createCandidate()
    {
        $this->authorize('create', WaecCandidate::class);

        $school = Auth::user()->school;

        $sessions = AcademicSession::where('school_id', $school->id)
            ->orderBy('name', 'desc')
            ->get();

        $classes = SchoolClass::where('school_id', $school->id)
            ->with('arms')
            ->orderBy('name')
            ->get();

        // Get eligible students (SS3 typically)
        $students = Student::where('school_id', $school->id)
            ->where('status', 'active')
            ->with('user', 'schoolClass')
            ->get();

        return view('waec.principal.candidates.create', compact(
            'sessions',
            'classes',
            'students'
        ));
    }

    /**
     * Store new WAEC candidate.
     */
    public function storeCandidate(Request $request)
    {
        $this->authorize('create', WaecCandidate::class);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'session_id' => 'required|exists:academic_sessions,id',
            'class_id' => 'required|exists:classes,id',
            'arm_id' => 'nullable|exists:class_arms,id',
            'notes' => 'nullable|string',
        ]);

        try {
            $candidate = $this->candidateService->registerCandidate(
                $validated,
                Auth::user()->school_id
            );

            return redirect()->route('principal.waec.candidates')
                ->with('success', 'WAEC candidate registered successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to register candidate: '.$e->getMessage());
        }
    }

    /**
     * Remove/Cancel a candidate.
     */
    public function destroyCandidate($id, Request $request)
    {
        $candidate = WaecCandidate::findOrFail($id);

        $this->authorize('delete', $candidate);

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|min:10',
        ]);

        try {
            $this->candidateService->cancelCandidate(
                $id,
                Auth::user()->school_id,
                $validated['cancellation_reason']
            );

            return redirect()->route('principal.waec.candidates')
                ->with('success', 'Candidate cancelled successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel candidate: '.$e->getMessage());
        }
    }

    /**
     * Display all WAEC payments.
     */
    public function payments(Request $request)
    {
        $this->authorize('viewAny', WaecPayment::class);

        $filters = [
            'status' => $request->input('status'),
            'session_id' => $request->input('session_id'),
            'class_id' => $request->input('class_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'search' => $request->input('search'),
        ];

        $payments = $this->paymentService->getSchoolPayments(
            Auth::user()->school_id,
            20,
            array_filter($filters)
        );

        $sessions = AcademicSession::where('school_id', Auth::user()->school_id)
            ->orderBy('name', 'desc')
            ->get();

        $classes = SchoolClass::where('school_id', Auth::user()->school_id)
            ->orderBy('name')
            ->get();

        $statistics = $this->paymentService->getPaymentStatistics(
            Auth::user()->school_id,
            $filters['session_id'] ?? null
        );

        return view('waec.principal.payments.index', compact(
            'payments',
            'sessions',
            'classes',
            'statistics',
            'filters'
        ));
    }

    /**
     * Display pending payments for review.
     */
    public function pendingPayments()
    {
        $this->authorize('viewAny', WaecPayment::class);

        $pendingPayments = $this->paymentService->getPendingPayments(Auth::user()->school_id);

        return view('waec.principal.payments.pending', compact('pendingPayments'));
    }

    /**
     * Show payment details for review.
     */
    public function showPayment($id)
    {
        $payment = WaecPayment::with([
            'candidate.student.user',
            'candidate.session',
            'candidate.schoolClass',
            'candidate.arm',
            'student.user',
            'guardian.user',
            'submitter',
            'approver',
            'rejecter',
            'approvals.user',
        ])->findOrFail($id);

        $this->authorize('view', $payment);

        return view('waec.principal.payments.show', compact('payment'));
    }

    /**
     * Approve a payment.
     */
    public function approvePayment($id, Request $request)
    {
        $payment = WaecPayment::findOrFail($id);

        $this->authorize('approve', $payment);

        $validated = $request->validate([
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $approvedPayment = $this->paymentService->approvePayment(
                $id,
                Auth::user()->school_id,
                $validated['comment'] ?? null
            );

            return redirect()->route('principal.waec.payments.show', $approvedPayment->id)
                ->with('success', 'Payment approved successfully. Receipt number: '.$approvedPayment->receipt_number);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve payment: '.$e->getMessage());
        }
    }

    /**
     * Reject a payment.
     */
    public function rejectPayment($id, Request $request)
    {
        $payment = WaecPayment::findOrFail($id);

        $this->authorize('reject', $payment);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $rejectedPayment = $this->paymentService->rejectPayment(
                $id,
                Auth::user()->school_id,
                $validated['rejection_reason'],
                $validated['comment'] ?? null
            );

            return redirect()->route('principal.waec.payments.show', $rejectedPayment->id)
                ->with('success', 'Payment rejected. Guardian/Student has been notified.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject payment: '.$e->getMessage());
        }
    }

    /**
     * Show candidate details.
     */
    public function showCandidate($id)
    {
        $candidate = WaecCandidate::with([
            'student.user',
            'student.guardians.user',
            'session',
            'schoolClass',
            'arm',
            'payments.submitter',
            'payments.approver',
            'registrar',
        ])->findOrFail($id);

        $this->authorize('view', $candidate);

        return view('waec.principal.candidates.show', compact('candidate'));
    }
}
