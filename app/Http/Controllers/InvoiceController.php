<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\FeeCategory;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display all invoices for the school.
     */
    public function index()
    {
        $school = Auth::user()->school;
        $invoices = Invoice::where('school_id', $school->id)
            ->with(['student.user', 'items.feeCategory'])
            ->orderBy('created_at', 'desc')
            ->get();
        $students = Student::where('school_id', $school->id)->where('status', 'active')->with('user')->get();
        $feeCategories = FeeCategory::where('school_id', $school->id)->where('status', 'active')->get();

        $summary = [
            'total' => $invoices->sum('total_amount'),
            'paid' => $invoices->sum('paid_amount'),
            'unpaid' => $invoices->sum('balance'),
            'partial' => $invoices->where('status', 'partially_paid')->sum('balance'),
        ];

        return view('invoices.index', compact('invoices', 'students', 'feeCategories', 'summary', 'school'));
    }

    /**
     * Show the create invoice form with dynamic line items.
     */
    public function create()
    {
        $school = Auth::user()->school;
        $students = Student::where('school_id', $school->id)
            ->where('status', 'active')
            ->with(['user', 'schoolClass'])
            ->orderBy('admission_no')
            ->get();
        $feeCategories = FeeCategory::where('school_id', $school->id)
            ->where('status', 'active')
            ->get();

        return view('invoices.create', compact('students', 'feeCategories', 'school'));
    }

    /**
     * Store a new invoice with multiple fee category line items.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'due_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.category_name' => 'nullable|string|max:150',
            'items.*.fee_category_id' => 'nullable',
            'items.*.amount' => 'required|numeric|min:0.01',
        ]);

        $school = Auth::user()->school;
        $student = null;

        // 1. Direct ID if provided and exists
        if ($request->filled('student_id') && is_numeric($request->student_id)) {
            $student = Student::where('school_id', $school->id)->find($request->student_id);
        }

        // 2. Lookup by typed student name / admission number from datalist
        if (! $student && ($request->filled('student_name') || $request->filled('student_search'))) {
            $raw = trim($request->student_name ?? $request->student_search);

            // If string is formatted as "First Last (ADM-001) — Class", extract admission number
            if (preg_match('/\(([^)]+)\)/', $raw, $matches)) {
                $adm = trim($matches[1]);
                $student = Student::where('school_id', $school->id)->where('admission_no', $adm)->first();
            }

            if (! $student) {
                // Try direct admission number match
                $student = Student::where('school_id', $school->id)->where('admission_no', $raw)->first();
            }

            if (! $student) {
                // Try name match
                $cleanName = trim(preg_replace('/\(.*$/', '', $raw));
                $student = Student::where('school_id', $school->id)
                    ->whereHas('user', function ($q) use ($cleanName) {
                        $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$cleanName}%")
                            ->orWhere('first_name', 'like', "%{$cleanName}%")
                            ->orWhere('last_name', 'like', "%{$cleanName}%");
                    })
                    ->first();
            }
        }

        // 3. Fallback to first active student if still not found
        if (! $student) {
            $student = Student::where('school_id', $school->id)->where('status', 'active')->first();
        }

        if (! $student) {
            return redirect()->back()->withErrors(['student_name' => 'Please select or write a valid student recipient.'])->withInput();
        }

        $session = AcademicSession::where('school_id', $school->id)->where('is_current', true)->first()
            ?? AcademicSession::where('school_id', $school->id)->latest()->first();

        $term = AcademicTerm::where('school_id', $school->id)->where('is_current', true)->first()
            ?? AcademicTerm::where('school_id', $school->id)->latest()->first();

        $totalAmount = collect($request->items)->sum('amount');

        return DB::transaction(function () use ($request, $school, $student, $session, $term, $totalAmount) {
            $invoice = Invoice::create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $school->id,
                'student_id' => $student->id,
                'class_id' => $student->class_id,
                'session_id' => $session?->id ?? 1,
                'term_id' => $term?->id ?? 1,
                'invoice_number' => 'INV-'.date('Y').'-'.str_pad(Invoice::where('school_id', $school->id)->count() + 1, 4, '0', STR_PAD_LEFT),
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'balance' => $totalAmount,
                'status' => 'unpaid',
                'due_date' => $request->due_date,
                'issued_by' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $feeCategoryId = null;

                // 1. If explicit category ID is provided and belongs to school
                if (! empty($item['fee_category_id']) && is_numeric($item['fee_category_id'])) {
                    $category = FeeCategory::where('school_id', $school->id)->find($item['fee_category_id']);
                    if ($category) {
                        $feeCategoryId = $category->id;
                    }
                }

                // 2. If user wrote / typed a custom category name in the container
                if (! $feeCategoryId && ! empty($item['category_name'])) {
                    $categoryName = trim($item['category_name']);
                    $category = FeeCategory::firstOrCreate(
                        [
                            'school_id' => $school->id,
                            'name' => $categoryName,
                        ],
                        [
                            'amount' => $item['amount'],
                            'status' => 'active',
                        ]
                    );
                    $feeCategoryId = $category->id;
                }

                // Fallback default
                if (! $feeCategoryId) {
                    $category = FeeCategory::firstOrCreate(
                        [
                            'school_id' => $school->id,
                            'name' => 'General School Fee',
                        ],
                        [
                            'amount' => $item['amount'],
                            'status' => 'active',
                        ]
                    );
                    $feeCategoryId = $category->id;
                }

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'fee_category_id' => $feeCategoryId,
                    'amount' => $item['amount'],
                ]);
            }

            $paymentUrl = route('invoices.public.pay', $invoice->uuid);

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice #'.$invoice->invoice_number.' generated successfully!')
                ->with('new_invoice_uuid', $invoice->uuid)
                ->with('new_invoice_number', $invoice->invoice_number)
                ->with('new_invoice_url', $paymentUrl)
                ->with('new_invoice_student', $student->user ? ($student->user->first_name.' '.$student->user->last_name) : 'Student')
                ->with('new_invoice_amount', number_format($totalAmount, 2));
        });
    }

    /**
     * Update invoice status.
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Invoice status updated!');
    }

    /**
     * Delete an invoice and its items.
     */
    public function destroy($id)
    {
        Invoice::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Invoice deleted successfully!');
    }
}
