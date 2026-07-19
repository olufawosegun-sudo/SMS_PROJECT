<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $school = Auth::user()->school;
        $selectedDate = $request->get('date', now()->toDateString());

        // Get all active staff members (not just teachers)
        $staffMembers = Staff::where('school_id', $school->id)
            ->where('status', 'active')
            ->with('user')
            ->orderBy('staff_type')
            ->orderBy('user_id')
            ->get();

        // Get attendance records for the date
        $attendance = Attendance::where('school_id', $school->id)
            ->where('attendance_date', $selectedDate)
            ->with(['staff.user', 'recorder', 'approver'])
            ->get();

        $summary = [
            'total' => $staffMembers->count(),
            'present' => $attendance->where('status', 'present')->count(),
            'absent' => $attendance->where('status', 'absent')->count(),
            'late' => $attendance->where('status', 'late')->count(),
            'on_leave' => $attendance->where('status', 'on_leave')->count(),
        ];

        return view('staff-attendance.index', compact('staffMembers', 'attendance', 'selectedDate', 'summary', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'staff' => 'required|array',
            'staff.*.staff_id' => 'required|exists:staffs,id',
            'staff.*.status' => 'required|in:present,absent,late,on_leave',
            'staff.*.late_minutes' => 'nullable|integer|min:0',
            'staff.*.remark' => 'nullable|string|max:500',
        ]);

        $school = Auth::user()->school;

        foreach ($request->staff as $entry) {
            Attendance::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'staff_id' => $entry['staff_id'],
                    'attendance_date' => $request->attendance_date,
                ],
                [
                    'status' => $entry['status'],
                    'late_minutes' => $entry['late_minutes'] ?? null,
                    'remark' => $entry['remark'] ?? null,
                    'recorded_by' => Auth::id(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Staff attendance recorded successfully!');
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        
        // Only allow deletion if user has permission (owner/admin)
        if (Auth::user()->role->name !== 'Owner') {
            return redirect()->back()->with('error', 'You do not have permission to delete attendance records.');
        }

        $attendance->delete();
        return redirect()->back()->with('success', 'Attendance record deleted successfully!');
    }
}
