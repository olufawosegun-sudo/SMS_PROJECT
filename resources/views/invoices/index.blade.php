@extends('layouts.app')
@section('title', 'Invoices')
@section('body')
@php
    $currencySymbol = $school->currency_symbol ?? Auth::user()->school->currency_symbol ?? '₦';
@endphp
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Student Invoices</h1>
                    <p class="text-gray-500">Generate fee statements, copy student payment links, and track collections</p>
                </div>
                <a href="{{ route('invoices.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-all font-semibold text-sm shadow-lg shadow-primary/20 hover:shadow-xl hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Create &amp; Issue Invoice
                </a>
            </div>

            {{-- New Invoice Shareable Payment URL Hero Banner --}}
            @if(session('new_invoice_url'))
            <div class="mb-8 p-6 bg-gradient-to-r from-primary to-primary-dark rounded-3xl text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
                <div class="relative z-10 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Invoice #{{ session('new_invoice_number') }} Generated!</h3>
                                <p class="text-xs text-white/70">Student: <strong>{{ session('new_invoice_student') }}</strong> • Total: <strong>{{ $currencySymbol }}{{ session('new_invoice_amount') }}</strong></p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-accent text-dark rounded-full text-xs font-bold uppercase tracking-wider self-start sm:self-auto">Ready to Send</span>
                    </div>

                    {{-- URL Box with Copy Action --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 pt-2">
                        <div class="flex-1 bg-white/10 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20 flex items-center gap-2 overflow-hidden">
                            <svg class="w-4 h-4 text-white/60 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            <input type="text" readonly value="{{ session('new_invoice_url') }}" id="newInvoiceUrlInput" class="bg-transparent border-none text-white text-xs font-mono w-full focus:outline-none select-all">
                        </div>
                        <button type="button" onclick="copyInvoiceLink('{{ session('new_invoice_url') }}', this)"
                                class="px-5 py-3 bg-white text-primary rounded-xl font-bold text-xs hover:bg-white/90 transition-all flex items-center justify-center gap-2 shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span>Copy Link</span>
                        </button>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode('Hello, please find the official school fee payment link for ' . session('new_invoice_student') . ' (Invoice #' . session('new_invoice_number') . '): ' . session('new_invoice_url') . ' - Click the link to view itemized breakdown and make payment.') }}"
                           target="_blank"
                           class="px-5 py-3 bg-emerald-500 text-white rounded-xl font-bold text-xs hover:bg-emerald-600 transition-all flex items-center justify-center gap-2 shadow-md">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            Share on WhatsApp
                        </a>
                        <a href="{{ session('new_invoice_url') }}" target="_blank"
                           class="px-4 py-3 bg-white/20 text-white rounded-xl font-bold text-xs hover:bg-white/30 transition-all flex items-center justify-center gap-1.5">
                            Open Portal
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            @elseif(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Summary Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                @foreach([
                    ['label' => 'Total Invoiced', 'value' => $summary['total'], 'color' => 'primary'],
                    ['label' => 'Total Collected', 'value' => $summary['paid'], 'color' => 'success'],
                    ['label' => 'Total Outstanding', 'value' => $summary['unpaid'], 'color' => 'danger'],
                    ['label' => 'Partial Payments', 'value' => $summary['partial'], 'color' => 'warning'],
                ] as $card)
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-{{ $card['color'] }} mb-1">{{ $currencySymbol }}{{ number_format($card['value'], 2) }}</p>
                    <p class="text-sm text-gray-400 font-medium">{{ $card['label'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Invoices Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-dark">Invoice Ledger ({{ $invoices->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Inv Number</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Student</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Items</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Total</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Paid</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Balance</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Payment Link</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($invoices as $inv)
                            @php
                                $payUrl = route('invoices.public.pay', $inv->uuid);
                                $studentName = ($inv->student->user->first_name ?? 'Student') . ' ' . ($inv->student->user->last_name ?? '');
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-mono font-bold text-primary">{{ $inv->invoice_number }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-dark">
                                    {{ $studentName }}
                                    @if($inv->student && $inv->student->admission_no)
                                    <span class="block text-xs font-mono text-gray-400">{{ $inv->student->admission_no }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($inv->items->count() > 0)
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-primary/10 text-primary">{{ $inv->items->count() }} {{ Str::plural('item', $inv->items->count()) }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-dark">{{ $currencySymbol }}{{ number_format($inv->total_amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-success font-semibold">{{ $currencySymbol }}{{ number_format($inv->paid_amount, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-danger font-semibold">{{ $currencySymbol }}{{ number_format($inv->balance, 2) }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $col = $inv->status === 'paid' ? 'success' : ($inv->status === 'partially_paid' ? 'warning' : 'danger');
                                    @endphp
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-{{ $col }}/10 text-{{ $col }} uppercase">{{ str_replace('_', ' ', $inv->status) }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-1.5">
                                        <button type="button"
                                                onclick="copyInvoiceLink('{{ $payUrl }}', this)"
                                                class="px-2.5 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg text-xs font-semibold flex items-center gap-1 transition-all"
                                                title="Copy payment link to clipboard">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            <span>Copy</span>
                                        </button>
                                        <a href="https://api.whatsapp.com/send?text={{ urlencode('Hello, please find the payment link for ' . $studentName . ' (Invoice #' . $inv->invoice_number . '): ' . $payUrl) }}"
                                           target="_blank"
                                           class="p-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-xs transition-colors"
                                           title="Send via WhatsApp">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                        </a>
                                        <a href="{{ $payUrl }}" target="_blank"
                                           class="p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs transition-colors"
                                           title="Open Payment Portal">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="{{ route('invoices.destroy', $inv->id) }}" onsubmit="return confirm('Delete invoice?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-danger font-semibold hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="px-6 py-12 text-center text-gray-400">No invoices generated yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function copyInvoiceLink(url, btn) {
    navigator.clipboard.writeText(url).then(() => {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Copied!';
        btn.classList.add('bg-emerald-500', 'text-white');
        btn.classList.remove('bg-primary/10', 'text-primary', 'bg-white');
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('bg-emerald-500', 'text-white');
            btn.classList.add('bg-primary/10', 'text-primary');
        }, 2000);
    }).catch(err => {
        prompt('Copy this invoice payment URL:', url);
    });
}
</script>
@endsection
