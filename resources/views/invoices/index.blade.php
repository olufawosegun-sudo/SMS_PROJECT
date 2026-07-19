@extends('layouts.app')
@section('title', 'Invoices')
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
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Student Invoices</h1>
                    <p class="text-gray-500">Generate fee statements and outstanding balances</p>
                </div>
            </div>
            @if(session('success'))
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Create Invoice --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Generate Invoice</h3>
                    <form method="POST" action="{{ route('invoices.store') }}" class="space-y-4">
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
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Fee Category</label>
                            <select name="fee_category_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="">Select Category</option>
                                @foreach($feeCategories as $fc)
                                <option value="{{ $fc->id }}">{{ $fc->name }} ({{ $currencySymbol }}{{ number_format($fc->amount, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Amount Override</label>
                            <input type="number" name="amount" required step="0.01" min="0" placeholder="0.00" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Due Date</label>
                            <input type="date" name="due_date" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Create Invoice</button>
                    </form>
                </div>

                {{-- Right: Invoices Table --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Invoice Ledger ({{ $invoices->count() }})</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Inv Number</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Student</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Category</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Amount</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Due</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($invoices as $inv)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono font-bold text-primary">{{ $inv->invoice_number }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $inv->student->user->first_name ?? 'N/A' }} {{ $inv->student->user->last_name ?? '' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $inv->feeCategory->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-dark">{{ $currencySymbol }}{{ number_format($inv->amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $col = $inv->status === 'paid' ? 'success' : ($inv->status === 'partial' ? 'warning' : 'danger');
                                        @endphp
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-{{ $col }}/10 text-{{ $col }} uppercase">{{ $inv->status }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $inv->due_date?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('invoices.destroy', $inv->id) }}" onsubmit="return confirm('Delete invoice?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs text-danger font-semibold hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No invoices generated yet.</td></tr>
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
