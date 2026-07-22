@extends('layouts.app')
@section('title', 'Edit CBT Exam')

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.teacher_sidebar')
    
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="mb-6 flex items-center gap-3">
                <a href="{{ route('cbt-exams.show', $exam->id) }}" 
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors font-semibold text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Exam Details
                </a>
                <h1 class="text-2xl font-bold text-dark">✏️ Edit CBT Exam</h1>
            </div>

            {{-- Warning if Needs Revision --}}
            @if($exam->status === 'needs_revision' && $exam->principal_comment)
            <div class="bg-orange-50 border-2 border-orange-300 rounded-2xl p-6 mb-8">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-orange-200 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-orange-900 mb-2">⚠️ Principal Requested Revisions</h3>
                        <p class="text-sm text-orange-800 bg-white rounded-lg p-3 border border-orange-200">
                            {{ $exam->principal_comment }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Edit Form --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
                    <h2 class="text-xl font-bold text-white">Exam Settings</h2>
                    <p class="text-sm text-indigo-100 mt-1">Update the exam details below</p>
                </div>

                <form method="POST" action="{{ route('cbt-exams.update', $exam->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-6">
                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Exam Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="{{ old('title', $exam->title) }}" required 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary"
                                placeholder="e.g. Mid-Term Mathematics Test">
                            @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Class --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Class <span class="text-red-500">*</span>
                                </label>
                                <select name="class_id" required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    <option value="">Select Class</option>
                                    @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ old('class_id', $exam->class_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Subject --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <select name="subject_id" required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $s)
                                    <option value="{{ $s->id }}" {{ old('subject_id', $exam->subject_id) == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Question Bank --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Question Bank (Assessment) <span class="text-red-500">*</span>
                            </label>
                            <select name="assessment_id" required 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select Question Bank</option>
                                @foreach($assessments as $assessment)
                                <option value="{{ $assessment->id }}" {{ old('assessment_id', $exam->assessment_id) == $assessment->id ? 'selected' : '' }}>
                                    {{ $assessment->title }} ({{ $assessment->schoolClass->name ?? 'N/A' }} - {{ $assessment->subject->name ?? 'N/A' }})
                                </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Select the assessment containing the questions for this CBT exam.</p>
                            @error('assessment_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Session --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Academic Session <span class="text-red-500">*</span>
                                </label>
                                <select name="session_id" required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    <option value="">Select Session</option>
                                    @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ old('session_id', $exam->session_id) == $session->id ? 'selected' : '' }}>
                                        {{ $session->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('session_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Term --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Term <span class="text-red-500">*</span>
                                </label>
                                <select name="term_id" required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    <option value="">Select Term</option>
                                    @foreach($terms as $term)
                                    <option value="{{ $term->id }}" {{ old('term_id', $exam->term_id) == $term->id ? 'selected' : '' }}>
                                        {{ $term->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('term_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Duration --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Duration (minutes) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="duration" value="{{ old('duration', $exam->duration) }}" 
                                    required min="5" max="300" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary"
                                    placeholder="e.g. 45">
                                @error('duration')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Total Marks --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Total Marks <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="total_marks" value="{{ old('total_marks', $exam->total_marks) }}" 
                                    required min="1" max="200" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary"
                                    placeholder="e.g. 50">
                                @error('total_marks')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row gap-3 justify-between">
                        <a href="{{ route('cbt-exams.show', $exam->id) }}" 
                            class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-colors font-semibold text-center">
                            Cancel
                        </a>
                        <div class="flex gap-3">
                            <button type="submit" 
                                class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-primary to-indigo-600 hover:from-primary-dark hover:to-indigo-700 text-white rounded-xl transition-colors font-semibold">
                                💾 Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Delete Exam (Only for Draft) --}}
            @if($exam->status === 'draft')
            <div class="mt-8 bg-red-50 border-2 border-red-200 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-red-900 mb-2">🗑️ Delete Exam</h3>
                <p class="text-sm text-red-700 mb-4">
                    This action is permanent and cannot be undone. Only draft exams can be deleted.
                </p>
                <form method="POST" action="{{ route('cbt-exams.destroy', $exam->id) }}" 
                    onsubmit="return confirm('Are you sure you want to permanently delete this exam? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-semibold text-sm">
                        Delete Exam
                    </button>
                </form>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
