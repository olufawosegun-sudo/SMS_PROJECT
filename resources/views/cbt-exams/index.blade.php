@extends('layouts.app')
@section('title', 'CBT Exams')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="mb-8"><h1 class="text-3xl font-bold text-dark mb-2">Computer-Based Testing</h1><p class="text-gray-500">Create and manage CBT examinations for students</p></div>
            @if(session('success'))<div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl"><p class="text-success font-semibold">{{ session('success') }}</p></div>@endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Create CBT Exam</h3>
                    <form method="POST" action="{{ route('cbt-exams.store') }}" class="space-y-4">
                        @csrf
                        <div><label class="block text-sm font-semibold text-gray-600 mb-2">Title</label><input type="text" name="title" required placeholder="e.g. Mid-Term Math Test" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></div>
                        <div><label class="block text-sm font-semibold text-gray-600 mb-2">Class</label><select name="class_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"><option value="">Select</option>@foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
                        <div><label class="block text-sm font-semibold text-gray-600 mb-2">Subject</label><select name="subject_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"><option value="">Select</option>@foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div><label class="block text-sm font-semibold text-gray-600 mb-2">Duration (min)</label><input type="number" name="duration_minutes" required value="45" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></div>
                            <div><label class="block text-sm font-semibold text-gray-600 mb-2">Questions</label><input type="number" name="total_questions" required value="30" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Create Exam</button>
                    </form>
                </div>

                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">CBT Exam Library</h3></div>
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <p class="text-sm font-semibold mb-1">CBT Module Ready</p>
                        <p class="text-xs">Create your first computer-based test to get started. Questions and student attempts will appear here.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
