@extends('layouts.app')

@section('title', 'WAEC Payments Oversight — ' . ($school->name ?? 'EduWest Africa'))

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
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
                <div class="text-left">
                    <span class="px-3 py-1 bg-amber-500/10 text-amber-700 font-bold text-xs rounded-full uppercase">Owner Executive Oversight</span>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-dark mt-1">WAEC Student Payments</h1>
                    <p class="text-sm text-gray-500 mt-1">Comprehensive view of all student WAEC fee payments submitted across the school</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('owner.waec.reports') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-dark font-bold text-xs rounded-xl transition-all">
                        WAEC Financial Reports
                    </a>
                    <a href="{{ route('owner.waec.remittance.index') }}" class="px-5 py-2.5 gradient-primary text-white font-bold text-xs rounded-xl shadow-md transition-all">
                        Process WAEC Remittance
                    </a>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 text-left">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Collected</p>
                    <p class="text-2xl md:text-3xl font-black text-dark mt-1">{{ $currencySymbol }}{{ number_format($statistics['total_collected'] ?? 0, 2) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase">Approved Payments</p>
                    <p class="text-2xl md:text-3xl font-black text-emerald-600 mt-1">{{ $statistics['approved_count'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase">Pending Review</p>
                    <p class="text-2xl md:text-3xl font-black text-amber-600 mt-1">{{ $statistics['pending_count'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Transactions</p>
                    <p class="text-2xl md:text-3xl font-black text-indigo-600 mt-1">{{ $statistics['total_payments'] ?? 0 }}</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-left">
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-extrabold text-dark">Payment Transactions</h3>
                    <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-3">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search student or ref..." class="px-4 py-2 border border-gray-200 rounded-xl text-xs">
                        <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-semibold bg-white">
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
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Ref</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Student</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Method</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 text-xs font-extrabold text-primary">{{ $payment->payment_reference }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-dark">{{ $payment->student->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">{{ $payment->candidate->schoolClass->name ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm font-extrabold text-dark">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4 text-xs text-gray-600 font-medium">{{ ucfirst($payment->payment_method) }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase bg-emerald-50 text-emerald-700">{{ $payment->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No payment records found.</td></tr>
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
