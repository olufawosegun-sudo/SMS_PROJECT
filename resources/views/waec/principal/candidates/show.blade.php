@extends('layouts.app')

@section('title', 'WAEC Candidate — ' . ($candidate->student->user->name ?? 'Candidate Details'))

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

        <div class="p-4 md:p-6 lg:p-8 max-w-5xl mx-auto text-left">
            <div class="mb-6">
                <a href="{{ route('principal.waec.candidates') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-primary transition-colors mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Back to Candidates</span>
                </a>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-dark">{{ $candidate->student->user->name ?? 'Student Name' }}</h1>
                        <p class="text-sm text-gray-500">Admission No: {{ $candidate->student->admission_no ?? 'N/A' }} | Class: {{ $candidate->schoolClass->name ?? 'N/A' }}</p>
                    </div>
                    <span class="px-3 py-1 bg-primary/10 text-primary font-bold text-xs rounded-full uppercase">
                        {{ $candidate->status }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Fee Assigned</p>
                    <p class="text-2xl font-black text-dark">{{ $currencySymbol }}{{ number_format($candidate->total_fee, 2) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Amount Paid by Student</p>
                    <p class="text-2xl font-black text-emerald-600">{{ $currencySymbol }}{{ number_format($candidate->amount_paid, 2) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Outstanding Balance</p>
                    <p class="text-2xl font-black text-amber-600">{{ $currencySymbol }}{{ number_format($candidate->balance, 2) }}</p>
                </div>
            </div>

            {{-- Remittance Status Card --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-8">
                <h3 class="text-lg font-bold text-dark mb-4">WAEC Remittance Status</h3>
                @if($candidate->remittance)
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-sm font-extrabold text-emerald-800">Remitted to Official WAEC</p>
                        <p class="text-xs text-emerald-600">Batch Reference: {{ $candidate->remittance->batch_reference }} | Date: {{ $candidate->remittance->payment_date->format('M d, Y') }}</p>
                    </div>
                    <a href="{{ route('principal.waec.remittance.show', $candidate->remittance->id) }}" class="px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-lg hover:bg-emerald-700 transition-all">
                        View Remittance Batch
                    </a>
                </div>
                @else
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-sm font-extrabold text-amber-800">Pending Remittance to WAEC</p>
                        <p class="text-xs text-amber-600">Fee collected in school balance. School needs to submit payment to WAEC.</p>
                    </div>
                    <a href="{{ route('principal.waec.remittance.create') }}" class="px-4 py-2 bg-amber-600 text-white font-bold text-xs rounded-lg hover:bg-amber-700 transition-all">
                        Remit to WAEC
                    </a>
                </div>
                @endif
            </div>

            {{-- Payment History --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-dark">Payment History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100">
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Reference</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Method</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($candidate->payments as $payment)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 text-xs font-bold text-primary">{{ $payment->payment_reference }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-dark">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4 text-xs text-gray-600">{{ ucfirst($payment->payment_method) }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 uppercase">{{ $payment->status }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 text-xs">No payments recorded for this candidate yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
