@extends('layouts.app')

@section('title', 'WAEC Student Payments — ' . ($school->name ?? 'EduWest Africa'))

@section('body')
@php
    $currencySymbol = match(strtolower(Auth::user()->school->country ?? '')) {
        'nigeria' => '₦',
        'ghana' => 'GH₵',
        'kenya' => 'KSh',
        'south africa' => 'R',
        'united kingdom', 'uk' => '£',
        'united states', 'us', 'usa' => '$',
        default => '₦',
    };
@endphp

<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'principal'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
                <div class="text-left">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-dark">WAEC Student Payments</h1>
                    <p class="text-sm text-gray-500 mt-1">Review student & guardian payments submitted for WAEC examination fees</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('principal.waec.payments.pending') }}" class="px-4 py-2.5 bg-amber-500/10 hover:bg-amber-500 text-amber-700 hover:text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Review Pending Payments</span>
                    </a>
                    <a href="{{ route('principal.waec.remittance.index') }}" class="px-5 py-2.5 gradient-primary text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Process WAEC Remittance</span>
                    </a>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-3 text-emerald-700">
                <svg class="w-6 h-6 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Payments List Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-left">
                <div class="p-4 md:p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-extrabold text-dark">All Student Payments</h3>

                    {{-- Search and Filters --}}
                    <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-center gap-3">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search student or ref..." class="px-4 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-primary/20 bg-white">
                            <option value="">All Statuses</option>
                            <option value="submitted" {{ ($filters['status'] ?? '') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="approved" {{ ($filters['status'] ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100">
                                <th class="px-4 md:px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Reference</th>
                                <th class="px-4 md:px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-4 md:px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-4 md:px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-4 md:px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Payment Date</th>
                                <th class="px-4 md:px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 md:px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 md:px-6 py-4 text-xs font-extrabold text-primary">
                                    {{ $payment->payment_reference }}
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    <p class="text-sm font-bold text-dark">{{ $payment->student->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">Class: {{ $payment->candidate->schoolClass->name ?? 'N/A' }}</p>
                                </td>
                                <td class="px-4 md:px-6 py-4 text-sm font-extrabold text-dark">
                                    {{ $currencySymbol }}{{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-xs text-gray-600 font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-xs text-gray-500">
                                    {{ $payment->payment_date->format('M d, Y') }}
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    @php
                                        $badgeColor = match($payment->status) {
                                            'approved' => 'bg-emerald-50 text-emerald-700',
                                            'submitted', 'under_review' => 'bg-amber-50 text-amber-700',
                                            'rejected' => 'bg-rose-50 text-rose-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $badgeColor }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-4 text-center">
                                    <a href="{{ route('principal.waec.payments.show', $payment->id) }}" class="px-3 py-1.5 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    No WAEC student payments found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payments->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $payments->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
