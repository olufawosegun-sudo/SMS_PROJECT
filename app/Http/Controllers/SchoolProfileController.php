<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolProfileController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        return view('school-profile.index', compact('school'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'motto' => 'nullable|string|max:200',
            'website' => 'nullable|url|max:200',
        ]);

        $school = Auth::user()->school;
        $school->update($request->only('name', 'address', 'phone', 'email', 'motto', 'website'));

        return redirect()->back()->with('success', 'School profile updated successfully!');
    }
}
