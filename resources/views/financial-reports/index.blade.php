@extends('layouts.app')
@section('title', 'Financial Reports')
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
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">School Financial Statement</h1>
                <p class="text-gray-500">Cumulative revenue, billing collections, outstanding receivables, and expense reports</p>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Total Invoiced</p>
                    <p class="text-3xl font-extrabold text-dark">{{ $currencySymbol }}{{ number_format($totalInvoiced, 2) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Total Revenue (Cash)</p>
                    <p class="text-3xl font-extrabold text-success">{{ $currencySymbol }}{{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Outstanding Receivables</p>
                    <p class="text-3xl font-extrabold text-warning">{{ $currencySymbol }}{{ number_format($totalOutstanding, 2) }}</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-2">Total Expenses</p>
                    <p class="text-3xl font-extrabold text-danger">{{ $currencySymbol }}{{ number_format($totalExpenses, 2) }}</p>
                </div>
            </div>

            {{-- Month Performance Overview --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-dark mb-4">Cash Flow Status (This Month)</h3>
                    <div class="flex items-center gap-6 py-6 border-b border-gray-100">
                        <div class="flex-1 text-center">
                            <span class="text-xs text-gray-400 font-semibold uppercase block">Monthly Income</span>
                            <span class="text-2xl font-bold text-success block mt-1">+{{ $currencySymbol }}{{ number_format($monthlyRevenue, 2) }}</span>
                        </div>
                        <div class="w-px h-12 bg-gray-200"></div>
                        <div class="flex-1 text-center">
                            <span class="text-xs text-gray-400 font-semibold uppercase block">Monthly Outflow</span>
                            <span class="text-2xl font-bold text-danger block mt-1">-{{ $currencySymbol }}{{ number_format($monthlyExpenses, 2) }}</span>
                        </div>
                        <div class="w-px h-12 bg-gray-200"></div>
                        @php
                            $surplus = $monthlyRevenue - $monthlyExpenses;
                        @endphp
                        <div class="flex-1 text-center">
                            <span class="text-xs text-gray-400 font-semibold uppercase block">Net Balance</span>
                            <span class="text-2xl font-bold {{ $surplus >= 0 ? 'text-primary' : 'text-danger' }} block mt-1">
                                {{ $currencySymbol }}{{ number_format($surplus, 2) }}
                            </span>
                        </div>
                    </div>

                    {{-- Expenses by category --}}
                    <div class="mt-6">
                        <h4 class="text-sm font-bold text-dark mb-3">Expenses Breakdown</h4>
                        <div class="space-y-3">
                            @forelse($expensesByCategory as $cat)
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1">
                                    <span class="text-gray-600 uppercase">{{ $cat->category }}</span>
                                    <span class="text-dark">{{ $currencySymbol }}{{ number_format($cat->total, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                    @php
                                        $pct = $totalExpenses > 0 ? ($cat->total / $totalExpenses) * 100 : 0;
                                    @endphp
                                    <div class="bg-danger h-full" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                            @empty
                            <p class="text-xs text-gray-400 italic py-4">No categories recorded yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Recent Financial Activity Logs --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-dark mb-4">Cash Ledger</h3>
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                            <p class="font-bold text-[10px] text-gray-400 uppercase tracking-widest mb-2">Inflows</p>
                            @forelse($recentPayments as $p)
                            <div class="flex items-center justify-between text-xs py-1 border-b border-gray-50">
                                <div>
                                    <p class="font-semibold text-dark">{{ $p->student->user->first_name ?? 'N/A' }} {{ $p->student->user->last_name ?? '' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $p->paid_at?->format('M d, Y') }}</p>
                                </div>
                                <span class="font-bold text-success">+{{ $currencySymbol }}{{ number_format($p->amount, 2) }}</span>
                            </div>
                            @empty
                            <p class="text-[11px] text-gray-400 italic">No inflows</p>
                            @endforelse

                            <p class="font-bold text-[10px] text-gray-400 uppercase tracking-widest mt-6 mb-2">Outflows</p>
                            @forelse($recentExpenses as $e)
                            <div class="flex items-center justify-between text-xs py-1 border-b border-gray-50">
                                <div>
                                    <p class="font-semibold text-dark">{{ $e->title }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $e->expense_date?->format('M d, Y') }}</p>
                                </div>
                                <span class="font-bold text-danger">-{{ $currencySymbol }}{{ number_format($e->amount, 2) }}</span>
                            </div>
                            @empty
                            <p class="text-[11px] text-gray-400 italic">No outflows</p>
                            @endforelse
                        </div>
                    </div>

                    <button class="w-full mt-6 py-2.5 bg-gray-900 text-white rounded-xl hover:bg-black font-semibold text-xs transition-colors" onclick="window.print()">Export Statement</button>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
