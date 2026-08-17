@extends('layouts.app')

@section('title', 'WAEC Candidates Oversight — ' . ($school->name ?? 'EduWest Africa'))

@section('body')
@php
    $currencySymbol = Auth::user()->school->currency_symbol ?? $school->currency_symbol ?? '₦';
@endphp

<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
                <div class="text-left">
                    <span class="px-3 py-1 bg-primary/10 text-primary font-bold text-xs rounded-full uppercase">Owner Executive Oversight</span>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-dark mt-1">WAEC Registered Candidates</h1>
                    <p class="text-sm text-gray-500 mt-1">Monitor candidate registrations, fee payment progress, and WAEC remittance status</p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('owner.waec.remittance.index') }}" class="px-5 py-2.5 gradient-primary text-white font-bold text-xs rounded-xl shadow-md transition-all">
                        Process WAEC Remittance
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden text-left">
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-extrabold text-dark">Candidates List</h3>
                    <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-3">
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search candidate..." class="px-4 py-2 border border-gray-200 rounded-xl text-xs">
                        <select name="payment_status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-semibold bg-white">
                            <option value="">All Payment Statuses</option>
                            <option value="paid" {{ ($filters['payment_status'] ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="partial" {{ ($filters['payment_status'] ?? '') == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="unpaid" {{ ($filters['payment_status'] ?? '') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100">
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Student Candidate</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Admission No</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Class</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Total Fee</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Amount Paid</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Fee Status</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">WAEC Remittance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($candidates as $candidate)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 font-bold text-sm text-dark">
                                    {{ $candidate->student->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">
                                    {{ $candidate->student->admission_no ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-700">
                                    {{ $candidate->schoolClass->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-extrabold text-dark">
                                    {{ $currencySymbol }}{{ number_format($candidate->total_fee, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-extrabold text-emerald-600">
                                    {{ $currencySymbol }}{{ number_format($candidate->amount_paid, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase bg-emerald-50 text-emerald-700">
                                        {{ $candidate->payment_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($candidate->remittance)
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold">
                                        Remitted ({{ $candidate->remittance->batch_reference }})
                                    </span>
                                    @else
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-bold">
                                        Awaiting WAEC Pay
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400">No candidates found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($candidates->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $candidates->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
