<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        // Placeholder: email logs
        $emailLogs = collect();

        return view('email.index', compact('emailLogs', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_email' => 'required|email',
            'subject' => 'required|string|max:200',
            'body' => 'required|string',
        ]);

        try {
            Mail::raw($request->body, function ($message) use ($request) {
                $message->to($request->recipient_email)
                    ->subject($request->subject);
            });

            return redirect()->back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: '.$e->getMessage());
        }
    }
}
