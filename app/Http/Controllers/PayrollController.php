<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Payroll;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $payrolls = Payroll::where('school_id', $school->id)
            ->with('staff.user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all active staff members (not just teachers)
        $staffMembers = Staff::where('school_id', $school->id)
            ->where('status', 'active')
            ->with('user')
            ->orderBy('staff_type')
            ->orderBy('user_id')
            ->get();

        $summary = [
            'total_paid' => $payrolls->sum('net_salary'),
            'this_month' => $payrolls->where('created_at', '>=', now()->startOfMonth())->sum('net_salary'),
            'count' => $payrolls->count(),
        ];

        return view('payroll.index', compact('payrolls', 'staffMembers', 'summary', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staffs,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'deduction' => 'nullable|numeric|min:0',
            'month' => 'required|string',
            'year' => 'required|integer',
        ]);

        $school = Auth::user()->school;
        $allowance = $request->allowance ?? 0;
        $deduction = $request->deduction ?? 0;
        $net = $request->basic_salary + $allowance - $deduction;

        $payroll = Payroll::create([
            'school_id' => $school->id,
            'staff_id' => $request->staff_id,
            'month' => $request->month,
            'year' => $request->year,
            'basic_salary' => $request->basic_salary,
            'allowance' => $allowance,
            'deduction' => $deduction,
            'net_salary' => $net,
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        // Auto-record in Expense ledger so all school activities & disbursements are tracked in Financial Reports
        $staff = Staff::with('user')->find($request->staff_id);
        $staffName = $staff && $staff->user ? ($staff->user->first_name.' '.$staff->user->last_name) : 'Staff #'.$request->staff_id;

        $payrollCategory = ExpenseCategory::firstOrCreate([
            'school_id' => $school->id,
            'name' => 'Staff Salaries & Payroll',
        ]);

        Expense::create([
            'school_id' => $school->id,
            'expense_category_id' => $payrollCategory->id,
            'title' => 'Payroll: '.$staffName.' ('.$request->month.' '.$request->year.')',
            'amount' => $net,
            'expense_date' => now(),
            'description' => 'Disbursed salary to '.$staffName.' - Basic: '.$request->basic_salary.', Allowance: '.$allowance.', Deduction: '.$deduction,
            'approved_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Payroll slip generated and recorded as an expense successfully!');
    }

    public function destroy($id)
    {
        Payroll::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Payroll record removed!');
    }
}
