<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use App\Models\FeeCategory;
use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $invoices = Invoice::where('school_id', $school->id)
            ->with(['student.user'])
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

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $school = Auth::user()->school;
        $student = Student::findOrFail($request->student_id);

        $session = AcademicSession::where('school_id', $school->id)->where('is_active', true)->first()
            ?? AcademicSession::where('school_id', $school->id)->first()
            ?? new AcademicSession(['id' => 1]);

        $term = AcademicTerm::where('school_id', $school->id)->where('is_active', true)->first()
            ?? AcademicTerm::where('school_id', $school->id)->first()
            ?? new AcademicTerm(['id' => 1]);

        Invoice::create([
            'uuid' => (string) Str::uuid(),
            'school_id' => $school->id,
            'student_id' => $student->id,
            'class_id' => $student->class_id,
            'session_id' => $session->id ?? 1,
            'term_id' => $term->id ?? 1,
            'invoice_number' => 'INV-' . date('Y') . '-' . str_pad(Invoice::where('school_id', $school->id)->count() + 1, 4, '0', STR_PAD_LEFT),
            'total_amount' => $request->amount,
            'paid_amount' => 0,
            'balance' => $request->amount,
            'status' => 'unpaid',
            'due_date' => $request->due_date,
            'issued_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Invoice generated successfully!');
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Invoice status updated!');
    }

    public function destroy($id)
    {
        Invoice::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Invoice deleted successfully!');
    }
}
