<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\WaecRemittance;
use App\Services\WaecRemittanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaecRemittanceController extends Controller
{
    protected $remittanceService;

    public function __construct(WaecRemittanceService $remittanceService)
    {
        $this->remittanceService = $remittanceService;
    }

    /**
     * Display list of WAEC remittances.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', WaecRemittance::class);

        $schoolId = Auth::user()->school_id;
        $sessionId = $request->input('session_id');

        $summary = $this->remittanceService->getSummary($schoolId, $sessionId);
        $remittances = $this->remittanceService->getSchoolRemittances($schoolId);

        $sessions = AcademicSession::where('school_id', $schoolId)
            ->orderBy('name', 'desc')
            ->get();

        return view('waec.remittance.index', compact(
            'summary',
            'remittances',
            'sessions',
            'sessionId'
        ));
    }

    /**
     * Show form to make payment/remittance to WAEC.
     */
    public function create(Request $request)
    {
        $this->authorize('create', WaecRemittance::class);

        $schoolId = Auth::user()->school_id;
        $sessionId = $request->input('session_id');

        $sessions = AcademicSession::where('school_id', $schoolId)
            ->orderBy('name', 'desc')
            ->get();

        $currentSession = $sessionId
            ? $sessions->firstWhere('id', $sessionId)
            : $sessions->firstWhere('is_current', true) ?? $sessions->first();

        $eligibleCandidates = $this->remittanceService->getEligibleCandidates(
            $schoolId,
            $currentSession?->id
        );

        $summary = $this->remittanceService->getSummary($schoolId, $currentSession?->id);

        return view('waec.remittance.create', compact(
            'sessions',
            'currentSession',
            'eligibleCandidates',
            'summary'
        ));
    }

    /**
     * Store new WAEC payment/remittance.
     */
    public function store(Request $request)
    {
        $this->authorize('create', WaecRemittance::class);

        $validated = $request->validate([
            'session_id' => 'required|exists:academic_sessions,id',
            'candidate_ids' => 'required|array|min:1',
            'candidate_ids.*' => 'exists:waec_candidates,id',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:bank_transfer,waec_portal,card,draft',
            'bank_name' => 'nullable|string|max:255',
            'waec_transaction_reference' => 'required|string|max:255',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
            'proof_document' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        try {
            // Handle proof document upload if provided
            if ($request->hasFile('proof_document')) {
                $path = $request->file('proof_document')->store('waec_remittances', 'public');
                $validated['proof_document'] = $path;
            }

            $remittance = $this->remittanceService->createRemittance(
                $validated,
                Auth::user()->school_id,
                Auth::id()
            );

            $route = Auth::user()->role->name === 'Owner'
                ? route('owner.waec.remittance.show', $remittance->id)
                : route('principal.waec.remittance.show', $remittance->id);

            return redirect($route)
                ->with('success', 'WAEC Payment / Remittance recorded successfully for '.$remittance->total_candidates_count.' candidate(s). Batch Reference: '.$remittance->batch_reference);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to record WAEC payment: '.$e->getMessage());
        }
    }

    /**
     * Display remittance details.
     */
    public function show($id)
    {
        $remittance = $this->remittanceService->findRemittance($id, Auth::user()->school_id);

        $this->authorize('view', $remittance);

        return view('waec.remittance.show', compact('remittance'));
    }
}
