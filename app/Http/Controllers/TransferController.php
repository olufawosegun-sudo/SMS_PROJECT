<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $students = Student::where('school_id', $school->id)->with('user')->get();
        $transfers = StudentTransfer::with('student.user')
            ->whereHas('student', function ($q) use ($school) {
                $q->where('school_id', $school->id);
            })
            ->orderBy('transfer_date', 'desc')
            ->get();

        return view('transfers.index', compact('students', 'transfers', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'transfer_type' => 'required|in:incoming,outgoing',
            'school_name' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
        ]);

        StudentTransfer::create([
            'student_id' => $request->student_id,
            'transfer_type' => $request->transfer_type,
            'school_name' => $request->school_name,
            'reason' => $request->reason,
            'transfer_date' => now(),
            'approved_by' => Auth::id(),
        ]);

        if ($request->transfer_type === 'outgoing') {
            $student = Student::findOrFail($request->student_id);
            $student->update(['status' => 'transferred']);
        }

        return redirect()->back()->with('success', 'Student transfer recorded successfully!');
    }
}
