@extends('layouts.app')
@section('title', 'Generate Report Card')

@section('body')
<div class="flex min-h-screen bg-surface">
    @if(Auth::user()->role->name === 'Teacher')
        @include('partials.teacher_sidebar')
    @else
        @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name)])
    @endif
    
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        
        <div class="p-4 md:p-6 lg:p-8 max-w-6xl mx-auto">
            {{-- Header --}}
            <div class="mb-8">
                <a href="{{ route('report-cards.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-primary mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Report Cards
                </a>
                <h1 class="text-3xl font-bold text-dark mb-2">📋 Generate Report Card</h1>
                <p class="text-gray-500">Review scores and create terminal report for this student</p>
            </div>

            {{-- Student Information Card --}}
            <div class="bg-gradient-to-br from-primary to-primary-dark rounded-2xl p-6 text-white shadow-lg mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-white/20 flex items-center justify-center text-2xl font-bold">
                        {{ substr($student->user->first_name, 0, 1) }}{{ substr($student->user->last_name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-1">{{ $student->user->first_name }} {{ $student->user->last_name }}</h3>
                        <div class="flex items-center gap-4 text-sm text-white/80">
                            <span>📚 {{ $student->schoolClass->name }}</span>
                            <span>🆔 {{ $student->admission_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('report-cards.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input type="hidden" name="class_id" value="{{ $student->class_id }}">

                {{-- Session & Term --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-dark">Academic Period</h3>
                        <p class="text-xs text-gray-500 mt-1">Select the session and term you want to generate report for</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Session *</label>
                                <select id="session_select" name="session_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                    @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ (isset($sessionId) && $sessionId == $session->id) || (!isset($sessionId) && $currentSession && $currentSession->id == $session->id) ? 'selected' : '' }}>
                                        {{ $session->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Term *</label>
                                <select id="term_select" name="term_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                    @foreach($terms as $term)
                                    <option value="{{ $term->id }}" {{ (isset($termId) && $termId == $term->id) || (!isset($termId) && $currentTerm && $currentTerm->id == $term->id) ? 'selected' : '' }}>
                                        {{ $term->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        {{-- Check Results Button --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="text-xs text-blue-700 mb-3">
                                <strong>ℹ️ Important:</strong> Results must be recorded for the selected session and term. Click the button below to load results after changing session/term.
                            </p>
                            <button type="button" onclick="checkResults()" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Load Results for Selected Period
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                function checkResults() {
                    const sessionId = document.getElementById('session_select').value;
                    const termId = document.getElementById('term_select').value;
                    const studentId = {{ $student->id }};
                    window.location.href = `{{ route('report-cards.create') }}?student_id=${studentId}&session_id=${sessionId}&term_id=${termId}`;
                }
                </script>

                {{-- Recorded Results Preview - COLLATION CENTER --}}
                <div class="bg-white rounded-2xl border-2 border-primary/20 overflow-hidden shadow-lg">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-5">
                        <h3 class="font-bold text-white flex items-center gap-2 text-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            📊 Subject Scores - Review & Edit
                        </h3>
                        <p class="text-xs text-blue-100 mt-2">
                            ✨ <strong>Auto-loaded</strong> from Results table - Click "Edit" to update any score before generating report
                        </p>
                    </div>
                    
                    <div class="p-6">
                        @if($existingResults->count() > 0)
                        {{-- Alert: Missing Subject? --}}
                        <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-blue-900 mb-1">📚 Missing a subject?</p>
                                <p class="text-xs text-blue-700 mb-3">If a teacher hasn't recorded their subject yet, they need to go to the Results page to add it.</p>
                                <a href="{{ route('results.index') }}?class_id={{ $student->class_id }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Go to Results to Add Missing Subject
                                </a>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">Subject</th>
                                        <th class="text-center py-3 px-4 text-xs font-bold text-gray-600 uppercase">CA (40)</th>
                                        <th class="text-center py-3 px-4 text-xs font-bold text-gray-600 uppercase">Exam (60)</th>
                                        <th class="text-center py-3 px-4 text-xs font-bold text-gray-600 uppercase">Total (100)</th>
                                        <th class="text-center py-3 px-4 text-xs font-bold text-gray-600 uppercase">Grade</th>
                                        <th class="text-center py-3 px-4 text-xs font-bold text-gray-600 uppercase">Teacher</th>
                                        <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">Remarks</th>
                                        <th class="text-center py-3 px-4 text-xs font-bold text-gray-600 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($existingResults as $result)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50" id="row-{{ $result->id }}">
                                        <td class="py-3 px-4 font-medium text-sm text-dark">{{ $result->subject->name }}</td>
                                        <td class="py-3 px-4 text-center text-sm">
                                            <span class="inline-flex items-center justify-center w-12 h-8 bg-blue-50 text-blue-700 rounded-lg font-semibold score-display" id="ca-display-{{ $result->id }}">
                                                {{ $result->ca_score }}
                                            </span>
                                            <input type="number" min="0" max="40" step="0.01" value="{{ $result->ca_score }}" 
                                                class="hidden w-16 px-2 py-1 border border-blue-300 rounded text-center text-sm font-semibold score-input" 
                                                id="ca-input-{{ $result->id }}" data-result-id="{{ $result->id }}" data-field="ca">
                                        </td>
                                        <td class="py-3 px-4 text-center text-sm">
                                            <span class="inline-flex items-center justify-center w-12 h-8 bg-green-50 text-green-700 rounded-lg font-semibold score-display" id="exam-display-{{ $result->id }}">
                                                {{ $result->exam_score }}
                                            </span>
                                            <input type="number" min="0" max="60" step="0.01" value="{{ $result->exam_score }}" 
                                                class="hidden w-16 px-2 py-1 border border-green-300 rounded text-center text-sm font-semibold score-input" 
                                                id="exam-input-{{ $result->id }}" data-result-id="{{ $result->id }}" data-field="exam">
                                        </td>
                                        <td class="py-3 px-4 text-center text-sm">
                                            <span class="inline-flex items-center justify-center w-12 h-8 bg-purple-50 text-purple-700 rounded-lg font-bold" id="total-display-{{ $result->id }}">
                                                {{ $result->total_score }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center text-sm" id="grade-cell-{{ $result->id }}">
                                            @php
                                                $total = $result->total_score;
                                                if ($total >= 75) {
                                                    $grade = 'A1';
                                                    $gradeClass = 'bg-green-100 text-green-800';
                                                } elseif ($total >= 70) {
                                                    $grade = 'B2';
                                                    $gradeClass = 'bg-green-100 text-green-700';
                                                } elseif ($total >= 65) {
                                                    $grade = 'B3';
                                                    $gradeClass = 'bg-blue-100 text-blue-700';
                                                } elseif ($total >= 60) {
                                                    $grade = 'C4';
                                                    $gradeClass = 'bg-blue-100 text-blue-600';
                                                } elseif ($total >= 55) {
                                                    $grade = 'C5';
                                                    $gradeClass = 'bg-yellow-100 text-yellow-700';
                                                } elseif ($total >= 50) {
                                                    $grade = 'C6';
                                                    $gradeClass = 'bg-yellow-100 text-yellow-600';
                                                } elseif ($total >= 45) {
                                                    $grade = 'D7';
                                                    $gradeClass = 'bg-orange-100 text-orange-700';
                                                } elseif ($total >= 40) {
                                                    $grade = 'E8';
                                                    $gradeClass = 'bg-red-100 text-red-600';
                                                } else {
                                                    $grade = 'F9';
                                                    $gradeClass = 'bg-red-100 text-red-700';
                                                }
                                            @endphp
                                            <span class="inline-flex items-center justify-center w-12 h-8 {{ $gradeClass }} rounded-lg font-bold">
                                                {{ $grade }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center text-xs">
                                            @if($result->recordedBy)
                                                <div class="flex items-center justify-center gap-1">
                                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-indigo-100 text-indigo-700 rounded-full font-bold text-[10px]">
                                                        {{ $result->getTeacherSignature() }}
                                                    </span>
                                                    <span class="text-gray-600">{{ $result->recordedBy->first_name }}</span>
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-xs text-gray-600" id="remarks-cell-{{ $result->id }}">
                                            @if($total >= 75)
                                                Excellent
                                            @elseif($total >= 60)
                                                Good
                                            @elseif($total >= 50)
                                                Fair
                                            @elseif($total >= 40)
                                                Pass
                                            @else
                                                Needs Improvement
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <button type="button" onclick="editScore({{ $result->id }})" 
                                                class="edit-btn px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold hover:bg-amber-200 transition-colors" 
                                                id="edit-btn-{{ $result->id }}">
                                                ✏️ Edit
                                            </button>
                                            <button type="button" onclick="saveScore({{ $result->id }})" 
                                                class="save-btn hidden px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-semibold hover:bg-green-200 transition-colors" 
                                                id="save-btn-{{ $result->id }}">
                                                ✓ Save
                                            </button>
                                            <button type="button" onclick="cancelEdit({{ $result->id }})" 
                                                class="cancel-btn hidden px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-200 transition-colors ml-1" 
                                                id="cancel-btn-{{ $result->id }}">
                                                ✕
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 border-t-2 border-gray-300">
                                        <td class="py-3 px-4 font-bold text-sm text-dark">TOTAL</td>
                                        <td class="py-3 px-4 text-center font-bold text-sm">{{ $existingResults->sum('ca_score') }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-sm">{{ $existingResults->sum('exam_score') }}</td>
                                        <td class="py-3 px-4 text-center font-bold text-sm text-primary">{{ $existingResults->sum('total_score') }}</td>
                                        <td colspan="3" class="py-3 px-4 text-sm text-gray-600">
                                            <strong>Average:</strong> 
                                            {{ $existingResults->count() > 0 ? number_format($existingResults->avg('total_score'), 2) : '0.00' }}%
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Hidden fields to pass results data --}}
                        <input type="hidden" name="average" value="{{ $existingResults->count() > 0 ? round($existingResults->avg('total_score'), 2) : 0 }}">

                        @else
                        {{-- No Results Recorded --}}
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-700 mb-2">No Results Recorded Yet</h4>
                            <p class="text-sm text-gray-500 mb-4">
                                No subject scores found for <strong>{{ $student->user->first_name }} {{ $student->user->last_name }}</strong> in 
                                <strong>{{ $sessions->find(isset($sessionId) ? $sessionId : ($currentSession->id ?? null))->name ?? 'this session' }}</strong> - 
                                <strong>{{ $terms->find(isset($termId) ? $termId : ($currentTerm->id ?? null))->name ?? 'this term' }}</strong>
                            </p>
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 max-w-md mx-auto mb-4">
                                <p class="text-xs text-amber-800 text-left">
                                    <strong>💡 Possible reasons:</strong>
                                </p>
                                <ul class="text-xs text-amber-700 text-left mt-2 space-y-1 list-disc list-inside">
                                    <li>Subject teachers haven't recorded scores yet</li>
                                    <li>Scores were recorded for a different session/term</li>
                                    <li>Try changing the session/term above and clicking "Load Results"</li>
                                </ul>
                            </div>
                            <a href="{{ route('results.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Go to Results Page to Record Scores
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                @if($existingResults->count() > 0)
                {{-- Class Teacher's Comment --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-dark">Class Teacher's Comment</h3>
                        <p class="text-xs text-gray-500 mt-1">Professional remarks on student's performance and behavior</p>
                    </div>
                    <div class="p-6">
                        <textarea name="teacher_comment" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm resize-none focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Enter your professional assessment of this student's academic performance, conduct, and areas for improvement..."></textarea>
                        
                        <div class="mt-4">
                            <p class="text-xs font-semibold text-gray-600 mb-2">Quick Suggestions:</p>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="document.querySelector('[name=teacher_comment]').value = 'Excellent performance. Keep it up!'" class="px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-xs hover:bg-green-100 transition-colors">
                                    Excellent Performance
                                </button>
                                <button type="button" onclick="document.querySelector('[name=teacher_comment]').value = 'Good work. Continue to work hard.'" class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition-colors">
                                    Good Performance
                                </button>
                                <button type="button" onclick="document.querySelector('[name=teacher_comment]').value = 'Fair performance. More effort is needed.'" class="px-3 py-1.5 bg-yellow-50 text-yellow-700 rounded-lg text-xs hover:bg-yellow-100 transition-colors">
                                    Needs Improvement
                                </button>
                                <button type="button" onclick="document.querySelector('[name=teacher_comment]').value = 'Poor performance. Requires serious attention.'" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs hover:bg-red-100 transition-colors">
                                    Poor Performance
                                </button>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-4">
                            <strong>Note:</strong> You can add class teacher comments. Save as draft first, then the class teacher and principal can finalize.
                        </p>
                    </div>
                </div>

                {{-- Principal's Comment --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-dark">Principal's Comment</h3>
                        <p class="text-xs text-gray-500 mt-1">Official remarks from the school principal (optional)</p>
                    </div>
                    <div class="p-6">
                        @if(Auth::user()->role->name === 'Principal')
                        <textarea name="principal_comment" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm resize-none focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Enter principal's remarks and endorsement..."></textarea>
                        @else
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-900 mb-1">Principal Only</p>
                                <p class="text-xs text-blue-700">Only the Principal can add their comment. You can save as draft, and the Principal will add their comment later.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Attendance & Position --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-dark">Additional Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Attendance (days present)</label>
                            <input type="number" name="attendance" min="0" max="200" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm" placeholder="e.g. 85">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Overall Position (optional)</label>
                            <input type="number" name="overall_position" min="1" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm" placeholder="e.g. 1st, 2nd, 3rd...">
                            <p class="text-xs text-gray-500 mt-1">Leave blank to calculate automatically</p>
                        </div>
                    </div>
                </div>

                {{-- Status Selection --}}
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-dark">Report Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-primary transition-colors">
                                <input type="radio" name="status" value="draft" checked class="mt-1 text-primary focus:ring-primary">
                                <div>
                                    <p class="font-semibold text-dark">Draft</p>
                                    <p class="text-xs text-gray-500">Save for review, not visible to students/parents</p>
                                </div>
                            </label>
                            <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-primary transition-colors">
                                <input type="radio" name="status" value="published" class="mt-1 text-primary focus:ring-primary">
                                <div>
                                    <p class="font-semibold text-dark">Published</p>
                                    <p class="text-xs text-gray-500">Make available to students and parents</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-between gap-4 pt-4">
                    <a href="{{ route('report-cards.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-xl hover:shadow-lg transition-all font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Generate Report Card
                    </button>
                </div>
                @endif
            </form>
        </div>
    </main>
</div>

@if($existingResults->count() > 0)
    @include('report-cards._edit-scores-script')
@endif

@endsection
