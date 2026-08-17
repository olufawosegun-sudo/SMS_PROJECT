@extends('layouts.app')

@section('title', 'Process WAEC Remittance — ' . ($school->name ?? 'EduWest Africa'))

@section('body')
@php
    $currencySymbol = Auth::user()->school->currency_symbol ?? $school->currency_symbol ?? '₦';
    $isOwner = Auth::user()->role->name === 'Owner';
    $storeRoute = $isOwner ? route('owner.waec.remittance.store') : route('principal.waec.remittance.store');
    $indexRoute = $isOwner ? route('owner.waec.remittance.index') : route('principal.waec.remittance.index');
@endphp

<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name)])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8 max-w-6xl mx-auto">
            {{-- Back Link & Header --}}
            <div class="mb-6">
                <a href="{{ $indexRoute }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-primary transition-colors mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Back to WAEC Remittances</span>
                </a>
                <h1 class="text-2xl md:text-3xl font-extrabold text-dark text-left">Process WAEC Remittance</h1>
                <p class="text-sm text-gray-500 text-left mt-1">Select candidates whose fees have been collected from students and record payment remittance to WAEC</p>
            </div>

            @if(session('error'))
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center gap-3 text-rose-700">
                <svg class="w-6 h-6 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-700">
                <p class="text-sm font-bold mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ $storeRoute }}" method="POST" enctype="multipart/form-data" id="waecRemittanceForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Left 2 Columns: Candidate Selector --}}
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-left">
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                                <div>
                                    <h3 class="text-lg font-bold text-dark">Select Candidates for Remittance</h3>
                                    <p class="text-xs text-gray-400">Students with approved WAEC fee payments ready for WAEC submission</p>
                                </div>
                                <button type="button" onclick="toggleSelectAll()" class="px-3 py-1.5 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all">
                                    Toggle Select All
                                </button>
                            </div>

                            {{-- Academic Session Filter --}}
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Academic Session</label>
                                <select name="session_id" onchange="window.location.href='{{ url()->current() }}?session_id=' + this.value" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/20 bg-white">
                                    @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ ($currentSession->id ?? null) == $session->id ? 'selected' : '' }}>
                                        {{ $session->name }} {{ $session->is_current ? '(Current Session)' : '' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Candidate Checkbox List --}}
                            @if($eligibleCandidates->isNotEmpty())
                            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                                @foreach($eligibleCandidates as $candidate)
                                <label class="flex items-start gap-3 p-4 rounded-xl border border-gray-100 hover:border-primary/30 hover:bg-gray-50/50 transition-all cursor-pointer block candidate-row">
                                    <input type="checkbox" name="candidate_ids[]" value="{{ $candidate->id }}" data-amount="{{ $candidate->amount_paid }}" onchange="recalculateTotal()" class="mt-1 w-4 h-4 rounded text-primary focus:ring-primary candidate-checkbox" checked>
                                    <div class="flex-1 text-left">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-bold text-dark">{{ $candidate->student->user->name ?? 'N/A' }}</p>
                                            <span class="text-sm font-black text-primary">{{ $currencySymbol }}{{ number_format($candidate->amount_paid, 2) }}</span>
                                        </div>
                                        <div class="flex items-center gap-3 text-xs text-gray-400 mt-1">
                                            <span>Admission: {{ $candidate->student->admission_no ?? 'N/A' }}</span>
                                            <span>•</span>
                                            <span>Class: {{ $candidate->schoolClass->name ?? 'N/A' }}</span>
                                            <span>•</span>
                                            <span class="font-semibold text-emerald-600 uppercase text-[10px] bg-emerald-50 px-2 py-0.5 rounded">{{ $candidate->payment_status }}</span>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            @else
                            <div class="py-12 text-center text-gray-400 bg-gray-50/50 rounded-xl border border-dashed border-gray-200">
                                <svg class="w-10 h-10 mx-auto mb-2 opacity-40 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <p class="text-sm font-bold text-gray-600">No Eligible Candidates Found</p>
                                <p class="text-xs text-gray-400 mt-1">All candidates in this session have already been remitted or have not paid their WAEC fees yet.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Right Column: Remittance Payment Form --}}
                    <div class="space-y-6 text-left">
                        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-4">
                            <h3 class="text-lg font-bold text-dark pb-3 border-b border-gray-100">WAEC Remittance Details</h3>

                            {{-- Selected Summary Box --}}
                            <div class="p-4 bg-gradient-to-br from-primary/10 to-primary/5 rounded-xl border border-primary/20">
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Remittance Amount</p>
                                <p class="text-3xl font-black text-primary mt-1" id="displayTotalAmount">{{ $currencySymbol }}0.00</p>
                                <p class="text-xs text-gray-500 mt-1" id="displaySelectedCount">0 Candidates Selected</p>
                                <input type="hidden" name="total_amount" id="inputTotalAmount" value="0">
                            </div>

                            {{-- Payment Method --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Remittance Payment Channel *</label>
                                <select name="payment_method" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/20 bg-white">
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Direct Bank Transfer</option>
                                    <option value="waec_portal" {{ old('payment_method') == 'waec_portal' ? 'selected' : '' }}>Official WAEC Portal Card/Online</option>
                                    <option value="draft" {{ old('payment_method') == 'draft' ? 'selected' : '' }}>Certified Bank Draft</option>
                                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Corporate Debit/Credit Card</option>
                                </select>
                            </div>

                            {{-- Bank Name --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Receiving Bank / Payment Gateway</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="e.g. First Bank, WAEC Remita Portal" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/20">
                            </div>

                            {{-- WAEC Transaction Reference --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">WAEC Transaction / Teller Reference *</label>
                                <input type="text" name="waec_transaction_reference" required value="{{ old('waec_transaction_reference') }}" placeholder="e.g. WAEC-TL-9823410293" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/20">
                            </div>

                            {{-- Payment Date --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Remittance Date *</label>
                                <input type="date" name="payment_date" required value="{{ old('payment_date', date('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/20">
                            </div>

                            {{-- Proof Document --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Proof of Remittance (Bank Teller / Receipt)</label>
                                <input type="file" name="proof_document" accept=".pdf,.png,.jpg,.jpeg" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Remittance Remarks / Audit Notes</label>
                                <textarea name="notes" rows="2" placeholder="Optional comments, batch registration codes, etc." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('notes') }}</textarea>
                            </div>

                            {{-- Submit Button --}}
                            <button type="submit" @if($eligibleCandidates->isEmpty()) disabled @endif class="w-full py-3.5 gradient-primary hover:opacity-95 text-white font-bold rounded-xl shadow-lg shadow-primary/25 transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                                Submit WAEC Remittance
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    const currencySymbol = "{{ $currencySymbol }}";

    function recalculateTotal() {
        const checkboxes = document.querySelectorAll('.candidate-checkbox:checked');
        let total = 0;
        checkboxes.forEach(cb => {
            total += parseFloat(cb.getAttribute('data-amount') || 0);
        });

        document.getElementById('displayTotalAmount').innerText = currencySymbol + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('inputTotalAmount').value = total;
        document.getElementById('displaySelectedCount').innerText = checkboxes.length + ' Candidate(s) Selected';
    }

    function toggleSelectAll() {
        const checkboxes = document.querySelectorAll('.candidate-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
        recalculateTotal();
    }

    document.addEventListener('DOMContentLoaded', function() {
        recalculateTotal();
    });
</script>
@endsection
