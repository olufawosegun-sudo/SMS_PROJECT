@extends('layouts.app')
@section('title', 'Assessments')
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
                    <h1 class="text-3xl font-bold text-dark mb-2">Continuous Assessments</h1>
                    <p class="text-gray-500">Manage CA tests, quizzes, and graded assignments</p>
                </div>
            </div>
            @if(session('success'))<div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3"><svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-success font-semibold">{{ session('success') }}</p></div>@endif

            {{-- Filter --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <form method="GET" action="{{ route('assessments.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Filter by Class</label>
                        <select name="class_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <option value="">All Classes</option>
                            @foreach($classes as $c)<option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Add Assessment</h3>
                    <form method="POST" action="{{ route('assessments.store') }}" class="space-y-4">
                        @csrf
                        <div><label class="block text-sm font-semibold text-gray-600 mb-2">Title</label><input type="text" name="title" required placeholder="e.g. CA Test 1" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></div>
                        <div><label class="block text-sm font-semibold text-gray-600 mb-2">Class</label><select name="class_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"><option value="">Select</option>@foreach($classes as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
                        <div><label class="block text-sm font-semibold text-gray-600 mb-2">Subject</label><select name="subject_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"><option value="">Select</option>@foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select></div>
                        <div><label class="block text-sm font-semibold text-gray-600 mb-2">Teacher</label><select name="teacher_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"><option value="">Select</option>@foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->user->first_name }} {{ $t->user->last_name }}</option>@endforeach</select></div>
                        <div class="grid grid-cols-2 gap-3">
                            <div><label class="block text-sm font-semibold text-gray-600 mb-2">Max Score</label><input type="number" name="max_score" required value="20" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></div>
                            <div><label class="block text-sm font-semibold text-gray-600 mb-2">Weight (%)</label><input type="number" name="weight" required value="30" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Assessment</button>
                    </form>
                </div>

                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Assessment Records ({{ $assessments->count() }})</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100"><tr><th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Title</th><th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Class</th><th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Subject</th><th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Max Score</th><th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Weight</th><th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th></tr></thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($assessments as $a)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $a->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $a->schoolClass->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $a->subject->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-primary">{{ $a->max_score }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $a->weight }}%</td>
                                    <td class="px-6 py-4"><form method="POST" action="{{ route('assessments.destroy', $a->id) }}" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs text-danger font-semibold hover:underline">Delete</button></form></td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No assessments found.</td></tr>
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
