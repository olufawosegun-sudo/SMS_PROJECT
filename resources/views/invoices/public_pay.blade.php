@extends('layouts.app')
@section('title', 'Invoice Payment — ' . $invoice->invoice_number . ' — ' . ($school->name ?? 'School Portal'))
@section('body')
@php
    $currencySymbol = $school->currency_symbol ?? '₦';
    $banking = $school->resolved_banking_details ?? [];
    $isPaid = $invoice->status === 'paid' || $invoice->balance <= 0.01;
@endphp
<div class="min-h-screen bg-slate-50 flex flex-col justify-between py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto w-full">

        {{-- School Header Card --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm mb-6 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-48 h-48 bg-primary/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="relative z-10">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-3 font-extrabold text-2xl shadow-inner">
                    {{ strtoupper(substr($school->name ?? 'S', 0, 2)) }}
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-dark tracking-tight">{{ $school->name ?? 'School Management' }}</h1>
                <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1">{{ $school->motto ?? 'Excellence in Education' }}</p>
                <div class="flex items-center justify-center gap-3 mt-3 text-xs text-gray-400">
                    <span>{{ $school->city ?? '' }}{{ $school->state ? ', ' . $school->state : '' }} {{ $school->country ? '(' . $school->country . ')' : '' }}</span>
                    @if($school->phone)
                    <span>•</span>
                    <span>{{ $school->phone }}</span>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800">
            <svg class="w-6 h-6 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="font-bold text-sm">{{ session('success') }}</p>
        </div>
        @endif

        {{-- Invoice Statement Main Container --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
            {{-- Statement Header Banner --}}
            <div class="px-6 sm:px-8 py-5 bg-gray-50/80 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Official Fee Statement</span>
                    <h2 class="text-xl font-extrabold text-dark mt-0.5 font-mono">{{ $invoice->invoice_number }}</h2>
                </div>
                <div class="flex items-center gap-3">
                    @if($isPaid)
                    <span class="px-4 py-1.5 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xs uppercase tracking-wider border border-emerald-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Paid in Full
                    </span>
                    @elseif($invoice->status === 'partially_paid')
                    <span class="px-4 py-1.5 rounded-full bg-amber-100 text-amber-800 font-extrabold text-xs uppercase tracking-wider border border-amber-200">
                        Partially Paid
                    </span>
                    @else
                    <span class="px-4 py-1.5 rounded-full bg-rose-100 text-rose-800 font-extrabold text-xs uppercase tracking-wider border border-rose-200">
                        Payment Due
                    </span>
                    @endif
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                {{-- Recipient & Meta Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 bg-gray-50/60 rounded-2xl border border-gray-100 text-sm">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Student Recipient</p>
                        <p class="text-base font-extrabold text-dark mt-0.5">{{ $student->user->first_name ?? 'N/A' }} {{ $student->user->last_name ?? '' }}</p>
                        <p class="text-xs text-gray-500 font-mono mt-0.5">Admission No: <strong>{{ $student->admission_no ?? 'N/A' }}</strong></p>
                        <p class="text-xs text-gray-500">Class: <strong>{{ $invoice->schoolClass->name ?? $student->schoolClass->name ?? 'General' }}</strong></p>
                    </div>
                    <div class="sm:text-right flex flex-col justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Date Issued</p>
                            <p class="text-sm font-semibold text-dark">{{ $invoice->created_at?->format('M d, Y') }}</p>
                        </div>
                        <div class="mt-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Payment Due Date</p>
                            <p class="text-sm font-bold {{ $invoice->due_date && $invoice->due_date->isPast() && !$isPaid ? 'text-danger' : 'text-dark' }}">
                                {{ $invoice->due_date?->format('M d, Y') ?? 'Immediate' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Itemized Fee Categories Table --}}
                <div>
                    <h3 class="text-sm font-bold text-dark uppercase tracking-wider mb-3">Itemized Fee Breakdown</h3>
                    <div class="border border-gray-100 rounded-2xl overflow-hidden">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50/80 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-5 py-3">#</th>
                                    <th class="px-5 py-3">Fee Particular / Category</th>
                                    <th class="px-5 py-3 text-right">Amount ({{ $currencySymbol }})</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($invoice->items as $idx => $item)
                                <tr class="hover:bg-gray-50/40 transition-colors">
                                    <td class="px-5 py-3.5 text-xs text-gray-400 font-bold">{{ $idx + 1 }}</td>
                                    <td class="px-5 py-3.5 font-semibold text-dark">
                                        {{ $item->feeCategory->name ?? 'School Fee' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-bold text-dark">
                                        {{ $currencySymbol }}{{ number_format($item->amount, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-4 text-center text-gray-400">Total invoice billing amount: {{ $currencySymbol }}{{ number_format($invoice->total_amount, 2) }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Financial Summary Box --}}
                <div class="bg-primary/5 rounded-2xl p-5 border border-primary/15 space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Total Invoiced Fee:</span>
                        <span class="font-bold text-dark">{{ $currencySymbol }}{{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    @if($invoice->paid_amount > 0)
                    <div class="flex justify-between text-sm text-emerald-600 font-semibold">
                        <span>Already Paid:</span>
                        <span>-{{ $currencySymbol }}{{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="border-t border-primary/20 pt-3 flex justify-between items-center">
                        <span class="text-base font-extrabold text-dark uppercase tracking-wide">Outstanding Balance:</span>
                        <span class="text-2xl sm:text-3xl font-extrabold {{ $isPaid ? 'text-emerald-600' : 'text-primary' }}">
                            {{ $currencySymbol }}{{ number_format($invoice->balance, 2) }}
                        </span>
                    </div>
                </div>

                @if($isPaid)
                {{-- Paid in full action --}}
                <div class="pt-4 text-center space-y-4">
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200">
                        <p class="text-emerald-800 font-bold text-base">This invoice has been settled in full. Thank you!</p>
                        <p class="text-xs text-emerald-600 mt-1">Receipt reference is available for official verification.</p>
                    </div>
                    <a href="{{ route('invoices.public.receipt', $invoice->uuid) }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-900 text-white rounded-xl hover:bg-black font-bold text-sm transition-all shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        View &amp; Print Official Receipt
                    </a>
                </div>
                @else
                {{-- Payment Options Form --}}
                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-base font-bold text-dark mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Complete Payment for this Invoice
                    </h3>

                    <form method="POST" action="{{ route('invoices.public.process', $invoice->uuid) }}" id="paymentForm" class="space-y-5">
                        @csrf

                        {{-- Amount to pay --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Amount to Pay ({{ $currencySymbol }}) *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400 font-extrabold">{{ $currencySymbol }}</span>
                                <input type="number"
                                       name="amount"
                                       id="paymentAmount"
                                       required
                                       step="0.01"
                                       min="1"
                                       max="{{ $invoice->balance }}"
                                       value="{{ old('amount', $invoice->balance) }}"
                                       class="w-full pl-10 pr-4 py-3.5 border-2 border-primary/30 rounded-2xl font-extrabold text-xl text-primary focus:ring-4 focus:ring-primary/20 focus:border-primary transition-all">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">You can pay full balance ({{ $currencySymbol }}{{ number_format($invoice->balance, 2) }}) or make a partial installment payment.</p>
                        </div>

                        {{-- Payer Information --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Payer / Guardian Name *</label>
                                <input type="text"
                                       name="payer_name"
                                       required
                                       placeholder="Enter your full name"
                                       value="{{ old('payer_name') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Phone Number (WhatsApp) *</label>
                                <input type="text"
                                       name="payer_phone"
                                       required
                                       placeholder="e.g. Mobile number"
                                       value="{{ old('payer_phone') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            </div>
                        </div>

                        {{-- Payment Method Selection --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2.5">Select Payment Method *</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="payment-method-card border-2 border-primary bg-primary/5 rounded-2xl p-4 cursor-pointer flex flex-col items-center text-center transition-all">
                                    <input type="radio" name="payment_method" value="card" checked class="sr-only">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-dark">Debit/Credit Card</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5">Instant Card Payment</span>
                                </label>

                                <label class="payment-method-card border-2 border-gray-200 hover:border-gray-300 rounded-2xl p-4 cursor-pointer flex flex-col items-center text-center transition-all">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="sr-only">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-dark">Bank Transfer</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5">{{ $banking['bank_name'] ?? 'Direct Bank' }}</span>
                                </label>

                                <label class="payment-method-card border-2 border-gray-200 hover:border-gray-300 rounded-2xl p-4 cursor-pointer flex flex-col items-center text-center transition-all">
                                    <input type="radio" name="payment_method" value="{{ !empty($banking['has_momo']) ? 'mobile_money' : 'ussd' }}" class="sr-only">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold text-dark">{{ !empty($banking['has_momo']) ? ($banking['momo_networks'][0] ?? 'Mobile Money') : 'USSD / Mobile Pay' }}</span>
                                    <span class="text-[10px] text-gray-400 mt-0.5">{{ $banking['country'] ?? 'National' }} Portal</span>
                                </label>
                            </div>
                        </div>

                        {{-- Country-Specific Official Bank Details Box (Shown for Bank Transfer) --}}
                        <div id="transferDetailsBox" class="p-5 bg-gradient-to-br from-gray-50 to-primary/5 rounded-2xl border border-primary/20 hidden space-y-3 text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-gray-200">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-dark text-sm">Official School Bank Account</span>
                                    <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary font-bold text-[10px]">{{ $banking['country'] }}</span>
                                </div>
                                <span class="text-emerald-700 font-bold flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Verified School Account
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-dark pt-1">
                                <div class="p-3 bg-white rounded-xl border border-gray-200/80">
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Bank Name</span>
                                    <strong class="text-sm text-primary font-extrabold">{{ $banking['bank_name'] }}</strong>
                                </div>

                                <div class="p-3 bg-white rounded-xl border border-gray-200/80">
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">{{ $banking['account_label'] }}</span>
                                    <div class="flex items-center justify-between mt-0.5">
                                        <strong class="text-sm font-mono font-extrabold text-dark" id="schoolAccountNum">{{ $banking['account_number'] ?? 'Contact Bursary' }}</strong>
                                        @if(!empty($banking['account_number']))
                                        <button type="button" onclick="copyText('{{ $banking['account_number'] }}', this)" class="text-[10px] text-primary hover:underline font-bold px-1.5 py-0.5 rounded bg-primary/10">Copy</button>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-span-1 sm:col-span-2 p-3 bg-white rounded-xl border border-gray-200/80">
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Account Name</span>
                                    <strong class="text-sm text-dark font-extrabold">{{ $banking['account_name'] }}</strong>
                                </div>

                                @if(!empty($banking['bank_branch']))
                                <div class="p-3 bg-white rounded-xl border border-gray-200/80">
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Branch</span>
                                    <strong class="text-xs text-dark">{{ $banking['bank_branch'] }}</strong>
                                </div>
                                @endif

                                @if(!empty($banking['bank_sort_code']))
                                <div class="p-3 bg-white rounded-xl border border-gray-200/80">
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">{{ $banking['sort_label'] }}</span>
                                    <strong class="text-xs font-mono text-dark">{{ $banking['bank_sort_code'] }}</strong>
                                </div>
                                @endif
                            </div>

                            @if(!empty($banking['instructions']))
                            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-xs">
                                <strong>Payment Note from School:</strong> {{ $banking['instructions'] }}
                            </div>
                            @endif

                            <div class="pt-2">
                                <label class="block text-[11px] font-semibold text-gray-600 mb-1">Transfer Reference / Teller No (Optional)</label>
                                <input type="text"
                                       name="transfer_reference"
                                       placeholder="e.g. TRF-93821048 or Transaction ID"
                                       class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs">
                            </div>
                        </div>

                        {{-- Mobile Money Details Box (Shown for Mobile Money) --}}
                        <div id="momoDetailsBox" class="p-5 bg-gradient-to-br from-amber-500/5 to-amber-500/10 rounded-2xl border border-amber-300 hidden space-y-3 text-xs">
                            <div class="flex items-center justify-between pb-2 border-b border-amber-200">
                                <span class="font-bold text-dark text-sm">Official Mobile Money Merchant ({{ $banking['country'] }})</span>
                                <span class="px-2 py-0.5 rounded-full bg-amber-200 text-amber-900 font-bold text-[10px]">Instant MoMo</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="p-3 bg-white rounded-xl border border-amber-200">
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Network / Provider</span>
                                    <strong class="text-sm text-dark font-extrabold">{{ $banking['momo_network'] ?? ($banking['momo_networks'][0] ?? 'Mobile Money') }}</strong>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-amber-200">
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Merchant / MoMo Number</span>
                                    <strong class="text-sm font-mono font-extrabold text-dark">{{ $banking['momo_number'] ?? $school->phone ?? 'Contact School Bursar' }}</strong>
                                </div>
                            </div>
                        </div>

                        {{-- Submit / Pay Now Button --}}
                        <button type="submit"
                                class="w-full py-4 bg-primary text-white rounded-2xl hover:bg-primary-dark transition-all font-extrabold text-base shadow-xl shadow-primary/25 hover:shadow-2xl hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Confirm &amp; Complete Payment
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center text-xs text-gray-400 space-y-1">
            <p>🔒 256-Bit SSL Encrypted &amp; Secure School Payment Portal</p>
            <p>© {{ date('Y') }} {{ $school->name }}. All Rights Reserved.</p>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.payment-method-card').forEach(c => {
            c.classList.remove('border-primary', 'bg-primary/5');
            c.classList.add('border-gray-200');
        });
        const parent = this.closest('.payment-method-card');
        parent.classList.add('border-primary', 'bg-primary/5');
        parent.classList.remove('border-gray-200');

        const transferBox = document.getElementById('transferDetailsBox');
        const momoBox = document.getElementById('momoDetailsBox');

        if (this.value === 'bank_transfer') {
            transferBox.classList.remove('hidden');
            if (momoBox) momoBox.classList.add('hidden');
        } else if (this.value === 'mobile_money' || this.value === 'ussd') {
            if (momoBox) momoBox.classList.remove('hidden');
            transferBox.classList.add('hidden');
        } else {
            transferBox.classList.add('hidden');
            if (momoBox) momoBox.classList.add('hidden');
        }
    });
});

function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const orig = btn.textContent;
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = orig, 2000);
    });
}
</script>
@endsection
