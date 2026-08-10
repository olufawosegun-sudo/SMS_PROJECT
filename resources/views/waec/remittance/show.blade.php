@extends('layouts.app')

@section('title', 'WAEC Remittance ' . $remittance->batch_reference . ' — ' . ($school->name ?? 'EduWest Africa'))

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
    $isOwner = Auth::user()->role->name === 'Owner';
    $indexRoute = $isOwner ? route('owner.waec.remittance.index') : route('principal.waec.remittance.index');
@endphp

<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name)])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8 max-w-6xl mx-auto">
            {{-- Back link & Header --}}
            <div class="mb-6">
                <a href="{{ $indexRoute }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-primary transition-colors mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Back to WAEC Remittances</span>
                </a>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="text-left">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-600 font-bold text-xs rounded-full uppercase tracking-wider">WAEC Payment Remitted</span>
                            <span class="text-xs text-gray-400">Batch: {{ $remittance->batch_reference }}</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-black text-dark">{{ $remittance->batch_reference }}</h1>
                        <p class="text-sm text-gray-500 mt-0.5">Recorded on {{ $remittance->created_at->format('M d, Y \a\t h:i A') }} by {{ $remittance->remitter->name ?? 'Administrator' }}</p>
                    </div>

                    <a href="javascript:window.print()" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-dark rounded-xl text-xs font-bold transition-all inline-flex items-center gap-2 self-start">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Print Receipt</span>
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

            {{-- Info Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-left">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-3">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Remitted Amount</p>
                    <p class="text-3xl font-black text-emerald-600">{{ $currencySymbol }}{{ number_format($remittance->total_amount, 2) }}</p>
                    <span class="inline-block px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-md">Paid & Confirmed</span>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Transaction Info</p>
                    <div>
                        <p class="text-xs text-gray-500">WAEC Transaction / Teller Ref:</p>
                        <p class="text-sm font-bold text-dark">{{ $remittance->waec_transaction_reference ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Payment Channel / Bank:</p>
                        <p class="text-sm font-semibold text-gray-700">{{ ucfirst(str_replace('_', ' ', $remittance->payment_method)) }} {{ $remittance->bank_name ? '('.$remittance->bank_name.')' : '' }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Batch Details</p>
                    <div>
                        <p class="text-xs text-gray-500">Academic Session:</p>
                        <p class="text-sm font-bold text-dark">{{ $remittance->session->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Candidates Included:</p>
                        <p class="text-sm font-extrabold text-primary">{{ $remittance->total_candidates_count }} Students</p>
                    </div>
                </div>
            </div>

            @if($remittance->proof_document)
            <div class="mb-8 p-4 bg-primary/5 border border-primary/20 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-3 text-left">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-dark">Proof of Remittance Document Attached</p>
                        <p class="text-xs text-gray-400">Uploaded bank receipt / teller copy</p>
                    </div>
                </div>

                <a href="{{ Storage::url($remittance->proof_document) }}" target="_blank" class="px-4 py-2 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary-dark transition-all">
                    View Document
                </a>
            </div>
            @endif

            {{-- Candidates List Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-left">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-extrabold text-dark">Candidates Covered in this Remittance</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Students status updated to Exam Ready upon remittance</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100">
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Candidate / Student</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Admission No</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Fee Paid</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($remittance->candidates as $candidate)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-extrabold text-sm text-dark">{{ $candidate->student->user->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">
                                    {{ $candidate->student->admission_no ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-700">
                                    {{ $candidate->schoolClass->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-extrabold text-emerald-600">
                                    {{ $currencySymbol }}{{ number_format($candidate->amount_paid, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold">
                                        Exam Ready (Remitted)
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
