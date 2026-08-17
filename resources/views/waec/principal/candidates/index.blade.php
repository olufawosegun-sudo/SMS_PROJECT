@extends('layouts.app')
@section('title', 'WAEC Candidates Management')
@section('body')
@php
    $currencySymbol = $school->currency_symbol ?? Auth::user()->school->currency_symbol ?? '₦';
@endphp
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'principal'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">WAEC Candidates</h1>
                    <p class="text-gray-500">Manage WAEC examination candidates</p>
                </div>
                <a href="{{ route('principal.waec.candidates.create') }}" 
                   class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold transition-colors duration-200 inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Register Candidate
                </a>
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

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-primary mb-1">{{ $statistics['total_candidates'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Candidates</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-success mb-1">{{ $statistics['fully_paid'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Fully Paid</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-warning mb-1">{{ $statistics['partially_paid'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Partially Paid</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-danger mb-1">{{ $statistics['unpaid'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Unpaid</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-6">
                <form method="GET" action="{{ route('principal.waec.candidates') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Session</label>
                        <select name="session_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <option value="">All Sessions</option>
                            @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ ($filters['session_id'] ?? '') == $session->id ? 'selected' : '' }}>
                                {{ $session->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Class</label>
                        <select name="class_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ ($filters['class_id'] ?? '') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Payment Status</label>
                        <select name="payment_status" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <option value="">All Statuses</option>
                            <option value="unpaid" {{ ($filters['payment_status'] ?? '') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="partially_paid" {{ ($filters['payment_status'] ?? '') === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                            <option value="fully_paid" {{ ($filters['payment_status'] ?? '') === 'fully_paid' ? 'selected' : '' }}>Fully Paid</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Search</label>
                        <input type="text" 
                               name="search" 
                               value="{{ $filters['search'] ?? '' }}" 
                               placeholder="Name or admission no..."
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                    </div>
                    <div class="md:col-span-4 flex gap-2">
                        <button type="submit" 
                                class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold transition-colors duration-200">
                            Apply Filters
                        </button>
                        <a href="{{ route('principal.waec.candidates') }}" 
                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-dark rounded-xl font-semibold transition-colors duration-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Candidates Table --}}
            @if($candidates->isEmpty())
            <div class="bg-white rounded-2xl p-12 border border-gray-100 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="text-xl font-bold text-dark mb-2">No Candidates Found</h3>
                <p class="text-gray-500 mb-6">Start by registering WAEC candidates for examinations.</p>
                <a href="{{ route('principal.waec.candidates.create') }}" 
                   class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold transition-colors duration-200">
                    Register First Candidate
                </a>
            </div>
            @else
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Session</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Candidate No.</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Payment Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Balance</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($candidates as $candidate)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-dark">{{ $candidate->student->user->first_name }} {{ $candidate->student->user->last_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $candidate->student->admission_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $candidate->schoolClass->name }}{{ $candidate->arm ? ' ' . $candidate->arm->name : '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $candidate->session->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-dark">
                                    {{ $candidate->candidate_number ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold {{ $candidate->balance > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $currencySymbol }}{{ number_format($candidate->balance, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'registered' => 'bg-blue-100 text-blue-700',
                                            'approved' => 'bg-success/10 text-success',
                                            'cancelled' => 'bg-danger/10 text-danger',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $statusColors[$candidate->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($candidate->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('principal.waec.candidates.show', $candidate->id) }}" 
                                       class="text-primary hover:text-primary-dark font-semibold">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($candidates->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $candidates->links() }}
                </div>
                @endif
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
