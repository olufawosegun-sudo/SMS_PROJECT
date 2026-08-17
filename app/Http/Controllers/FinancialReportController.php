<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class FinancialReportController extends Controller
{
    /**
     * Display the financial report with profit/loss summary.
     */
    public function index()
    {
        $school = Auth::user()->school;

        $totalRevenue = Payment::where('school_id', $school->id)->sum('amount');
        $totalExpenses = Expense::where('school_id', $school->id)->sum('amount');
        $totalInvoiced = Invoice::where('school_id', $school->id)->sum('total_amount');
        $totalOutstanding = Invoice::where('school_id', $school->id)->whereIn('status', ['unpaid', 'partially_paid'])->sum('balance');

        // Profit/Loss calculation
        $netProfit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 1) : 0;
        $collectionRate = $totalInvoiced > 0 ? round(($totalRevenue / $totalInvoiced) * 100, 1) : 0;

        $monthlyRevenue = Payment::where('school_id', $school->id)
            ->where('paid_at', '>=', now()->startOfMonth())
            ->sum('amount');
        $monthlyExpenses = Expense::where('school_id', $school->id)
            ->where('expense_date', '>=', now()->startOfMonth())
            ->sum('amount');

        $recentPayments = Payment::where('school_id', $school->id)
            ->with('student.user')
            ->orderBy('paid_at', 'desc')
            ->take(10)
            ->get();

        $recentExpenses = Expense::where('school_id', $school->id)
            ->orderBy('expense_date', 'desc')
            ->take(10)
            ->get();

        $expensesByCategory = Expense::where('expenses.school_id', $school->id)
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->selectRaw('expense_categories.name as category, SUM(expenses.amount) as total')
            ->groupBy('expense_categories.name')
            ->orderByDesc('total')
            ->get();

        return view('financial-reports.index', compact(
            'totalRevenue', 'totalExpenses', 'totalInvoiced', 'totalOutstanding',
            'netProfit', 'profitMargin', 'collectionRate',
            'monthlyRevenue', 'monthlyExpenses',
            'recentPayments', 'recentExpenses', 'expensesByCategory', 'school'
        ));
    }
}
