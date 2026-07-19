<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $expenses = Expense::where('school_id', $school->id)
            ->with('category')
            ->orderBy('expense_date', 'desc')
            ->get();

        $summary = [
            'total' => $expenses->sum('amount'),
            'this_month' => $expenses->where('expense_date', '>=', now()->startOfMonth())->sum('amount'),
            'count' => $expenses->count(),
        ];

        return view('expenses.index', compact('expenses', 'summary', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:50',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $school = Auth::user()->school;

        $expenseCategory = ExpenseCategory::firstOrCreate([
            'school_id' => $school->id,
            'name' => $request->category,
        ]);

        Expense::create([
            'school_id' => $school->id,
            'expense_category_id' => $expenseCategory->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'description' => $request->description,
            'approved_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Expense recorded successfully!');
    }

    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Expense deleted successfully!');
    }
}
