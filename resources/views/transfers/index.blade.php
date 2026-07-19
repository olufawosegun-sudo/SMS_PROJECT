@extends('layouts.app')
@section('title', 'Student Transfers')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Student Transfers</h1>
                    <p class="text-gray-500">Record incoming and outgoing student transfers</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Transfer Form --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <h3 class="text-lg font-bold text-dark mb-4">Record a Transfer</h3>
                <form method="POST" action="{{ route('transfers.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Student</label>
                        <select name="student_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->user->first_name }} {{ $student->user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Type</label>
                        <select name="transfer_type" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                            <option value="incoming">Incoming</option>
                            <option value="outgoing">Outgoing</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">School Name</label>
                        <input type="text" name="school_name" required placeholder="Transfer school" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Reason</label>
                        <input type="text" name="reason" required placeholder="Transfer reason" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Record Transfer</button>
                </form>
            </div>

            {{-- Transfer History --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Transfer History</h3></div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Student</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Type</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">School</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Reason</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transfers as $transfer)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $transfer->student->user->first_name ?? '' }} {{ $transfer->student->user->last_name ?? '' }}</td>
                                <td class="px-6 py-4"><span class="px-3 py-1 text-xs font-bold rounded-full {{ $transfer->transfer_type === 'incoming' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning' }} uppercase">{{ $transfer->transfer_type }}</span></td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $transfer->school_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $transfer->reason }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $transfer->transfer_date?->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No transfers recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
