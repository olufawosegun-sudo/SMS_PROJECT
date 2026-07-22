@extends('layouts.app')
@section('title', 'View CBT Exam')

@section('body')
<div class="flex min-h-screen bg-surface">
    @if(Auth::user()->role->name === 'Teacher')
        @include('partials.teacher_sidebar')
    @else
        @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name)])
    @endif
    
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="mb-6 flex items-center gap-3">
                <a href="{{ route('cbt-exams.index') }}" 
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors font-semibold text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to CBT Exams
                </a>
                <h1 class="text-2xl font-bold text-dark">📋 CBT Exam Details</h1>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Exam Details --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Exam Info Card --}}
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold text-white mb-2">{{ $exam->title }}</h2>
                                    <p class="text-indigo-100 text-sm">{{ $exam->schoolClass->name }} • {{ $exam->subject->name }}</p>
                                </div>
                                <span class="px-4 py-2 rounded-full text-sm font-bold {{ $exam->getStatusBadgeClass() }}">
                                    {{ $exam->getStatusLabel() }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 rounded-xl p-4">
                                    <p class="text-xs text-blue-600 font-semibold mb-1">Duration</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ $exam->duration }} <span class="text-sm">min</span></p>
                                </div>
                                <div class="bg-green-50 rounded-xl p-4">
                                    <p class="text-xs text-green-600 font-semibold mb-1">Total Marks</p>
                                    <p class="text-2xl font-bold text-green-900">{{ $exam->total_marks }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm">
                                <div class="flex items-center justify-between py-2 border-b">
                                    <span class="text-gray-600">Session:</span>
                                    <span class="font-semibold">{{ $exam->session->name }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b">
                                    <span class="text-gray-600">Term:</span>
                                    <span class="font-semibold">{{ $exam->term->name }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b">
                                    <span class="text-gray-600">Created By:</span>
                                    <span class="font-semibold">{{ $exam->createdBy->first_name }} {{ $exam->createdBy->last_name }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-gray-600">Created At:</span>
                                    <span class="font-semibold">{{ $exam->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Linked Question Bank & Questions Preview --}}
                    @if($exam->assessment)
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 px-6 py-4 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Exam Questions Preview
                                </h3>
                                <p class="text-indigo-100 text-xs mt-1">From: {{ $exam->assessment->title }}</p>
                            </div>
                            <span class="px-3 py-1.5 bg-white/20 text-white rounded-full text-sm font-bold">
                                {{ $exam->assessment->questions->count() }} Questions
                            </span>
                        </div>

                        <div class="p-6">
                            @if($exam->assessment->questions->count() > 0)
                            <div class="space-y-6">
                                @foreach($exam->assessment->questions as $qIndex => $question)
                                <div class="border border-gray-200 rounded-xl p-5 hover:border-indigo-200 transition-colors">
                                    {{-- Question Header --}}
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-start gap-3">
                                            <span class="flex-shrink-0 w-8 h-8 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center text-sm font-bold">
                                                {{ $qIndex + 1 }}
                                            </span>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $question->question }}</p>
                                                <div class="flex items-center gap-3 mt-1.5">
                                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold
                                                        {{ $question->question_type === 'Multiple Choice' ? 'bg-blue-100 text-blue-700' : '' }}
                                                        {{ $question->question_type === 'True/False' ? 'bg-green-100 text-green-700' : '' }}
                                                        {{ $question->question_type === 'Short Answer' ? 'bg-amber-100 text-amber-700' : '' }}
                                                        {{ $question->question_type === 'Essay' ? 'bg-purple-100 text-purple-700' : '' }}
                                                    ">{{ $question->question_type }}</span>
                                                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold
                                                        {{ ($question->difficulty ?? 'Medium') === 'Easy' ? 'bg-green-100 text-green-700' : '' }}
                                                        {{ ($question->difficulty ?? 'Medium') === 'Medium' ? 'bg-amber-100 text-amber-700' : '' }}
                                                        {{ ($question->difficulty ?? 'Medium') === 'Hard' ? 'bg-red-100 text-red-700' : '' }}
                                                    ">{{ $question->difficulty ?? 'Medium' }}</span>
                                                    <span class="text-[10px] text-gray-500 font-semibold">{{ $question->marks }} mark(s)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Options (if Multiple Choice or True/False) --}}
                                    @if($question->options && $question->options->count() > 0)
                                    <div class="ml-11 space-y-2">
                                        @foreach($question->options as $optIndex => $option)
                                        <div class="flex items-center gap-2 p-2 rounded-lg text-xs
                                            {{ $option->is_correct ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-100' }}">
                                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0
                                                {{ $option->is_correct ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600' }}">
                                                {{ chr(65 + $optIndex) }}
                                            </span>
                                            <span class="{{ $option->is_correct ? 'text-green-800 font-semibold' : 'text-gray-600' }}">
                                                {{ $option->option_text }}
                                            </span>
                                            @if($option->is_correct)
                                            <svg class="w-4 h-4 text-green-600 ml-auto flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-amber-800">No Questions Yet</p>
                                <p class="text-xs text-gray-500 mt-1">The linked assessment has no questions. Please add questions in the Assessment Questions module first.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="bg-amber-50 border-2 border-amber-200 rounded-2xl p-6 text-center">
                        <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-amber-900">No Question Bank Linked</p>
                        <p class="text-xs text-gray-600 mt-1">This exam does not have a linked assessment. Edit the exam to connect it to a Question Bank.</p>
                    </div>
                    @endif

                    {{-- Approval Timeline --}}
                    @if(in_array($exam->status, ['pending_approval', 'approved', 'needs_revision', 'rejected']))
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-dark mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Approval Timeline
                        </h3>

                        <div class="space-y-4">
                            {{-- Submitted --}}
                            @if($exam->submitted_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                    </div>
                                    @if($exam->approved_at || $exam->rejected_at)
                                    <div class="w-0.5 h-12 bg-blue-300"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-4">
                                    <p class="font-semibold text-gray-900">Submitted for Approval</p>
                                    <p class="text-xs text-gray-500">{{ $exam->submitted_at->format('M d, Y h:i A') }}</p>
                                    <p class="text-xs text-gray-600 mt-1">By {{ $exam->submittedBy->first_name ?? 'Teacher' }}</p>
                                </div>
                            </div>
                            @endif

                            {{-- Approved --}}
                            @if($exam->approved_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-green-900">Approved</p>
                                    <p class="text-xs text-gray-500">{{ $exam->approved_at->format('M d, Y h:i A') }}</p>
                                    <p class="text-xs text-gray-600 mt-1">By {{ $exam->approvedBy->first_name ?? 'Principal' }}</p>
                                    @if($exam->principal_comment)
                                    <div class="mt-2 bg-green-50 border border-green-200 rounded-lg p-3">
                                        <p class="text-xs font-semibold text-green-900">Comment:</p>
                                        <p class="text-xs text-green-800 mt-1">{{ $exam->principal_comment }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- Returned for Revision --}}
                            @if($exam->status === 'needs_revision' && $exam->returned_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-orange-900">Returned for Revision</p>
                                    <p class="text-xs text-gray-500">{{ $exam->returned_at->format('M d, Y h:i A') }}</p>
                                    @if($exam->principal_comment)
                                    <div class="mt-2 bg-orange-50 border border-orange-200 rounded-lg p-3">
                                        <p class="text-xs font-semibold text-orange-900">Principal's Comment:</p>
                                        <p class="text-xs text-orange-800 mt-1">{{ $exam->principal_comment }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- Rejected --}}
                            @if($exam->rejected_at)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-red-900">Rejected</p>
                                    <p class="text-xs text-gray-500">{{ $exam->rejected_at->format('M d, Y h:i A') }}</p>
                                    <p class="text-xs text-gray-600 mt-1">By {{ $exam->rejectedBy->first_name ?? 'Principal' }}</p>
                                    @if($exam->rejection_reason)
                                    <div class="mt-2 bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p class="text-xs font-semibold text-red-900">Rejection Reason:</p>
                                        <p class="text-xs text-red-800 mt-1">{{ $exam->rejection_reason }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sidebar: Action Buttons --}}
                <div class="space-y-6">
                    {{-- Teacher Actions --}}
                    @if($userRole === 'Teacher' && $exam->createdBy->id === Auth::id())
                        {{-- Draft or Needs Revision: Edit + Submit --}}
                        @if(in_array($exam->status, ['draft', 'needs_revision']))
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                            <h3 class="text-lg font-bold text-dark mb-4">Teacher Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('cbt-exams.edit', $exam->id) }}" 
                                    class="block w-full text-center px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl transition-colors font-semibold">
                                    ✏️ Edit Exam
                                </a>
                                <form method="POST" action="{{ route('cbt-exams.submit-for-approval', $exam->id) }}" 
                                    onsubmit="return confirm('Submit this exam for Principal approval? You will not be able to edit it until approved or returned.');">
                                    @csrf
                                    <button type="submit" 
                                        class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-colors font-semibold">
                                        🚀 Submit for Approval
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif

                        {{-- Pending Approval: Locked Message --}}
                        @if($exam->status === 'pending_approval')
                        <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl shadow-lg p-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-amber-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-amber-900 mb-2">⏳ Awaiting Principal Review</h3>
                                <p class="text-sm text-amber-700">This exam is locked for editing until the Principal approves or returns it for revision.</p>
                            </div>
                        </div>
                        @endif

                        {{-- Approved: View Only --}}
                        @if($exam->status === 'approved')
                        <div class="bg-green-50 border-2 border-green-300 rounded-2xl shadow-lg p-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-green-900 mb-2">✅ Exam Approved</h3>
                                <p class="text-sm text-green-700">This exam has been approved by the Principal and is ready for scheduling.</p>
                            </div>
                        </div>
                        @endif

                        {{-- Rejected: View Only --}}
                        @if($exam->status === 'rejected')
                        <div class="bg-red-50 border-2 border-red-300 rounded-2xl shadow-lg p-6">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-red-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-red-900 mb-2">❌ Exam Rejected</h3>
                                <p class="text-sm text-red-700">This exam has been rejected by the Principal. You may need to create a new exam.</p>
                            </div>
                        </div>
                        @endif
                    @endif

                    {{-- Principal Actions --}}
                    @if($userRole === 'Principal' && $exam->status === 'pending_approval')
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-dark mb-4">Principal Actions</h3>
                        <div class="space-y-3">
                            {{-- Approve Button --}}
                            <button onclick="document.getElementById('approveModal').classList.remove('hidden')" 
                                class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-colors font-semibold">
                                ✅ Approve Exam
                            </button>

                            {{-- Return for Revision Button --}}
                            <button onclick="document.getElementById('revisionModal').classList.remove('hidden')" 
                                class="w-full px-4 py-3 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white rounded-xl transition-colors font-semibold">
                                🔄 Return for Revision
                            </button>

                            {{-- Reject Button --}}
                            <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                                class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-xl transition-colors font-semibold">
                                ❌ Reject Exam
                            </button>
                        </div>
                    </div>
                    @endif

                    {{-- Owner: Read-Only Notice --}}
                    @if($userRole === 'Owner')
                    <div class="bg-blue-50 border-2 border-blue-300 rounded-2xl shadow-lg p-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-blue-900 mb-2">👁️ Monitoring Mode</h3>
                            <p class="text-sm text-blue-700">You are viewing this exam for oversight purposes only.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Approve Modal --}}
