@extends('layouts.app')
@section('title', 'Pending WAEC Payments')
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
    @include('partials.sidebar', ['role' => 'principal'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Pending WAEC Payments</h1>
                    <p class="text-gray-500">Review and approve WAEC examination fee payments</p>
                </div>
                <a href="{{ route('principal.waec.payments') }}" 
                   class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-dark rounded-xl font-semibold transition-colors duration-200">
                    View All Payments
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

            {{-- Pending Count Alert --}}
            @if($pendingPayments->isNotEmpty())
            <div class="mb-6 p-4 bg-warning/10 border border-warning/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-warning flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="font-semibold text-warning">{{ $pendingPayments->count() }} Payment(s) Awaiting Your Approval</p>
                    <p class="text-sm text-gray-600">Please review and approve or reject these payments promptly.</p>
                </div>
            </div>
            @endif

            {{-- Pending Payments --}}
            @if($pendingPayments->isEmpty())
            <div class="bg-white rounded-2xl p-12 border border-gray-100 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-bold text-dark mb-2">All Clear!</h3>
                <p class="text-gray-500">No pending WAEC payments require your approval at this time.</p>
            </div>
            @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($pendingPayments as $payment)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row gap-6">
                            {{-- Payment Info --}}
                            <div class="flex-1 space-y-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-dark mb-1">
                                            {{ $payment->candidate->student->user->first_name }} {{ $payment->candidate->student->user->last_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $payment->candidate->student->admission_number }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-warning/10 text-warning">
                                        Pending Review
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Amount</p>
                                        <p class="text-lg font-bold text-success">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Payment Method</p>
                                        <p class="text-sm font-semibold text-dark">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Payment Date</p>
                                        <p class="text-sm font-semibold text-dark">{{ $payment->payment_date->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Submitted</p>
                                        <p class="text-sm font-semibold text-dark">{{ $payment->submitted_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                @if($payment->bank_name || $payment->transaction_reference)
                                <div class="flex gap-4 text-sm">
                                    @if($payment->bank_name)
                                    <div>
                                        <span class="text-gray-500">Bank:</span>
                                        <span class="font-semibold text-dark ml-1">{{ $payment->bank_name }}</span>
                                    </div>
                                    @endif
                                    @if($payment->transaction_reference)
                                    <div>
                                        <span class="text-gray-500">Ref:</span>
                                        <span class="font-semibold text-dark ml-1">{{ $payment->transaction_reference }}</span>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                @if($payment->payment_notes)
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-500 mb-1">Payment Notes:</p>
                                    <p class="text-sm text-gray-700">{{ Str::limit($payment->payment_notes, 150) }}</p>
                                </div>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex lg:flex-col gap-3 lg:w-48">
                                <a href="{{ route('principal.waec.payments.show', $payment->id) }}" 
                                   class="flex-1 lg:flex-none px-4 py-3 bg-gray-100 hover:bg-gray-200 text-dark text-center rounded-xl font-semibold transition-colors duration-200">
                                    View Details
                                </a>
                                
                                <button type="button"
                                        onclick="openApproveModal({{ $payment->id }})"
                                        class="flex-1 lg:flex-none px-4 py-3 bg-success hover:bg-success/90 text-white text-center rounded-xl font-semibold transition-colors duration-200">
                                    Approve
                                </button>
                                
                                <button type="button"
                                        onclick="openRejectModal({{ $payment->id }})"
                                        class="flex-1 lg:flex-none px-4 py-3 bg-danger hover:bg-danger/90 text-white text-center rounded-xl font-semibold transition-colors duration-200">
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>

                    @if($payment->proof_document)
                    <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                        <a href="{{ asset('storage/' . $payment->proof_document) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 text-sm text-primary hover:text-primary-dark font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Payment Proof Document
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </main>
</div>

{{-- Approve Modal --}}
<div id="approve-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-dark mb-4">Approve Payment</h3>
        <form id="approve-form" method="POST">
            @csrf
            <p class="text-gray-600 mb-4">Are you sure you want to approve this payment? A receipt will be generated and the guardian will be notified.</p>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Comment (Optional)</label>
                <textarea name="comment" 
                          rows="3" 
                          placeholder="Add any comments about this approval..."
                          class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-success hover:bg-success/90 text-white rounded-xl font-semibold transition-colors duration-200">
                    Approve Payment
                </button>
                <button type="button" 
                        onclick="closeModal('approve-modal')"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-dark rounded-xl font-semibold transition-colors duration-200">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Modal --}}
<div id="reject-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-dark mb-4">Reject Payment</h3>
        <form id="reject-form" method="POST">
            @csrf
            <p class="text-gray-600 mb-4">Please provide a clear reason for rejecting this payment. The guardian will be notified with your reason.</p>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Rejection Reason <span class="text-danger">*</span></label>
                <textarea name="rejection_reason" 
                          rows="4" 
                          required
                          placeholder="e.g., Invalid transaction reference, unclear payment proof, amount mismatch..."
                          class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Additional Comment (Optional)</label>
                <textarea name="comment" 
                          rows="2" 
                          placeholder="Any additional notes..."
                          class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-danger hover:bg-danger/90 text-white rounded-xl font-semibold transition-colors duration-200">
                    Reject Payment
                </button>
                <button type="button" 
                        onclick="closeModal('reject-modal')"
                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-dark rounded-xl font-semibold transition-colors duration-200">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openApproveModal(paymentId) {
    const form = document.getElementById('approve-form');
    form.action = `/principal/waec/payments/${paymentId}/approve`;
    document.getElementById('approve-modal').classList.remove('hidden');
}

function openRejectModal(paymentId) {
    const form = document.getElementById('reject-form');
    form.action = `/principal/waec/payments/${paymentId}/reject`;
    document.getElementById('reject-modal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal on outside click
document.getElementById('approve-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('approve-modal');
});

document.getElementById('reject-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('reject-modal');
});
</script>
@endpush
@endsection
