@extends('layouts.app')
@section('title', 'WAEC Candidates')
@section('body')
@php
    $currencySymbol = $school->currency_symbol ?? Auth::user()->school->currency_symbol ?? '₦';
@endphp
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'guardian'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">WAEC Candidates</h1>
                    <p class="text-gray-500">View your wards registered for WAEC examinations</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-danger/10 border border-danger/20 rounded-xl">
                <p class="text-danger font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Candidates List --}}
            @if($candidates->isEmpty())
            <div class="bg-white rounded-2xl p-12 border border-gray-100 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-xl font-bold text-dark mb-2">No WAEC Candidates</h3>
                <p class="text-gray-500">None of your wards are currently registered for WAEC examinations.</p>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($candidates as $candidate)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                    {{-- Header with Status Badge --}}
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-dark mb-1">
                                    {{ $candidate->student->user->first_name }} {{ $candidate->student->user->last_name }}
                                </h3>
                                <p class="text-sm text-gray-500">{{ $candidate->student->admission_number }}</p>
                            </div>
                            @php
                                $statusColors = [
                                    'registered' => 'bg-blue-100 text-blue-700',
                                    'approved' => 'bg-success/10 text-success',
                                    'cancelled' => 'bg-danger/10 text-danger',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$candidate->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($candidate->status) }}
                            </span>
                        </div>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                {{ $candidate->schoolClass->name }}{{ $candidate->arm ? ' ' . $candidate->arm->name : '' }}
                            </div>
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $candidate->session->name }}
                            </div>
                            @if($candidate->candidate_number)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <span class="font-semibold">{{ $candidate->candidate_number }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Payment Status --}}
                    <div class="p-6 bg-gray-50">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-semibold text-gray-600">Payment Status</span>
                            @php
                                $paymentStatusColors = [
                                    'unpaid' => 'bg-danger/10 text-danger',
                                    'partially_paid' => 'bg-warning/10 text-warning',
                                    'fully_paid' => 'bg-success/10 text-success',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $paymentStatusColors[$candidate->payment_status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ str_replace('_', ' ', ucfirst($candidate->payment_status)) }}
                            </span>
                        </div>

                        <div class="space-y-2 text-sm mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Fee:</span>
                                <span class="font-bold text-dark">{{ $currencySymbol }}{{ number_format($candidate->total_fee, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount Paid:</span>
                                <span class="font-semibold text-success">{{ $currencySymbol }}{{ number_format($candidate->amount_paid, 2) }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                <span class="text-gray-600">Balance:</span>
                                <span class="font-bold {{ $candidate->balance > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ $currencySymbol }}{{ number_format($candidate->balance, 2) }}
                                </span>
                            </div>
                        </div>

                        @if($candidate->balance > 0 && $candidate->status === 'registered')
                        <a href="{{ route('waec.payments.create', ['candidate_id' => $candidate->id]) }}" 
                           class="block w-full px-4 py-3 bg-primary hover:bg-primary-dark text-white text-center rounded-xl font-semibold transition-colors duration-200">
                            Make Payment
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
