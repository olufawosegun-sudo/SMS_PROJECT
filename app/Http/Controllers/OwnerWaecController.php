<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\SchoolClass;
use App\Models\WaecCandidate;
use App\Models\WaecFeeConfiguration;
use App\Models\WaecPayment;
use App\Services\WaecCandidateService;
use App\Services\WaecPaymentService;
use App\Services\WaecReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerWaecController extends Controller
{
    protected $candidateService;

    protected $paymentService;

    protected $reportService;

    public function __construct(
        WaecCandidateService $candidateService,
        WaecPaymentService $paymentService,
        WaecReportService $reportService
    ) {
        $this->candidateService = $candidateService;
        $this->paymentService = $paymentService;
        $this->reportService = $reportService;
    }

    /**
     * Display WAEC reports dashboard.
     */
    public function reports(Request $request)
    {
        $this->authorize('viewReports', WaecPayment::class);

        $sessionId = $request->input('session_id');
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;

        $summary = $this->reportService->getFinancialSummary(
            Auth::user()->school_id,
            $sessionId,
            $startDate,
            $endDate
        );

        $paymentsBySession = $this->reportService->getPaymentsBySession(Auth::user()->school_id);
        $paymentsByClass = $this->reportService->getPaymentsByClass(Auth::user()->school_id, $sessionId);
        $paymentsByMethod = $this->reportService->getPaymentsByMethod(Auth::user()->school_id, $sessionId);
        $paymentTrends = $this->reportService->getPaymentTrends(Auth::user()->school_id, $sessionId, 30);
        $recentActivities = $this->reportService->getRecentActivities(Auth::user()->school_id, 10);

        $sessions = AcademicSession::where('school_id', Auth::user()->school_id)
            ->orderBy('name', 'desc')
            ->get();

        return view('waec.owner.reports.index', compact(
            'summary',
            'paymentsBySession',
            'paymentsByClass',
            'paymentsByMethod',
            'paymentTrends',
            'recentActivities',
            'sessions',
            'sessionId'
        ));
    }

    /**
     * Display detailed financial report.
     */
    public function financial(Request $request)
    {
        $this->authorize('viewReports', WaecPayment::class);

        $sessionId = $request->input('session_id');

        $summary = $this->reportService->getFinancialSummary(
            Auth::user()->school_id,
            $sessionId
        );

        $candidatesByStatus = $this->reportService->getCandidatesByPaymentStatus(
            Auth::user()->school_id,
            $sessionId
        );

        $topPayingClasses = $this->reportService->getTopPayingClasses(
            Auth::user()->school_id,
            $sessionId
        );

        $approvalStats = $this->reportService->getApprovalStatistics(
            Auth::user()->school_id,
            $sessionId
        );

        $sessions = AcademicSession::where('school_id', Auth::user()->school_id)
            ->orderBy('name', 'desc')
            ->get();

        return view('waec.owner.reports.financial', compact(
            'summary',
            'candidatesByStatus',
            'topPayingClasses',
            'approvalStats',
            'sessions',
            'sessionId'
        ));
    }

    /**
     * Export WAEC payment data.
     */
    public function export(Request $request)
    {
        $this->authorize('export', WaecPayment::class);

        $filters = [
            'session_id' => $request->input('session_id'),
            'status' => $request->input('status'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $payments = $this->reportService->exportPaymentData(
            Auth::user()->school_id,
            array_filter($filters)
        );

        $format = $request->input('format', 'csv');

        if ($format === 'pdf') {
            // PDF export (will be implemented in Phase 9)
            return view('waec.owner.reports.export-pdf', compact('payments', 'filters'));
        }

        // CSV export
        $filename = 'waec-payments-'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Payment Reference',
                'Receipt Number',
                'Student Name',
                'Class',
                'Session',
                'Amount',
                'Payment Method',
                'Payment Date',
                'Status',
                'Submitted By',
                'Submitted At',
                'Approved By',
                'Approved At',
            ]);

            // CSV data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->payment_reference,
                    $payment->receipt_number ?? '-',
                    $payment->student->user->name ?? '-',
                    $payment->candidate->schoolClass->name ?? '-',
                    $payment->candidate->session->name ?? '-',
                    $payment->amount,
                    $payment->payment_method,
                    $payment->payment_date->format('Y-m-d'),
                    $payment->status,
                    $payment->submitter->name ?? '-',
                    $payment->submitted_at?->format('Y-m-d H:i:s') ?? '-',
                    $payment->approver->name ?? '-',
                    $payment->approved_at?->format('Y-m-d H:i:s') ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display fee configuration page.
     */
    public function feeConfiguration()
    {
        $this->authorize('viewReports', WaecPayment::class);

        $sessions = AcademicSession::where('school_id', Auth::user()->school_id)
            ->orderBy('name', 'desc')
            ->get();

        $feeConfigurations = WaecFeeConfiguration::where('school_id', Auth::user()->school_id)
            ->with(['session', 'creator', 'updater'])
            ->orderBy('session_id', 'desc')
            ->get()
            ->groupBy('session_id');

        return view('waec.owner.fees.configuration', compact('sessions', 'feeConfigurations'));
    }

    /**
     * Update fee configuration.
     */
    public function updateFeeConfiguration(Request $request)
    {
        $this->authorize('viewReports', WaecPayment::class);

        $validated = $request->validate([
            'session_id' => 'required|exists:academic_sessions,id',
            'fees' => 'required|array',
            'fees.*.fee_type' => 'required|in:examination_fee,registration_fee,other',
            'fees.*.fee_name' => 'required|string|max:255',
            'fees.*.amount' => 'required|numeric|min:0',
            'fees.*.description' => 'nullable|string',
            'fees.*.status' => 'required|in:active,inactive',
        ]);

        try {
            foreach ($validated['fees'] as $feeData) {
                WaecFeeConfiguration::updateOrCreate(
                    [
                        'school_id' => Auth::user()->school_id,
                        'session_id' => $validated['session_id'],
                        'fee_type' => $feeData['fee_type'],
                    ],
                    [
                        'fee_name' => $feeData['fee_name'],
                        'amount' => $feeData['amount'],
                        'description' => $feeData['description'] ?? null,
                        'status' => $feeData['status'],
                        'updated_by' => Auth::id(),
                    ]
                );
            }

            return redirect()->route('owner.waec.fees.configuration')
                ->with('success', 'WAEC fee configuration updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update fee configuration: '.$e->getMessage());
        }
    }

    /**
     * View all candidates (Owner oversight).
     */
    public function candidates(Request $request)
    {
        $this->authorize('viewAny', WaecCandidate::class);

        $filters = [
            'session_id' => $request->input('session_id'),
            'class_id' => $request->input('class_id'),
            'payment_status' => $request->input('payment_status'),
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

        return view('waec.owner.candidates.index', compact(
            'candidates',
            'sessions',
            'classes',
            'statistics',
            'filters'
        ));
    }

    /**
     * View all payments (Owner oversight).
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

        return view('waec.owner.payments.index', compact(
            'payments',
            'sessions',
            'classes',
            'statistics',
            'filters'
        ));
    }
}
