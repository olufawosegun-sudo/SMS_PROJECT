@extends('layouts.app')
@section('title', 'Question Options')
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
                    <h1 class="text-3xl font-bold text-dark mb-2">Question Options</h1>
                    <p class="text-gray-500">Manage answer options for multiple choice questions</p>
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
                <form method="GET" action="{{ route('assessment-options.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[250px]">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Filter by Question</label>
                        <select name="question_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <option value="">All Questions</option>
                            @foreach($questions as $q)
                            <option value="{{ $q->id }}" {{ $selectedQuestionId == $q->id ? 'selected' : '' }}>
                                {{ Str::limit($q->question, 60) }} - {{ $q->assessment->title ?? 'N/A' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Add Option Form --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Add Option</h3>
                    <form method="POST" action="{{ route('assessment-options.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Question</label>
                            <select name="question_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="">Select</option>
                                @foreach($questions as $q)
                                <option value="{{ $q->id }}">{{ Str::limit($q->question, 50) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Option Label</label>
                            <input type="text" name="option_label" required placeholder="e.g. A, B, C, D" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Option Text</label>
                            <textarea name="option_text" required rows="3" placeholder="Enter option text..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
                        </div>
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_correct" value="1" class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm font-semibold text-gray-600">This is the correct answer</span>
                            </label>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Option</button>
                    </form>
                </div>

                {{-- Options List --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-dark">Options ({{ $options->count() }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Label</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Option Text</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Question</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Correct</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($options as $o)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-bold text-primary">{{ $o->option_label }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($o->option_text, 50) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($o->question->question ?? 'N/A', 40) }}</td>
                                    <td class="px-6 py-4">
                                        @if($o->is_correct)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-lg bg-success/10 text-success">✓ Correct</span>
                                        @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-500">Incorrect</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('assessment-options.destroy', $o->id) }}" onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs text-danger font-semibold hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">No options found.</td>
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
