<?php

namespace App\Http\Controllers;

use App\Models\FeeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeeCategoryController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $categories = FeeCategory::where('school_id', $school->id)->orderBy('name')->get();

        return view('fee-categories.index', compact('categories', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        $school = Auth::user()->school;

        FeeCategory::create([
            'school_id' => $school->id,
            'name' => $request->name,
            'amount' => $request->amount,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Fee category created successfully!');
    }

    public function update(Request $request, $id)
    {
        $category = FeeCategory::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
        ]);

        $category->update($request->only('name', 'amount', 'description'));

        return redirect()->back()->with('success', 'Fee category updated successfully!');
    }

    public function destroy($id)
    {
        FeeCategory::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Fee category deleted successfully!');
    }
}
