@extends('layouts.app')
@section('title', 'Subjects Curriculum')
@section('body')
@php $userRole = Auth::user()->role->name; @endphp
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar')
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">

            @if(in_array($userRole, ['Student', 'Guardian']))
            {{-- ===================== STUDENT VIEW ===================== --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">📚 My Subjects</h1>
                <p class="text-gray-500">View your enrolled subjects and curriculum</p>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-dark">Subject List</h3>
                    <span class="text-sm text-gray-500">{{ $subjects->count() }} {{ $subjects->count() === 1 ? 'Subject' : 'Subjects' }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Code</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Name</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Category</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($subjects as $subj)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-mono font-bold text-primary">{{ $subj->code }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $subj->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $subj->category ?? 'General' }}</td>
                                <td class="px-6 py-4">
                                    @if($subj->is_core)
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-success/10 text-success uppercase">Core</span>
                                    @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-info/10 text-info uppercase">Elective</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">No subjects available yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @else
            {{-- ===================== ADMIN / TEACHER VIEW ===================== --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Subjects Curriculum</h1>
                    <p class="text-gray-500">Manage subject lists, codes, categories, and core flags</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Create Subject --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Create Subject</h3>
                    <form method="POST" action="{{ route('subjects.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Subject Name</label>
                            <input type="text" name="name" required placeholder="e.g. Mathematics" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Subject Code</label>
                            <input type="text" name="code" required placeholder="e.g. MTH101" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Category</label>
                            <input type="text" name="category" placeholder="e.g. Core, Science, Arts" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_core" id="is_core" class="rounded border-gray-300 text-primary focus:ring-primary/20">
                            <label for="is_core" class="text-sm font-medium text-gray-600">Core Subject (Required for all)</label>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Subject</button>
                    </form>
                </div>

                {{-- Right: Subjects Table --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden h-fit">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Subject Records ({{ $subjects->count() }})</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Code</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Name</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Category</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Core/Elective</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($subjects as $subj)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono font-bold text-primary">{{ $subj->code }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $subj->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $subj->category ?? 'General' }}</td>
                                    <td class="px-6 py-4">
                                        @if($subj->is_core)
                                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-success/10 text-success uppercase">Core</span>
                                        @else
                                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-info/10 text-info uppercase">Elective</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <form method="POST" action="{{ route('subjects.destroy', $subj->id) }}" class="inline-block" onsubmit="return confirm('Delete this subject?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-danger font-semibold hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No subjects created yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