<div id="approveModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">✅ Approve CBT Exam</h3>
        </div>
        <form method="POST" action="{{ route('cbt-exams.approve', $exam->id) }}">
            @csrf
            <div class="p-6">
                <p class="text-gray-700 mb-4">Are you sure you want to approve this exam? The teacher will be notified and the exam will become available for scheduling.</p>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Optional Comment (Optional)</label>
                    <textarea name="principal_comment" rows="3" 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500"
                        placeholder="Add any comments or notes..."></textarea>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" 
                    class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-colors font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                    class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-colors font-semibold">
                    Approve
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Return for Revision Modal --}}
<div id="revisionModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="bg-gradient-to-r from-orange-600 to-amber-600 px-6 py-4 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">🔄 Return for Revision</h3>
        </div>
        <form method="POST" action="{{ route('cbt-exams.return-for-revision', $exam->id) }}">
            @csrf
            <div class="p-6">
                <p class="text-gray-700 mb-4">Provide feedback to help the teacher improve this exam.</p>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Comments (Required) <span class="text-red-500">*</span></label>
                    <textarea name="principal_comment" rows="4" required 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500"
                        placeholder="Explain what needs to be revised..."></textarea>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" onclick="document.getElementById('revisionModal').classList.add('hidden')" 
                    class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-colors font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                    class="flex-1 px-4 py-2 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white rounded-xl transition-colors font-semibold">
                    Return
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-4 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white">❌ Reject CBT Exam</h3>
        </div>
        <form method="POST" action="{{ route('cbt-exams.reject', $exam->id) }}">
            @csrf
            <div class="p-6">
                <p class="text-gray-700 mb-4">This action will permanently reject this exam. Please provide a clear reason.</p>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rejection Reason (Required) <span class="text-red-500">*</span></label>
                    <textarea name="rejection_reason" rows="4" required 
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-500"
                        placeholder="Explain why this exam is being rejected..."></textarea>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" 
                    class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-colors font-semibold">
                    Cancel
                </button>
                <button type="submit" 
                    class="flex-1 px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-xl transition-colors font-semibold">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
