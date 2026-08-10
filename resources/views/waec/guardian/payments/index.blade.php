@extends('layouts.app')
@section('title', 'WAEC Payments')
@section('body')
@php
    $currencySymbol = match(strtolower($school->country ?? '')) {
        'nigeria' => '₦',
        'ghana' => 'GH₵',
        'kenya' => 'KSh',
        'south africa' => 'R',
        'united kingdom', 'uk' => '£',
        'united states', 'us', 'usa' => '$',
        default => '$',
    };
@endphp
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'guardian'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">WAEC Payments</h1>
                    <p class="text-gray-500">Track WAEC examination fee payments for your wards</p>
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

            {{-- Payments Table --}}
            @if($payments->isEmpty())
            <div class="bg-white rounded-2xl p-12 border border-gray-100 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-xl font-bold text-dark mb-2">No Payments Yet</h3>
                <p class="text-gray-500 mb-6">You haven't submitted any WAEC payments yet.</p>
                <a href="{{ route('waec.candidates') }}" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold transition-colors duration-200">
                    View Candidates
                </a>
            </div>
            @else
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Session</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Payment Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-dark">{{ $payment->payment_reference }}</div>
                                    @if($payment->receipt_number)
                                    <div class="text-xs text-gray-500">Receipt: {{ $payment->receipt_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-dark">{{ $payment->candidate->student->user->first_name }} {{ $payment->candidate->student->user->last_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->candidate->student->admission_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $payment->candidate->session->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-dark">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $payment->payment_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['class' => 'bg-gray-100 text-gray-700', 'label' => 'Pending'],
                                            'submitted' => ['class' => 'bg-blue-100 text-blue-700', 'label' => 'Submitted'],
                                            'under_review' => ['class' => 'bg-warning/10 text-warning', 'label' => 'Under Review'],
                                            'approved' => ['class' => 'bg-success/10 text-success', 'label' => 'Approved'],
                                            'rejected' => ['class' => 'bg-danger/10 text-danger', 'label' => 'Rejected'],
                                        ];
                                        $config = $statusConfig[$payment->status] ?? ['class' => 'bg-gray-100 text-gray-700', 'label' => ucfirst($payment->status)];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $config['class'] }}">
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('waec.payments.show', $payment->id) }}" 
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
                @if($payments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $payments->links() }}
                </div>
                @endif
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
