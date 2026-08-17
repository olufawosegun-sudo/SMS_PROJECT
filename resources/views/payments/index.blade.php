@extends('layouts.app')
@section('title', 'Payments')
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
                    <h1 class="text-3xl font-bold text-dark mb-2">Recorded Payments</h1>
                    <p class="text-gray-500">Collect fees and document payment transactions</p>
                </div>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Summary Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-success mb-1">{{ $currencySymbol }}{{ number_format($summary['total_collected'], 2) }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Revenue</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-primary mb-1">{{ $currencySymbol }}{{ number_format($summary['this_month'], 2) }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Collected This Month</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-accent-dark mb-1">{{ $summary['count'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Transactions Count</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Record Payment --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Record Payment</h3>
                    <form method="POST" action="{{ route('payments.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Select Student</label>
                            <select name="student_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="">Select Student</option>
                                @foreach($students as $st)
                                <option value="{{ $st->id }}">{{ $st->user->first_name }} {{ $st->user->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Link to Invoice (Optional)</label>
                            <select name="invoice_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="">Unlinked Payment</option>
                                @foreach($invoices as $inv)
                                <option value="{{ $inv->id }}">{{ $inv->invoice_number }} - {{ $inv->student->user->first_name ?? '' }} ({{ $currencySymbol }}{{ number_format($inv->amount, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Amount Paid</label>
                            <input type="number" name="amount" required step="0.01" min="0" placeholder="0.00" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Payment Method</label>
                            <select name="payment_method" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="card">Credit/Debit Card</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Transaction</button>
                    </form>
                </div>

                {{-- Right: Transactions Table --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Recent Transactions</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Receipt Ref</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Student</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Amount</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Method</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($payments as $pay)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono font-bold text-primary">{{ $pay->payment_reference }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $pay->student->user->first_name ?? 'N/A' }} {{ $pay->student->user->last_name ?? '' }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-success">{{ $currencySymbol }}{{ number_format($pay->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 uppercase">{{ str_replace('_', ' ', $pay->payment_method) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $pay->paid_at?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('payments.destroy', $pay->id) }}" onsubmit="return confirm('Delete payment transaction?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs text-danger font-semibold hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No payment logs recorded yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
