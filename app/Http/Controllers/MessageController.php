<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        // Placeholder: messages would come from a messages table
        $messages = collect();

        return view('messages.index', compact('messages', 'school'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:teacher,student,guardian,all',
            'subject' => 'required|string|max:200',
            'body' => 'required|string',
        ]);

        // Placeholder: store in messages table when migration is created
        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}
