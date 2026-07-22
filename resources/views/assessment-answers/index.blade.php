@extends('layouts.app')
@section('title', 'Assessment Answers')
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
                    <h1 class="text-3xl font-bold text-dark mb-2">Assessment Answers</h1>
                    <p class="text-gray-500">View and manage student answers to assessment questions</p>
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
                <form method="GET" action="{{ route('assessment-answers.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Filter by Question</label>
                        <select name="question_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <option value="">All Questions</option>
                            @foreach($questions as $q)
                            <option value="{{ $q->id }}" {{ $selectedQuestionId == $q->id ? 'selected' : '' }}>
                                {{ Str::limit($q->question, 50) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Filter by Student</label>
                        <select name="student_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <option value="">All Students</option>
                            @foreach($students as $s)
                            <option value="{{ $s->id }}" {{ $selectedStudentId == $s->id ? 'selected' : '' }}>
                                {{ $s->user->first_name ?? '' }} {{ $s->user->last_name ?? '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Filter</button>
                </form>
            </div>

            {{-- Answers List --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-dark">Student Answers ({{ $answers->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Student</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Question</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Answer</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Score</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Correct</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Submitted</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($answers as $a)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-dark">
                                    {{ $a->student->user->first_name ?? '' }} {{ $a->student->user->last_name ?? '' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($a->question->question ?? 'N/A', 40) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($a->answer_text ?? 'N/A', 40) }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-primary">{{ $a->score ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($a->is_correct === true)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-lg bg-success/10 text-success">✓ Correct</span>
                                    @elseif($a->is_correct === false)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-lg bg-danger/10 text-danger">✗ Wrong</span>
                                    @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-500">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $a->submitted_at?->format('M d, Y') ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('assessment-answers.destroy', $a->id) }}" onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-danger font-semibold hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">No answers found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
