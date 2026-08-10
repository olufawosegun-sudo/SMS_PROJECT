@extends('layouts.app')

@section('title', 'WAEC Remittance & Payment to WAEC — ' . ($school->name ?? 'EduWest Africa'))

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
    $createRoute = $isOwner ? route('owner.waec.remittance.create') : route('principal.waec.remittance.create');
@endphp

<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name)])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
                <div class="text-left">
                    <div class="flex items-center gap-3 mb-1">
                        <span class="px-3 py-1 bg-primary/10 text-primary font-bold text-xs rounded-full uppercase tracking-wider">Official Remittance</span>
                        <span class="text-xs text-gray-400">School WAEC Portal</span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-dark">WAEC Payment & Remittance</h1>
                    <p class="text-sm text-gray-500 mt-1">View collected student fees and process official payments to WAEC</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ $createRoute }}" class="px-5 py-3 gradient-primary hover:opacity-95 text-white font-bold rounded-xl shadow-lg shadow-primary/25 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
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

            @if(session('error'))
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center gap-3 text-rose-700">
                <svg class="w-6 h-6 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-primary/10 text-primary">From Students</span>
                    </div>
                    <p class="text-2xl md:text-3xl font-black text-dark text-left">{{ $currencySymbol }}{{ number_format($summary['total_collected'], 2) }}</p>
                    <p class="text-xs text-gray-400 text-left mt-1">Total Student Payments Approved</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-600">To WAEC</span>
                    </div>
                    <p class="text-2xl md:text-3xl font-black text-emerald-600 text-left">{{ $currencySymbol }}{{ number_format($summary['total_remitted'], 2) }}</p>
                    <p class="text-xs text-gray-400 text-left mt-1">Total Fees Remitted to WAEC</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-600">Pending Remittance</span>
                    </div>
                    <p class="text-2xl md:text-3xl font-black text-amber-600 text-left">{{ $currencySymbol }}{{ number_format($summary['unremitted_balance'], 2) }}</p>
                    <p class="text-xs text-gray-400 text-left mt-1">Unremitted Candidate Exam Fees</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-indigo-500/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-indigo-500/10 text-indigo-600">Candidates Status</span>
                    </div>
                    <p class="text-2xl md:text-3xl font-black text-indigo-600 text-left">{{ $summary['remitted_candidates'] }} / {{ $summary['paid_candidates'] }}</p>
                    <p class="text-xs text-gray-400 text-left mt-1">Candidates Remitted vs Paid School</p>
                </div>
            </div>

            {{-- Remittances Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 md:p-6 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="text-left">
                        <h3 class="text-lg font-extrabold text-dark">WAEC Payment Batches & Receipts</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Historical records of payments remitted to official WAEC</p>
                    </div>

                    {{-- Session Filter --}}
                    <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
                        <select name="session_id" onchange="this.form.submit()" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-primary/20 bg-white">
                            <option value="">All Academic Sessions</option>
                            @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ $sessionId == $session->id ? 'selected' : '' }}>
                                {{ $session->name }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100">
                                <th class="px-4 md:px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Batch Reference</th>
                                <th class="px-4 md:px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Session</th>
                                <th class="px-4 md:px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Candidates</th>
                                <th class="px-4 md:px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-4 md:px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">WAEC Ref / Teller</th>
                                <th class="px-4 md:px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Payment Date</th>
                                <th class="px-4 md:px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Processed By</th>
                                <th class="px-4 md:px-6 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($remittances as $remittance)
                            @php
                                $showRoute = $isOwner
                                    ? route('owner.waec.remittance.show', $remittance->id)
                                    : route('principal.waec.remittance.show', $remittance->id);
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 md:px-6 py-4 text-left">
                                    <span class="font-extrabold text-sm text-primary">{{ $remittance->batch_reference }}</span>
                                </td>
                                <td class="px-4 md:px-6 py-4 text-left text-xs font-semibold text-gray-700">
                                    {{ $remittance->session->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-left">
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full font-bold text-xs">
                                        {{ $remittance->total_candidates_count }} Students
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-4 text-left text-sm font-extrabold text-dark">
                                    {{ $currencySymbol }}{{ number_format($remittance->total_amount, 2) }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-left text-xs font-medium text-gray-600">
                                    {{ $remittance->waec_transaction_reference ?? 'N/A' }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-left text-xs text-gray-500">
                                    {{ $remittance->payment_date->format('M d, Y') }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-left text-xs text-gray-700 font-medium">
                                    {{ $remittance->remitter->name ?? 'Administrator' }}
                                </td>
                                <td class="px-4 md:px-6 py-4 text-center">
                                    <a href="{{ $showRoute }}" class="px-3 py-1.5 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1">
                                        <span>View Details</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-40 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-base font-bold text-gray-600">No WAEC Remittance Payments Recorded Yet</p>
                                    <p class="text-xs text-gray-400 mt-1 mb-4">Select paid candidates and process remittance to WAEC once student payments are approved.</p>
                                    <a href="{{ $createRoute }}" class="px-5 py-2.5 gradient-primary text-white text-xs font-bold rounded-xl shadow-md inline-flex items-center gap-2">
                                        <span>Record First WAEC Remittance</span>
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($remittances->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $remittances->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
