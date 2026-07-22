@extends('layouts.app')
@section('title', 'Edit Report Card')

@section('body')
<div class="flex min-h-screen bg-surface">
    @if(Auth::user()->role->name === 'Teacher')
        @include('partials.teacher_sidebar')
    @else
        @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name)])
    @endif
    
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        
        <div class="p-4 md:p-6 lg:p-8 max-w-4xl mx-auto">
            {{-- Header --}}
            <div class="mb-8">
                <a href="{{ route('report-cards.show', $reportCard->id) }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-primary mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Report Card
                </a>
                
                @if(in_array(Auth::user()->role->name, ['Owner', 'Principal']))
                <div class="mb-6 bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-bold mb-2">👔 Principal's Review & Approval</h2>
                            <p class="text-sm text-blue-100 mb-3">
                                As the Principal, you're reviewing this report card before final publication. 
                                Review the student's performance, teacher's comment, and add your official remarks below.
                            </p>
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 bg-white/20 rounded-lg text-xs font-semibold">
                                    Current Status: <span class="text-yellow-300">{{ ucfirst($reportCard->status) }}</span>
                                </span>
                                @if($reportCard->status === 'draft')
                                <span class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded-lg text-xs font-semibold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Awaiting Your Approval
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <h1 class="text-3xl font-bold text-dark mb-2">✏️ {{ in_array(Auth::user()->role->name, ['Owner', 'Principal']) ? 'Review' : 'Edit' }} Report Card</h1>
                <p class="text-gray-500">{{ in_array(Auth::user()->role->name, ['Owner', 'Principal']) ? 'Review and approve' : 'Update comments and status for' }} {{ $reportCard->student->user->first_name }} {{ $reportCard->student->user->last_name }}</p>
            </div>

            <form method="POST" action="{{ route('report-cards.update', $reportCard->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Student Information Card --}}
                <div class="bg-gradient-to-br from-primary to-primary-dark rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-white/20 flex items-center justify-center text-2xl font-bold">
                            {{ substr($reportCard->student->user->first_name, 0, 1) }}{{ substr($reportCard->student->user->last_name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-1">{{ $reportCard->student->user->first_name }} {{ $reportCard->student->user->last_name }}</h3>
                            <div class="flex items-center gap-4 text-sm text-white/80">
                                <span>📚 {{ $reportCard->schoolClass->name }}</span>
                                <span>📅 {{ $reportCard->session->name }} - {{ $reportCard->term->name }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold">{{ number_format($reportCard->average, 1) }}%</p>
                            <p class="text-xs text-white/70">Average Score</p>
                        </div>
                    </div>
                </div>

                {{-- Student Performance Summary (For Principal's Review) --}}
                @if(in_array(Auth::user()->role->name, ['Owner', 'Principal']))
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Performance Summary (Read-Only)
                        </h3>
                        <p class="text-xs text-purple-100 mt-1">Quick overview of student's performance across all subjects</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-4 gap-4 mb-4">
                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <p class="text-xs text-blue-600 font-semibold mb-1">Average Score</p>
                                <p class="text-2xl font-bold text-blue-700">{{ number_format($reportCard->average, 1) }}%</p>
                            </div>
                            <div class="bg-green-50 rounded-xl p-4 text-center">
                                <p class="text-xs text-green-600 font-semibold mb-1">Position</p>
                                <p class="text-2xl font-bold text-green-700">{{ $reportCard->getPositionSuffix() ?: 'N/A' }}</p>
                            </div>
                            <div class="bg-purple-50 rounded-xl p-4 text-center">
                                <p class="text-xs text-purple-600 font-semibold mb-1">Grade</p>
                                <p class="text-xl font-bold text-purple-700">{{ substr($reportCard->getGradeLabel(), 0, 1) }}</p>
                            </div>
                            <div class="bg-amber-50 rounded-xl p-4 text-center">
                                <p class="text-xs text-amber-600 font-semibold mb-1">Attendance</p>
                                <p class="text-sm font-bold text-amber-700">{{ $reportCard->attendance ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-xs text-gray-600">
                            <strong>ℹ️ Note:</strong> This summary is automatically calculated from the student's recorded results. 
                            To modify scores, go to the Results page.
                        </div>
                    </div>
                </div>
                @endif

                {{-- Teacher's Comment --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-dark">Class Teacher's Comment</h3>
                            <p class="text-xs text-gray-500 mt-1">Professional remarks on student's performance and behavior</p>
                        </div>
                        @if(in_array(Auth::user()->role->name, ['Owner', 'Principal']))
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">Read-Only</span>
                        @endif
                    </div>
                    <div class="p-6">
                        @if(in_array(Auth::user()->role->name, ['Owner', 'Principal']))
                        {{-- Read-only for Principal --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-700">
                                {{ $reportCard->teacher_comment ?: 'No teacher comment provided yet.' }}
                            </p>
                        </div>
                        <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3 flex gap-2 text-xs text-blue-800">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>The class teacher's comment cannot be edited by the Principal. Only the class teacher can modify this section.</span>
                        </div>
                        @else
                        {{-- Editable for Teachers --}}
                        <textarea name="teacher_comment" rows="4" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary resize-none">{{ old('teacher_comment', $reportCard->teacher_comment) }}</textarea>
                        
                        {{-- Quick Comment Suggestions --}}
                        <div class="mt-4">
                            <p class="text-xs font-semibold text-gray-600 mb-2">Quick Suggestions:</p>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="setComment('teacher_comment', 'An excellent student who demonstrates outstanding academic performance and good moral conduct. Keep it up!')" 
                                    class="px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs hover:bg-green-100 transition-colors">
                                    Excellent Performance
                                </button>
                                <button type="button" onclick="setComment('teacher_comment', 'A good student with satisfactory performance. More effort required in weak subjects to improve overall results.')" 
                                    class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition-colors">
                                    Good Performance
                                </button>
                                <button type="button" onclick="setComment('teacher_comment', 'Fair performance but needs significant improvement. Student should pay more attention to studies.')" 
                                    class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg text-xs hover:bg-amber-100 transition-colors">
                                    Needs Improvement
                                </button>
                                <button type="button" onclick="setComment('teacher_comment', 'Poor performance. Student must work harder and seek extra help to improve next term.')" 
                                    class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs hover:bg-red-100 transition-colors">
                                    Poor Performance
                                </button>
                            </div>
                        </div>
                        
                        @if(Auth::user()->role->name === 'Teacher')
                        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3 flex gap-2 text-xs text-blue-800">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>As a teacher, you can add/edit class teacher comments. The class teacher or principal will finalize the report.</span>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>

                {{-- Principal's Comment --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-dark">Principal's Comment</h3>
                            <p class="text-xs text-gray-500 mt-1">Official remarks from the school principal</p>
                        </div>
                        @if(!in_array(Auth::user()->role->name, ['Owner', 'Principal']))
                        <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs font-semibold">Principal Only</span>
                        @endif
                    </div>
                    <div class="p-6">
                        @if(in_array(Auth::user()->role->name, ['Owner', 'Principal']))
                        <textarea name="principal_comment" rows="3" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary resize-none">{{ old('principal_comment', $reportCard->principal_comment) }}</textarea>
                        
                        <div class="mt-4">
                            <p class="text-xs font-semibold text-gray-600 mb-2">Quick Suggestions:</p>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="setComment('principal_comment', 'Excellent performance! Keep up the hard work and maintain this standard.')" 
                                    class="px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs hover:bg-green-100 transition-colors">
                                    Excellent
                                </button>
                                <button type="button" onclick="setComment('principal_comment', 'Good effort! Continue to work hard to achieve better results next term.')" 
                                    class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition-colors">
                                    Good Effort
                                </button>
                                <button type="button" onclick="setComment('principal_comment', 'More effort is needed. Take your studies seriously and work harder next term.')" 
                                    class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg text-xs hover:bg-amber-100 transition-colors">
                                    Needs Effort
                                </button>
                                <button type="button" onclick="setComment('principal_comment', 'Very poor performance. Must see me in my office with parent/guardian.')" 
                                    class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs hover:bg-red-100 transition-colors">
                                    Poor
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-600 italic">
                                {{ $reportCard->principal_comment ?? 'No principal comment yet. The principal will add their comment before publishing.' }}
                            </p>
                        </div>
                        <div class="mt-3 bg-amber-50 border border-amber-200 rounded-lg p-3 flex gap-2 text-xs text-amber-800">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Only the Principal can add or edit their comment. You can only view it.</span>
                        </div>
                        @if(Auth::user()->role->name === 'Teacher' && $reportCard->principal_comment && $reportCard->status === 'draft')
                        <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3 flex gap-2 text-xs text-green-800">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>🎉 The Principal has added their comment. You can now select "Published" status below and update the report to make it available for printing and student viewing.</span>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>

                {{-- Status Selection --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-dark">Report Status</h3>
                    </div>
                    <div class="p-6">
                        @if(in_array(Auth::user()->role->name, ['Principal', 'Owner']) && $reportCard->status === 'draft')
                        {{-- Principal can only approve drafts (draft → approved) --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                            <p class="text-sm text-blue-900 font-semibold">📋 Principal Action Required</p>
                            <p class="text-xs text-blue-700 mt-1">As Principal, you can approve this draft report. Once approved, the teacher will be able to publish it for students and parents.</p>
                        </div>
                        <label class="flex items-center gap-3 p-4 border-2 border-green-500 bg-green-50 rounded-xl cursor-pointer">
                            <input type="radio" name="status" value="approved" checked class="w-4 h-4 text-green-600">
                            <div>
                                <p class="font-semibold text-green-900">✅ Approve Report</p>
                                <p class="text-xs text-green-700">Teacher can then publish it for students/parents</p>
                            </div>
                        </label>
                        @elseif(!in_array(Auth::user()->role->name, ['Principal', 'Owner']) && $reportCard->status === 'approved')
                        {{-- Teacher can publish approved reports (approved → published) --}}
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                            <p class="text-sm text-green-900 font-semibold">✅ Report Approved by Principal</p>
                            <p class="text-xs text-green-700 mt-1">This report has been approved. You can now publish it to make it available for students and parents to view and print.</p>
                        </div>
                        <label class="flex items-center gap-3 p-4 border-2 border-blue-500 bg-blue-50 rounded-xl cursor-pointer">
                            <input type="radio" name="status" value="published" checked class="w-4 h-4 text-blue-600">
                            <div>
                                <p class="font-semibold text-blue-900">📤 Publish Report</p>
                                <p class="text-xs text-blue-700">Make available to students and parents for printing</p>
                            </div>
                        </label>
                        @else
                        {{-- Regular status display --}}
                        <div class="flex gap-4">
                            <label class="flex items-center gap-3 flex-1 p-4 border-2 {{ $reportCard->status === 'draft' ? 'border-primary bg-primary/5' : 'border-gray-200' }} rounded-xl cursor-pointer hover:border-primary/50 transition-colors">
                                <input type="radio" name="status" value="draft" {{ $reportCard->status === 'draft' ? 'checked' : '' }} class="w-4 h-4 text-primary">
                                <div>
                                    <p class="font-semibold text-dark">Draft</p>
                                    <p class="text-xs text-gray-500">Save for review</p>
                                </div>
                            </label>
                            @if($reportCard->status === 'approved')
                            <label class="flex items-center gap-3 flex-1 p-4 border-2 border-blue-500 bg-blue-50 rounded-xl">
                                <input type="radio" name="status" value="approved" checked disabled class="w-4 h-4">
                                <div>
                                    <p class="font-semibold text-blue-900">Approved</p>
                                    <p class="text-xs text-blue-700">Approved by Principal</p>
                                </div>
                            </label>
                            @endif
                            @if($reportCard->status === 'published')
                            <label class="flex items-center gap-3 flex-1 p-4 border-2 border-green-500 bg-green-50 rounded-xl">
                                <input type="radio" name="status" value="published" checked disabled class="w-4 h-4">
                                <div>
                                    <p class="font-semibold text-green-900">Published</p>
                                    <p class="text-xs text-green-700">Available to students/parents</p>
                                </div>
                            </label>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Info Notice --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold mb-1">Note</p>
                        <p>Average score, position, and attendance are automatically calculated and cannot be edited here. To update these values, modify the student's results and attendance records.</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-4 pt-4">
                    <a href="{{ route('report-cards.show', $reportCard->id) }}" 
                        class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-center">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="flex-1 px-6 py-3 {{ 
                            (in_array(Auth::user()->role->name, ['Owner', 'Principal']) && $reportCard->status === 'draft') 
                                ? 'bg-green-600 hover:bg-green-700' 
                                : ((!in_array(Auth::user()->role->name, ['Owner', 'Principal']) && $reportCard->status === 'approved') 
                                    ? 'bg-blue-600 hover:bg-blue-700' 
                                    : 'bg-primary hover:bg-primary-dark')
                        }} text-white rounded-xl transition-colors font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        @if(in_array(Auth::user()->role->name, ['Owner', 'Principal']) && $reportCard->status === 'draft')
                            Approve Report Card
                        @elseif(!in_array(Auth::user()->role->name, ['Owner', 'Principal']) && $reportCard->status === 'approved')
                            Publish Report Card
                        @else
                            Update Report Card
                        @endif
                    </button>
                </div>
            </form>

            {{-- Delete Button (for Owners/Principals) --}}
            @if(in_array(Auth::user()->role->name, ['Owner', 'Principal']))
            <div class="pt-6 border-t border-gray-200 mt-6">
                <form method="POST" action="{{ route('report-cards.destroy', $reportCard->id) }}" onsubmit="return confirm('Are you sure you want to delete this report card? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Report Card
                    </button>
                </form>
            </div>
            @endif
        </div>
    </main>
</div>

<script>
function setComment(fieldName, text) {
    document.querySelector(`textarea[name="${fieldName}"]`).value = text;
}
</script>
@endsection
