@extends('layouts.app')
@section('title', 'Assessment Questions')
@section('body')
<div class="flex min-h-screen bg-surface">
    @if(Auth::user()->role->name === 'Teacher')
        @include('partials.teacher_sidebar')
    @else
        @include('partials.sidebar', ['role' => 'owner'])
    @endif
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Assessment Questions</h1>
                    <p class="text-gray-500">Manage questions for continuous assessments</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <form method="GET" action="{{ route('assessment-questions.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[250px]">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Filter by Assessment</label>
                        <select name="assessment_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <option value="">All Assessments</option>
                            @foreach($assessments as $a)
                            <option value="{{ $a->id }}" {{ $selectedAssessmentId == $a->id ? 'selected' : '' }}>
                                {{ $a->title }} - {{ $a->schoolClass->name ?? 'N/A' }} - {{ $a->subject->name ?? 'N/A' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Add Question Form --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Add Question</h3>
                    <form method="POST" action="{{ route('assessment-questions.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Assessment</label>
                            <select name="assessment_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="">Select</option>
                                @foreach($assessments as $a)
                                <option value="{{ $a->id }}">{{ $a->title }} - {{ $a->schoolClass->name ?? 'N/A' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Question</label>
                            <textarea name="question" required rows="3" placeholder="Enter question..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Question Type</label>
                            <select name="question_type" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="">Select</option>
                                <option value="Multiple Choice">Multiple Choice</option>
                                <option value="True/False">True/False</option>
                                <option value="Short Answer">Short Answer</option>
                                <option value="Essay">Essay</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Marks</label>
                                <input type="number" name="marks" required step="0.01" value="1" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Difficulty</label>
                                <select name="difficulty" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                    <option value="Easy">Easy</option>
                                    <option value="Medium" selected>Medium</option>
                                    <option value="Hard">Hard</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Question</button>
                    </form>
                </div>

                {{-- Questions List --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-dark">Questions ({{ $questions->count() }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Question</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Assessment</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Type</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Marks</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Difficulty</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($questions as $q)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($q->question, 50) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $q->assessment->title ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $q->question_type }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-primary">{{ $q->marks }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-lg 
                                            {{ $q->difficulty == 'Easy' ? 'bg-success/10 text-success' : '' }}
                                            {{ $q->difficulty == 'Medium' ? 'bg-warning/10 text-warning' : '' }}
                                            {{ $q->difficulty == 'Hard' ? 'bg-danger/10 text-danger' : '' }}">
                                            {{ $q->difficulty }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('assessment-questions.destroy', $q->id) }}" onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs text-danger font-semibold hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">No questions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
