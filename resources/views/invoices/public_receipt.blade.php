@extends('layouts.app')
@section('title', 'Payment Receipt — ' . $invoice->invoice_number . ' — ' . ($school->name ?? 'School Portal'))
@section('body')
@php
    $currencySymbol = $school->currency_symbol ?? '₦';
    $paidAmount = $latestPayment ? $latestPayment->amount : $invoice->paid_amount;
@endphp
<div class="min-h-screen bg-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">

        {{-- Top action bar (hidden on print) --}}
        <div class="flex items-center justify-between mb-6 print:hidden">
            <a href="{{ route('invoices.public.pay', $invoice->uuid) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 rounded-xl hover:bg-gray-50 border border-gray-200 text-xs font-bold transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Statement
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-primary-dark font-bold text-xs transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / Save PDF Receipt
            </button>
        </div>

        {{-- Printable Official Receipt Document --}}
        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-gray-200 shadow-xl relative overflow-hidden print:border-none print:shadow-none print:p-0">

            {{-- Verified Paid Watermark / Stamp --}}
            <div class="absolute right-8 top-28 border-4 border-emerald-600/30 text-emerald-600/30 font-black text-3xl px-6 py-2 rounded-2xl rotate-[-12deg] pointer-events-none uppercase tracking-widest print:opacity-40">
                Official Receipt
            </div>

            {{-- Header --}}
            <div class="border-b-2 border-gray-100 pb-6 mb-6 text-center">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-2 font-extrabold text-xl">
                    {{ strtoupper(substr($school->name ?? 'S', 0, 2)) }}
                </div>
                <h1 class="text-2xl font-extrabold text-dark uppercase tracking-wide">{{ $school->name }}</h1>
                <p class="text-xs text-gray-500 font-medium italic mt-0.5">"{{ $school->motto ?? 'Excellence, Knowledge & Integrity' }}"</p>
                <p class="text-[11px] text-gray-400 mt-1">{{ $school->address ?? '' }} {{ $school->city ? '• ' . $school->city : '' }} {{ $school->country ? '(' . $school->country . ')' : '' }}</p>
            </div>

            {{-- Receipt Meta & Recipient Grid --}}
            <div class="grid grid-cols-2 gap-4 mb-6 text-xs">
                <div class="space-y-1">
                    <p class="text-gray-400 font-bold uppercase text-[10px]">Student Details</p>
                    <p class="font-extrabold text-sm text-dark">{{ $student->user->first_name ?? 'N/A' }} {{ $student->user->last_name ?? '' }}</p>
                    <p class="text-gray-600">Admission No: <strong>{{ $student->admission_no ?? 'N/A' }}</strong></p>
                    <p class="text-gray-600">Class: <strong>{{ $invoice->schoolClass->name ?? $student->schoolClass->name ?? 'General' }}</strong></p>
                </div>
                <div class="space-y-1 text-right">
                    <p class="text-gray-400 font-bold uppercase text-[10px]">Receipt Information</p>
                    <p class="font-mono font-bold text-xs text-primary">REC-{{ $latestPayment ? $latestPayment->id : $invoice->id }}-{{ date('Y') }}</p>
                    <p class="text-gray-600">Invoice: <strong>{{ $invoice->invoice_number }}</strong></p>
                    <p class="text-gray-600">Date Paid: <strong>{{ $latestPayment ? $latestPayment->paid_at?->format('M d, Y H:i') : now()->format('M d, Y') }}</strong></p>
                    @if($latestPayment && $latestPayment->payment_reference)
                    <p class="text-gray-500 font-mono text-[10px]">Ref: {{ $latestPayment->payment_reference }}</p>
                    @endif
                </div>
            </div>

            {{-- Line Items Table --}}
            <div class="border border-gray-200 rounded-2xl overflow-hidden mb-6">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b border-gray-200 text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-2.5">#</th>
                            <th class="px-4 py-2.5">Fee Category / Description</th>
                            <th class="px-4 py-2.5 text-right">Amount ({{ $currencySymbol }})</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($invoice->items as $i => $item)
                        <tr>
                            <td class="px-4 py-2.5 font-bold text-gray-400">{{ $i + 1 }}</td>
                            <td class="px-4 py-2.5 font-semibold text-dark">{{ $item->feeCategory->name ?? 'School Fee' }}</td>
                            <td class="px-4 py-2.5 text-right font-bold text-dark">{{ $currencySymbol }}{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-2.5 text-center text-gray-400">Total Billed: {{ $currencySymbol }}{{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Summary Totals Box --}}
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200 space-y-2 text-xs mb-6">
                <div class="flex justify-between text-gray-600">
                    <span>Total Invoiced:</span>
                    <span class="font-bold text-dark">{{ $currencySymbol }}{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-emerald-700 font-extrabold text-sm border-t border-gray-200 pt-2">
                    <span>Amount Paid (This Transaction):</span>
                    <span>+{{ $currencySymbol }}{{ number_format($paidAmount, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Cumulative Total Paid:</span>
                    <span class="font-bold text-dark">{{ $currencySymbol }}{{ number_format($invoice->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-700 font-extrabold text-sm border-t border-gray-200 pt-2">
                    <span>Remaining Balance:</span>
                    <span class="{{ $invoice->balance <= 0.01 ? 'text-emerald-600 font-bold' : 'text-danger font-bold' }}">
                        {{ $currencySymbol }}{{ number_format($invoice->balance, 2) }}
                    </span>
                </div>
            </div>

            {{-- Footer Notes & Signature Placeholder --}}
            <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400">
                <div class="text-center sm:text-left">
                    <p class="font-semibold text-gray-600">Official Computer-Generated Receipt</p>
                    <p class="text-[10px]">No physical signature required. Verified online.</p>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-emerald-700 font-extrabold uppercase text-[10px] tracking-wider">Status: PAYMENT VERIFIED</p>
                    <p class="text-[10px] font-mono">{{ date('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>

        {{-- Help note --}}
        <p class="text-center text-xs text-gray-400 mt-6 print:hidden">
            Have questions regarding this receipt? Contact the school bursary at <strong>{{ $school->email ?? 'bursar@school.edu' }}</strong>
        </p>
    </div>
</div>
@endsection
