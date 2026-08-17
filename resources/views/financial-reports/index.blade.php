@extends('layouts.app')
@section('title', 'Financial Reports')
@section('body')
@php
    $currencySymbol = $school->currency_symbol ?? Auth::user()->school->currency_symbol ?? '₦';
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

            {{-- ===== PROFIT / LOSS HERO CARD ===== --}}
            <div class="mb-8 bg-gradient-to-r {{ $netProfit >= 0 ? 'from-emerald-600 to-emerald-800' : 'from-red-600 to-red-800' }} rounded-2xl p-6 md:p-8 relative overflow-hidden shadow-lg">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
                <div class="absolute bottom-0 left-10 w-32 h-32 bg-white/5 rounded-full translate-y-1/2"></div>
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <p class="text-white/70 text-xs font-bold uppercase tracking-widest mb-2">{{ $netProfit >= 0 ? '📈 Net Profit' : '📉 Net Loss' }}</p>
                            <p class="text-4xl md:text-5xl font-extrabold text-white mb-2">
                                {{ $netProfit >= 0 ? '+' : '-' }}{{ $currencySymbol }}{{ number_format(abs($netProfit), 2) }}
                            </p>
                            <p class="text-white/60 text-sm">Revenue minus Expenses for the school</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="bg-white/10 backdrop-blur-md rounded-xl px-5 py-4 border border-white/20 text-center min-w-[120px]">
                                <p class="text-white/60 text-[10px] font-bold uppercase tracking-wider mb-1">Profit Margin</p>
                                <p class="text-2xl font-extrabold text-white">{{ $profitMargin }}%</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-md rounded-xl px-5 py-4 border border-white/20 text-center min-w-[120px]">
                                <p class="text-white/60 text-[10px] font-bold uppercase tracking-wider mb-1">Collection Rate</p>
                                <p class="text-2xl font-extrabold text-white">{{ $collectionRate }}%</p>
                            </div>
                        </div>
                    </div>

                    {{-- P&L Breakdown --}}
                    <div class="mt-6 pt-5 border-t border-white/15 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-white/50 text-[10px] font-bold uppercase">Total Revenue</p>
                                <p class="text-lg font-bold text-white">{{ $currencySymbol }}{{ number_format($totalRevenue, 2) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            </div>
                            <div>
                                <p class="text-white/50 text-[10px] font-bold uppercase">Total Expenses</p>
                                <p class="text-lg font-bold text-white">{{ $currencySymbol }}{{ number_format($totalExpenses, 2) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-white/50 text-[10px] font-bold uppercase">= Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</p>
                                <p class="text-lg font-bold text-white">{{ $netProfit >= 0 ? '+' : '-' }}{{ $currencySymbol }}{{ number_format(abs($netProfit), 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
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
                                {{ $surplus >= 0 ? '+' : '-' }}{{ $currencySymbol }}{{ number_format(abs($surplus), 2) }}
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
