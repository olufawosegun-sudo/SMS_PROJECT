<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PublicInvoiceController extends Controller
{
    /**
     * Display the public invoice payment portal for a student/guardian.
     */
    public function show($uuid)
    {
        $invoice = Invoice::where('uuid', $uuid)
            ->with(['school', 'student.user', 'items.feeCategory', 'payments', 'schoolClass'])
            ->firstOrFail();

        $school = $invoice->school;
        $student = $invoice->student;
        $currencySymbol = $school->currency_symbol ?? '₦';

        return view('invoices.public_pay', compact('invoice', 'school', 'student', 'currencySymbol'));
    }

    /**
     * Process an online or bank transfer payment made via the public link.
     */
    public function processPayment(Request $request, $uuid)
    {
        $invoice = Invoice::where('uuid', $uuid)
            ->with(['school', 'student.user'])
            ->firstOrFail();

        $request->validate([
            'amount' => 'required|numeric|min:1|max:'.$invoice->balance,
            'payment_method' => 'required|string|in:card,bank_transfer,paystack,flutterwave,mobile_money,ussd',
            'payer_name' => 'nullable|string|max:255',
            'payer_email' => 'nullable|email|max:255',
            'payer_phone' => 'nullable|string|max:50',
            'transfer_reference' => 'nullable|string|max:100',
        ]);

        $school = $invoice->school;
        $amount = (float) $request->amount;
        $paymentRef = $request->transfer_reference ?: 'PAY-'.strtoupper(Str::random(4)).'-'.date('YmdHis');

        return DB::transaction(function () use ($invoice, $school, $amount, $request, $paymentRef) {
            $payment = Payment::create([
                'school_id' => $school->id,
                'invoice_id' => $invoice->id,
                'student_id' => $invoice->student_id,
                'payment_reference' => $paymentRef,
                'payment_method' => $request->payment_method,
                'gateway' => in_array($request->payment_method, ['paystack', 'flutterwave']) ? $request->payment_method : 'direct',
                'amount' => $amount,
                'currency' => $school->currency_code ?? $invoice->currency ?? 'NGN',
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            $newPaidAmount = (float) $invoice->paid_amount + $amount;
            $newBalance = max(0, (float) $invoice->total_amount - $newPaidAmount);
            $newStatus = $newBalance <= 0.01 ? 'paid' : 'partially_paid';

            $invoice->update([
                'paid_amount' => $newPaidAmount,
                'balance' => $newBalance,
                'status' => $newStatus,
            ]);

            // Notify School Owner & Principal
            $studentName = $invoice->student && $invoice->student->user
                ? ($invoice->student->user->first_name.' '.$invoice->student->user->last_name)
                : 'Student';

            $admins = User::where('school_id', $school->id)
                ->whereHas('role', fn ($q) => $q->whereIn('name', ['Owner', 'Principal', 'Admin', 'Bursar']))
                ->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'school_id' => $school->id,
                    'user_id' => $admin->id,
                    'title' => 'Fee Payment Received',
                    'message' => ($school->currency_symbol ?? '₦').number_format($amount, 2).' was paid for invoice #'.$invoice->invoice_number.' ('.$studentName.'). Ref: '.$paymentRef,
                    'type' => 'payment',
                    'is_read' => false,
                ]);
            }

            return redirect()->route('invoices.public.receipt', $invoice->uuid)
                ->with('success', 'Payment of '.($school->currency_symbol ?? '₦').number_format($amount, 2).' processed successfully!');
        });
    }

    /**
     * View official printable receipt for the invoice payment.
     */
    public function receipt($uuid)
    {
        $invoice = Invoice::where('uuid', $uuid)
            ->with(['school', 'student.user', 'items.feeCategory', 'payments' => fn ($q) => $q->latest(), 'schoolClass'])
            ->firstOrFail();

        $school = $invoice->school;
        $student = $invoice->student;
        $latestPayment = $invoice->payments->first();
        $currencySymbol = $school->currency_symbol ?? '₦';

        return view('invoices.public_receipt', compact('invoice', 'school', 'student', 'latestPayment', 'currencySymbol'));
    }
}
