@extends('layouts.app')
@section('title', 'CBT Exams')

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
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">💻 Computer-Based Testing (CBT)</h1>
                <p class="text-gray-500">
                    @if($userRole === 'Teacher')
                        Create, manage, and submit CBT examinations for approval
                    @elseif($userRole === 'Principal')
                        Review and approve CBT examinations submitted by teachers
                    @else
                        Monitor CBT examination workflow and system usage
                    @endif
                </p>
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

            {{-- Principal/Owner: Pending Approvals Banner --}}
            @if(in_array($userRole, ['Principal', 'Owner']) && $statusCounts['pending_approval'] > 0)
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg mb-8">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold mb-2">⚠️ {{ $statusCounts['pending_approval'] }} CBT Exam(s) Awaiting Your Approval</h2>
                        <p class="text-sm text-amber-50 mb-3">
                            @if($userRole === 'Principal')
                                Review the submitted exams below, verify questions and settings, then approve or return for revision.
                            @else
                                These exams are pending Principal approval. You can monitor their status here.
                            @endif
                        </p>
                        @if($userRole === 'Principal')
                        <a href="{{ route('cbt-exams.index', ['status' => 'pending_approval']) }}" 
                            class="inline-block px-6 py-2 bg-white text-amber-700 rounded-lg hover:bg-amber-50 transition-colors font-semibold text-sm">
                            Review Pending Exams →
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Status Filter Bar --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h3 class="text-white font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter by Status
                    </h3>
                    <p class="text-indigo-100 text-xs mt-1">View exams by their approval status</p>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('cbt-exams.index') }}" 
                            class="flex-1 min-w-[140px] px-4 py-3 rounded-xl font-semibold transition-all {{ !$statusFilter ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg scale-105' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                            <div class="text-center">
                                <p class="text-sm">All Exams</p>
                                <p class="text-2xl font-bold mt-1">{{ $exams->count() }}</p>
                            </div>
                        </a>
                        <a href="{{ route('cbt-exams.index', ['status' => 'draft']) }}" 
                            class="flex-1 min-w-[140px] px-4 py-3 rounded-xl font-semibold transition-all {{ $statusFilter === 'draft' ? 'bg-gradient-to-r from-gray-500 to-gray-600 text-white shadow-lg scale-105' : 'bg-gray-50 text-gray-700 hover:bg-gray-100' }}">
                            <div class="text-center">
                                <p class="text-sm">Draft</p>
                                <p class="text-2xl font-bold mt-1">{{ $statusCounts['draft'] }}</p>
                            </div>
                        </a>
                        <a href="{{ route('cbt-exams.index', ['status' => 'pending_approval']) }}" 
                            class="flex-1 min-w-[140px] px-4 py-3 rounded-xl font-semibold transition-all {{ $statusFilter === 'pending_approval' ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg scale-105' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                            <div class="text-center">
                                <p class="text-sm">Pending</p>
                                <p class="text-2xl font-bold mt-1">{{ $statusCounts['pending_approval'] }}</p>
                            </div>
                            @if($statusCounts['pending_approval'] > 0)
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold animate-pulse">!</div>
                            @endif
                        </a>
                        <a href="{{ route('cbt-exams.index', ['status' => 'approved']) }}" 
                            class="flex-1 min-w-[140px] px-4 py-3 rounded-xl font-semibold transition-all {{ $statusFilter === 'approved' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg scale-105' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                            <div class="text-center">
                                <p class="text-sm">Approved</p>
                                <p class="text-2xl font-bold mt-1">{{ $statusCounts['approved'] }}</p>
                            </div>
                        </a>
                        <a href="{{ route('cbt-exams.index', ['status' => 'needs_revision']) }}" 
                            class="flex-1 min-w-[140px] px-4 py-3 rounded-xl font-semibold transition-all {{ $statusFilter === 'needs_revision' ? 'bg-gradient-to-r from-orange-600 to-red-500 text-white shadow-lg scale-105' : 'bg-orange-50 text-orange-700 hover:bg-orange-100' }}">
                            <div class="text-center">
                                <p class="text-sm">Revision</p>
                                <p class="text-2xl font-bold mt-1">{{ $statusCounts['needs_revision'] }}</p>
                            </div>
                        </a>
                        <a href="{{ route('cbt-exams.index', ['status' => 'rejected']) }}" 
                            class="flex-1 min-w-[140px] px-4 py-3 rounded-xl font-semibold transition-all {{ $statusFilter === 'rejected' ? 'bg-gradient-to-r from-red-600 to-rose-600 text-white shadow-lg scale-105' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                            <div class="text-center">
                                <p class="text-sm">Rejected</p>
                                <p class="text-2xl font-bold mt-1">{{ $statusCounts['rejected'] }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Create Form (Teachers Only) --}}
                @if($userRole === 'Teacher')
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit shadow-lg">
                    <h3 class="text-lg font-bold text-dark mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create New CBT Exam
                    </h3>
                    <form method="POST" action="{{ route('cbt-exams.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Exam Title</label>
                            <input type="text" name="title" required placeholder="e.g. Mid-Term Math Test" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Class</label>
                            <select name="class_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select Class</option>
                                @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Subject</label>
                            <select name="subject_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Question Bank (Assessment) <span class="text-red-500">*</span></label>
                            <select name="assessment_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select Question Bank</option>
                                @foreach($assessments as $assessment)
                                <option value="{{ $assessment->id }}">{{ $assessment->title }} ({{ $assessment->schoolClass->name ?? 'N/A' }} - {{ $assessment->subject->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Select the assessment containing the questions for this CBT exam.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Session</label>
                            <select name="session_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select Session</option>
                                @foreach($sessions as $session)
                                <option value="{{ $session->id }}">{{ $session->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Term</label>
                            <select name="term_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select Term</option>
                                @foreach($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Duration (min)</label>
                                <input type="number" name="duration" required value="45" min="5"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Total Marks</label>
                                <input type="number" name="total_marks" required value="50" min="1"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-primary to-indigo-600 text-white rounded-xl hover:from-primary-dark hover:to-indigo-700 transition-colors font-semibold text-sm shadow-lg">
                            Create Exam (Draft)
                        </button>
                    </form>
                </div>
                @endif

                {{-- Exams List --}}
                <div class="{{ $userRole === 'Teacher' ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-lg">
                        <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-5">
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                CBT Exam Library
                                @if($statusFilter)
                                <span class="text-sm text-gray-300">- {{ ucwords(str_replace('_', ' ', $statusFilter)) }}</span>
                                @endif
                            </h3>
                            <p class="text-sm text-gray-300 mt-1">{{ $exams->count() }} exam(s) found</p>
                        </div>

                        @if($exams->count() > 0)
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($exams as $exam)
                                <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 rounded-xl p-5 hover:shadow-xl transition-all">
                                    {{-- Status Badge --}}
                                    <div class="flex items-start justify-between mb-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $exam->getStatusBadgeClass() }}">
                                            {{ $exam->getStatusLabel() }}
                                        </span>
                                        @if($exam->status === 'needs_revision')
                                        <span class="px-2 py-1 bg-orange-500 text-white rounded text-xs font-bold animate-pulse">
                                            ACTION REQUIRED
                                        </span>
                                        @endif
                                    </div>

                                    {{-- Exam Title --}}
                                    <h4 class="font-bold text-lg text-gray-900 mb-2">{{ $exam->title }}</h4>
                                    
                                    {{-- Details --}}
                                    <div class="space-y-1 text-xs text-gray-600 mb-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            {{ $exam->schoolClass->name }} • {{ $exam->subject->name }}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $exam->duration }} minutes • {{ $exam->total_marks }} marks
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Created by {{ $exam->createdBy->first_name ?? 'System' }}
                                        </div>
                                        @if($exam->assessment)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            <span class="text-indigo-600 font-semibold">📝 {{ $exam->assessment->title }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Principal Comment (if exists) --}}
                                    @if($exam->principal_comment && in_array($exam->status, ['needs_revision', 'rejected']))
                                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
                                        <p class="text-xs font-semibold text-orange-900 mb-1">💬 Principal's Comment:</p>
                                        <p class="text-xs text-orange-800">{{ $exam->principal_comment }}</p>
                                    </div>
                                    @endif

                                    {{-- Rejection Reason (if exists) --}}
                                    @if($exam->rejection_reason && $exam->status === 'rejected')
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                        <p class="text-xs font-semibold text-red-900 mb-1">❌ Rejection Reason:</p>
                                        <p class="text-xs text-red-800">{{ $exam->rejection_reason }}</p>
                                    </div>
                                    @endif

                                    {{-- Action Button --}}
                                    <a href="{{ route('cbt-exams.show', $exam->id) }}" 
                                        class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg transition-colors font-semibold text-sm">
                                        View Details →
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div class="p-12 text-center text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm font-semibold mb-1">No Exams Found</p>
                            <p class="text-xs">
                                @if($statusFilter)
                                    No exams with "{{ ucwords(str_replace('_', ' ', $statusFilter)) }}" status.
                                @else
                                    Create your first CBT exam to get started.
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
