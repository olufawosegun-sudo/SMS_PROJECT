@extends('layouts.app')
@section('title', 'Submit WAEC Payment')
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
            <div class="mb-8">
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                    <a href="{{ route('waec.candidates') }}" class="hover:text-primary">WAEC Candidates</a>
                    <span>/</span>
                    <span class="text-dark font-semibold">Submit Payment</span>
                </div>
                <h1 class="text-3xl font-bold text-dark mb-2">Submit WAEC Payment</h1>
                <p class="text-gray-500">Submit payment for WAEC examination fees</p>
            </div>

            @if(session('error'))
            <div class="mb-6 p-4 bg-danger/10 border border-danger/20 rounded-xl">
                <p class="text-danger font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Candidate Info Card --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Candidate Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Student Name</p>
                            <p class="font-semibold text-dark">{{ $candidate->student->user->first_name }} {{ $candidate->student->user->last_name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Admission Number</p>
                            <p class="font-semibold text-dark">{{ $candidate->student->admission_number }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Class</p>
                            <p class="font-semibold text-dark">{{ $candidate->schoolClass->name }}{{ $candidate->arm ? ' ' . $candidate->arm->name : '' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Session</p>
                            <p class="font-semibold text-dark">{{ $candidate->session->name }}</p>
                        </div>

                        @if($candidate->candidate_number)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Candidate Number</p>
                            <p class="font-semibold text-dark">{{ $candidate->candidate_number }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Fee:</span>
                            <span class="font-bold text-dark">{{ $currencySymbol }}{{ number_format($candidate->total_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Amount Paid:</span>
                            <span class="font-semibold text-success">{{ $currencySymbol }}{{ number_format($candidate->amount_paid, 2) }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-200">
                            <span class="text-sm text-gray-600">Balance Due:</span>
                            <span class="font-bold text-danger">{{ $currencySymbol }}{{ number_format($candidate->balance, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Payment Form --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-dark mb-6">Payment Details</h3>

                    <form method="POST" action="{{ route('waec.payments.submit') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Amount Paying <span class="text-danger">*</span></label>
                                <input type="number" 
                                       name="amount" 
                                       step="0.01" 
                                       min="0" 
                                       max="{{ $candidate->balance }}"
                                       value="{{ old('amount') }}" 
                                       required 
                                       placeholder="0.00"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('amount') border-danger @enderror">
                                @error('amount')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" 
                                        required 
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('payment_method') border-danger @enderror">
                                    <option value="">Select Method</option>
                                    <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                                    <option value="pos" {{ old('payment_method') === 'pos' ? 'selected' : '' }}>POS</option>
                                    <option value="online" {{ old('payment_method') === 'online' ? 'selected' : '' }}>Online Payment</option>
                                </select>
                                @error('payment_method')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" 
                                       name="payment_date" 
                                       value="{{ old('payment_date', date('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}"
                                       required 
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('payment_date') border-danger @enderror">
                                @error('payment_date')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Bank Name</label>
                                <input type="text" 
                                       name="bank_name" 
                                       value="{{ old('bank_name') }}" 
                                       placeholder="e.g., First Bank"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('bank_name') border-danger @enderror">
                                @error('bank_name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Account Name</label>
                                <input type="text" 
                                       name="account_name" 
                                       value="{{ old('account_name') }}" 
                                       placeholder="Account holder name"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('account_name') border-danger @enderror">
                                @error('account_name')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Transaction Reference</label>
                                <input type="text" 
                                       name="transaction_reference" 
                                       value="{{ old('transaction_reference') }}" 
                                       placeholder="Transaction/Reference number"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('transaction_reference') border-danger @enderror">
                                @error('transaction_reference')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Payment Proof (Optional)</label>
                            <input type="file" 
                                   name="proof_document" 
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('proof_document') border-danger @enderror">
                            <p class="text-xs text-gray-500 mt-1">Upload bank receipt, transaction screenshot, or teller. Max 2MB (PDF, JPG, PNG)</p>
                            @error('proof_document')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Payment Notes</label>
                            <textarea name="payment_notes" 
                                      rows="3" 
                                      placeholder="Add any additional information about this payment..."
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('payment_notes') border-danger @enderror">{{ old('payment_notes') }}</textarea>
                            @error('payment_notes')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold transition-colors duration-200">
                                Submit Payment for Approval
                            </button>
                            <a href="{{ route('waec.candidates') }}" 
                               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-dark rounded-xl font-semibold transition-colors duration-200">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
