@extends('layouts.app')
@section('title', 'Alumni Management')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Alumni Management</h1>
                    <p class="text-gray-500">Track graduated students and their current status</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Register Alumni --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <h3 class="text-lg font-bold text-dark mb-4">Register Alumni</h3>
                <form method="POST" action="{{ route('alumni.store') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Student</label>
                        <select name="student_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                            <option value="">Select</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->user->first_name }} {{ $student->user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Graduation Year</label>
                        <input type="number" name="graduation_year" required value="{{ date('Y') }}" min="1900" max="2100" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Occupation</label>
                        <input type="text" name="current_occupation" placeholder="Current job" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Organization</label>
                        <input type="text" name="organization" placeholder="Company" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Email</label>
                        <input type="email" name="email" placeholder="Email" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Register</button>
                </form>
            </div>

            {{-- Alumni List --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Alumni Records ({{ $alumni->count() }})</h3></div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Name</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Year</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Occupation</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Organization</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Contact</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($alumni as $al)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $al->student->user->first_name ?? '' }} {{ $al->student->user->last_name ?? '' }}</td>
                                <td class="px-6 py-4"><span class="px-3 py-1 text-xs font-bold rounded-full bg-accent/10 text-accent-dark">{{ $al->graduation_year }}</span></td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $al->current_occupation ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $al->organization ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $al->email ?? $al->phone ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No alumni records yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
