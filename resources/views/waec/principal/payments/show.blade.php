@extends('layouts.app')
@section('title', 'WAEC Payment Review')
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
            <div class="mb-8">
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                    <a href="{{ route('principal.waec.payments') }}" class="hover:text-primary">WAEC Payments</a>
                    <span>/</span>
                    <span class="text-dark font-semibold">Payment Review</span>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-dark mb-2">Payment Review</h1>
                        <p class="text-gray-500">Reference: {{ $payment->payment_reference }}</p>
                    </div>
                    
                    {{-- Action Buttons --}}
                    @if(in_array($payment->status, ['submitted', 'under_review']))
                    <div class="flex gap-3">
                        <button type="button"
                                onclick="openApproveModal()"
                                class="px-6 py-3 bg-success hover:bg-success/90 text-white rounded-xl font-semibold transition-colors duration-200 inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Approve
                        </button>
                        
                        <button type="button"
                                onclick="openRejectModal()"
                                class="px-6 py-3 bg-danger hover:bg-danger/90 text-white rounded-xl font-semibold transition-colors duration-200 inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reject
                        </button>
                    </div>
                    @elseif($payment->status === 'approved')
                    <a href="{{ route('waec.payments.receipt', $payment->id) }}" 
                       target="_blank"
                       class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold transition-colors duration-200 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        Download Receipt
                    </a>
                    @endif
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Status & Action Panel --}}
                <div class="space-y-6">
                    {{-- Payment Status --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-dark mb-4">Payment Status</h3>
                        
                        @php
                            $statusConfig = [
                                'pending' => ['class' => 'bg-gray-100 text-gray-700', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'submitted' => ['class' => 'bg-blue-100 text-blue-700', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'under_review' => ['class' => 'bg-warning/10 text-warning', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                                'approved' => ['class' => 'bg-success/10 text-success', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'rejected' => ['class' => 'bg-danger/10 text-danger', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ];
                            $config = $statusConfig[$payment->status] ?? ['class' => 'bg-gray-100 text-gray-700', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'];
                        @endphp

                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full {{ $config['class'] }} flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}" />
                                </svg>
                            </div>
                            <div>
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-bold {{ $config['class'] }}">
                                    {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                                </span>
                            </div>
                        </div>

                        @if($payment->status === 'rejected' && $payment->rejection_reason)
                        <div class="mt-4 p-4 bg-danger/5 border border-danger/20 rounded-xl">
                            <p class="text-sm font-semibold text-danger mb-2">Rejection Reason:</p>
                            <p class="text-sm text-gray-700">{{ $payment->rejection_reason }}</p>
                            @if($payment->rejecter)
                            <p class="text-xs text-gray-500 mt-2">Rejected by: {{ $payment->rejecter->first_name }} {{ $payment->rejecter->last_name }}</p>
                            @endif
                        </div>
                        @endif

                        @if($payment->status === 'approved' && $payment->receipt_number)
                        <div class="mt-4 p-4 bg-success/5 border border-success/20 rounded-xl">
                            <p class="text-sm text-gray-600 mb-1">Receipt Number</p>
                            <p class="text-lg font-bold text-success">{{ $payment->receipt_number }}</p>
                            @if($payment->approver)
                            <p class="text-xs text-gray-500 mt-2">Approved by: {{ $payment->approver->first_name }} {{ $payment->approver->last_name }}</p>
                            @endif
                        </div>
                        @endif

                        <div class="mt-6 space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Submitted:</span>
                                <span class="font-semibold text-dark">{{ $payment->submitted_at ? $payment->submitted_at->format('M d, Y H:i') : '-' }}</span>
                            </div>
                            @if($payment->approved_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Approved:</span>
                                <span class="font-semibold text-dark">{{ $payment->approved_at->format('M d, Y H:i') }}</span>
                            </div>
                            @endif
                            @if($payment->rejected_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rejected:</span>
                                <span class="font-semibold text-dark">{{ $payment->rejected_at->format('M d, Y H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Candidate Summary --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-dark mb-4">Candidate Summary</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Fee:</span>
                                <span class="font-bold text-dark">{{ $currencySymbol }}{{ number_format($payment->candidate->total_fee, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount Paid:</span>
                                <span class="font-semibold text-success">{{ $currencySymbol }}{{ number_format($payment->candidate->amount_paid, 2) }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                <span class="text-gray-600">Balance:</span>
                                <span class="font-bold {{ $payment->candidate->balance > 0 ? 'text-danger' : 'text-success' }}">
                                    {{ $currencySymbol }}{{ number_format($payment->candidate->balance, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Candidate Information --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-dark mb-4">Candidate Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Student Name</p>
                                <p class="font-semibold text-dark">{{ $payment->candidate->student->user->first_name }} {{ $payment->candidate->student->user->last_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Admission Number</p>
                                <p class="font-semibold text-dark">{{ $payment->candidate->student->admission_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Class</p>
                                <p class="font-semibold text-dark">{{ $payment->candidate->schoolClass->name }}{{ $payment->candidate->arm ? ' ' . $payment->candidate->arm->name : '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Session</p>
                                <p class="font-semibold text-dark">{{ $payment->candidate->session->name }}</p>
                            </div>
                            @if($payment->candidate->candidate_number)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Candidate Number</p>
                                <p class="font-semibold text-dark">{{ $payment->candidate->candidate_number }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Guardian</p>
                                <p class="font-semibold text-dark">{{ $payment->guardian->user->first_name ?? 'N/A' }} {{ $payment->guardian->user->last_name ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Details --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-dark mb-4">Payment Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Amount Paid</p>
                                <p class="text-2xl font-bold text-success">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                                <p class="font-semibold text-dark">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Payment Date</p>
                                <p class="font-semibold text-dark">{{ $payment->payment_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Submitted By</p>
                                <p class="font-semibold text-dark">{{ $payment->submitter->first_name ?? 'N/A' }} {{ $payment->submitter->last_name ?? '' }}</p>
                            </div>
                            @if($payment->bank_name)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Bank Name</p>
                                <p class="font-semibold text-dark">{{ $payment->bank_name }}</p>
                            </div>
                            @endif
                            @if($payment->account_name)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Account Name</p>
                                <p class="font-semibold text-dark">{{ $payment->account_name }}</p>
                            </div>
                            @endif
                            @if($payment->transaction_reference)
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Transaction Reference</p>
                                <p class="font-semibold text-dark">{{ $payment->transaction_reference }}</p>
                            </div>
                            @endif
                        </div>

                        @if($payment->payment_notes)
                        <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm font-semibold text-gray-600 mb-2">Payment Notes:</p>
                            <p class="text-sm text-gray-700">{{ $payment->payment_notes }}</p>
                        </div>
                        @endif

                        @if($payment->proof_document)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm font-semibold text-gray-600 mb-2">Payment Proof</p>
                            <a href="{{ asset('storage/' . $payment->proof_document) }}" 
                               target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Payment Proof Document
                            </a>
                        </div>
                        @endif
                    </div>

                    {{-- Approval History --}}
                    @if($payment->approvals->isNotEmpty())
                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-dark mb-4">Approval History</h3>
                        <div class="space-y-4">
                            @foreach($payment->approvals as $approval)
                            <div class="flex items-start gap-3 pb-4 border-b border-gray-100 last:border-0">
                                <div class="w-10 h-10 rounded-full {{ $approval->action === 'approved' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }} flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($approval->action === 'approved')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        @endif
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-semibold text-dark">{{ ucfirst($approval->action) }} by {{ $approval->user->first_name }} {{ $approval->user->last_name }}</p>
                                        <span class="text-xs text-gray-500">{{ $approval->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    @if($approval->comment)
                                    <p class="text-sm text-gray-600">{{ $approval->comment }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Approve Modal --}}
<div id="approve-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-dark mb-4">Approve Payment</h3>
        <form method="POST" action="{{ route('principal.waec.payments.approve', $payment->id) }}">
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
        <form method="POST" action="{{ route('principal.waec.payments.reject', $payment->id) }}">
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
function openApproveModal() {
    document.getElementById('approve-modal').classList.remove('hidden');
}

function openRejectModal() {
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
