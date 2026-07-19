@extends('layouts.app')
@section('title', 'Student Promotions')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Student Promotions</h1>
                    <p class="text-gray-500">Promote students to the next class level</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Promote Form --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <h3 class="text-lg font-bold text-dark mb-4">Promote a Student</h3>
                <form method="POST" action="{{ route('promotions.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Student</label>
                        <select name="student_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->user->first_name }} {{ $student->user->last_name }} ({{ $student->schoolClass->name ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Promote To</label>
                        <select name="to_class_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Remarks</label>
                        <input type="text" name="remarks" placeholder="Optional remarks" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Promote Student</button>
                </form>
            </div>

            {{-- Promotion History --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-dark">Promotion History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Student</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">From</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">To</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($promotions as $promo)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $promo->student->user->first_name ?? 'N/A' }} {{ $promo->student->user->last_name ?? '' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $promo->fromClass->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-primary">{{ $promo->toClass->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $promo->promotion_date?->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-400">{{ $promo->remarks ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No promotions recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
