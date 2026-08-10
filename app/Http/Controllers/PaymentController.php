<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $payments = Payment::where('school_id', $school->id)
            ->with(['student.user', 'invoice'])
            ->orderBy('paid_at', 'desc')
            ->get();
        $students = Student::where('school_id', $school->id)->where('status', 'active')->with('user')->get();
        $invoices = Invoice::where('school_id', $school->id)->where('status', '!=', 'paid')->with('student.user')->get();

        $summary = [
            'total_collected' => $payments->sum('amount'),
            'this_month' => $payments->where('paid_at', '>=', now()->startOfMonth())->sum('amount'),
            'count' => $payments->count(),
        ];

        return view('payments.index', compact('payments', 'students', 'invoices', 'summary', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        $school = Auth::user()->school;

        Payment::create([
            'school_id' => $school->id,
            'student_id' => $request->student_id,
            'invoice_id' => $request->invoice_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'paid_at' => now(),
            'payment_reference' => 'PAY-'.strtoupper(uniqid()),
            'received_by' => Auth::id(),
            'status' => 'completed',
        ]);

        // Update invoice status if linked
        $invoice = Invoice::find($request->invoice_id);
        if ($invoice) {
            $totalPaid = Payment::where('invoice_id', $invoice->id)->sum('amount');
            $invoice->update([
                'paid_amount' => $totalPaid,
                'balance' => max(0, $invoice->total_amount - $totalPaid),
                'status' => $totalPaid >= $invoice->total_amount ? 'paid' : 'partially_paid',
            ]);
        }

        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Payment deleted successfully!');
    }
}
