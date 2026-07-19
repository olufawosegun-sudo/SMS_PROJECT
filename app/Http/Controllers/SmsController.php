<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        // Placeholder: SMS logs would come from an sms_logs table
        $smsLogs = collect();

        return view('sms.index', compact('smsLogs', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:teacher,student,guardian,custom',
            'phone_number' => 'nullable|string',
            'message' => 'required|string|max:160',
        ]);

        // Placeholder: integrate with SMS gateway (e.g. Twilio, Africa's Talking)
        return redirect()->back()->with('success', 'SMS queued for delivery!');
    }
}
