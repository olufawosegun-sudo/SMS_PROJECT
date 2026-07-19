@extends('layouts.app')
@section('title', 'Academic Sessions')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Academic Sessions</h1>
                    <p class="text-gray-500">Manage school academic calendar years</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Create Session --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Create Session</h3>
                    <form method="POST" action="{{ route('sessions.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Session Name</label>
                            <input type="text" name="name" required placeholder="e.g. 2026/2027" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Start Date</label>
                            <input type="date" name="start_date" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">End Date</label>
                            <input type="date" name="end_date" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_current" id="is_current" class="rounded border-gray-300 text-primary focus:ring-primary/20">
                            <label for="is_current" class="text-sm font-medium text-gray-600">Set as Current Session</label>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Session</button>
                    </form>
                </div>

                {{-- Right: Session List --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Session Calendar</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Name</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Duration</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Terms Count</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($sessions as $session)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $session->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $session->start_date?->format('M Y') ?? 'N/A' }} - {{ $session->end_date?->format('M Y') ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($session->is_current)
                                        <span class="px-3 py-1 text-[10px] font-bold rounded-full bg-success/10 text-success uppercase">Current</span>
                                        @else
                                        <span class="px-3 py-1 text-[10px] font-bold rounded-full bg-gray-150 text-gray-500 uppercase">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $session->terms_count }} Terms</td>
                                    <td class="px-6 py-4 text-sm">
                                        <form method="POST" action="{{ route('sessions.destroy', $session->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-danger hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No academic sessions added yet.</td></tr>
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
