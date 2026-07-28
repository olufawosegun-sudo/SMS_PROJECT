@extends('layouts.app')

@section('title', 'Payments & Revenue — Super Admin')

@push('styles')
<style>
    .sa-body { background: #f0f2f5; min-height: 100vh; }
    .sa-main { margin-left: 280px; padding: 0; }
    @media (max-width: 1023px) { .sa-main { margin-left: 0; } }
    .sa-topbar { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0,0,0,0.04); padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 20; }
    .sa-panel { background: #fff; border-radius: 20px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02); overflow: hidden; }
    .sa-table { width: 100%; text-align: left; font-size: 13px; }
    .sa-table thead th { padding: 12px 20px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700; color: #8b95a5; background: #fafbfc; border-bottom: 1px solid #f0f2f5; }
    .sa-table tbody td { padding: 14px 20px; border-bottom: 1px solid #f8f9fa; color: #374151; }
    .sa-table tbody tr:hover { background: #f8fafc; }
    .sa-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; }
    .sa-badge.active { background: rgba(39,174,96,0.08); color: #27AE60; }
    .sa-badge.expired { background: rgba(231,76,60,0.08); color: #E74C3C; }
    .summary-card { background: #fff; border-radius: 16px; padding: 20px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
    .sa-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 10px; font-size: 12px; font-weight: 700; transition: all 0.2s; cursor: pointer; border: none; }
    .sa-btn-primary { background: #1B6B3E; color: #fff; }
    .sa-btn-primary:hover { background: #0F4D2A; }
    .filter-input { padding: 8px 14px; background: #fafbfc; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 12px; color: #374151; outline: none; transition: border-color 0.2s; }
    .filter-input:focus { border-color: #1B6B3E; }
    .tab-btn { padding: 10px 20px; font-size: 13px; font-weight: 700; color: #6b7280; border-bottom: 2px solid transparent; transition: all 0.2s; }
    .tab-btn.active { color: #1B6B3E; border-bottom-color: #1B6B3E; }
</style>
@endpush

@section('body')
<div class="sa-body">
    @include('super-admin.partials._sidebar', ['activePage' => 'payments'])

    <div class="sa-main">
        <div class="sa-topbar">
            <div class="flex items-center gap-3">
                <button onclick="toggleSASidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Platform Payments Ledger</h1>
                    <p class="text-xs text-gray-400">Complete transaction history across all schools & subscriptions</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            {{-- Metric Summary --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="summary-card">
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">School Fees Revenue</p>
                    <p class="text-2xl font-extrabold mt-1" style="color:#1B6B3E;">₦{{ number_format($paymentStats['total_collected'], 0) }}</p>
                </div>
                <div class="summary-card">
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Subscription Revenue</p>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">₦{{ number_format($paymentStats['sub_collected'], 0) }}</p>
                </div>
                <div class="summary-card">
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Total Revenue This Month</p>
                    <p class="text-2xl font-extrabold text-blue-600 mt-1">₦{{ number_format($paymentStats['this_month'], 0) }}</p>
                </div>
                <div class="summary-card">
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Pending School Fees</p>
                    <p class="text-2xl font-extrabold text-gray-400 mt-1">₦{{ number_format($paymentStats['pending'], 0) }}</p>
                </div>
            </div>

            {{-- Main Panel with Tabs --}}
            <div class="sa-panel">
                {{-- Navigation Tabs --}}
                <div class="flex items-center gap-2 border-b border-gray-100 px-6 pt-2">
                    <a href="{{ route('super-admin.payments', ['tab' => 'school_fees']) }}" class="tab-btn {{ $tab === 'school_fees' ? 'active' : '' }}">
                        School Fee Payments
                    </a>
                    <a href="{{ route('super-admin.payments', ['tab' => 'subscriptions']) }}" class="tab-btn {{ $tab === 'subscriptions' ? 'active' : '' }}">
                        Subscription Payments
                    </a>
                </div>

                {{-- Filters --}}
                <div class="p-4 border-b border-gray-50">
                    <form method="GET" action="{{ route('super-admin.payments') }}" class="flex flex-wrap items-center gap-3">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ $tab === 'subscriptions' ? 'Search school name...' : 'Search student name...' }}" class="filter-input flex-1 min-w-[200px]">
                        <select name="status" class="filter-input">
                            <option value="">All Status</option>
                            <option value="successful" {{ request('status') === 'successful' ? 'selected' : '' }}>Successful / Paid</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        <button type="submit" class="sa-btn sa-btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Filter
                        </button>
                    </form>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="sa-table">
                        @if($tab === 'subscriptions')
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>School</th>
                                <th>Subscription Plan</th>
                                <th>Amount</th>
                                <th>Gateway</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $p)
                            <tr>
                                <td class="font-mono text-xs font-bold text-gray-800">{{ $p->payment_reference }}</td>
                                <td class="font-bold text-gray-900">{{ $p->subscription->school->name ?? 'N/A' }}</td>
                                <td><span class="sa-badge starter">{{ $p->subscription->plan ?? 'N/A' }}</span></td>
                                <td class="font-bold" style="color:#1B6B3E;">₦{{ number_format($p->amount, 2) }}</td>
                                <td class="text-xs text-gray-500 capitalize">{{ $p->gateway ?? 'N/A' }}</td>
                                <td><span class="sa-badge {{ $p->status === 'paid' ? 'active' : 'expired' }}">{{ ucfirst($p->status) }}</span></td>
                                <td class="text-xs text-gray-400">{{ $p->paid_at ? $p->paid_at->format('M d, Y') : ($p->created_at ? $p->created_at->format('M d, Y') : 'N/A') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-10 text-gray-400">No subscription payments found.</td></tr>
                            @endforelse
                        </tbody>
                        @else
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Student</th>
                                <th>School Branch</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $p)
                            <tr>
                                <td class="font-mono text-xs font-bold text-gray-800">{{ $p->payment_reference }}</td>
                                <td>
                                    <p class="font-bold text-gray-900 text-sm">{{ $p->student->first_name ?? '' }} {{ $p->student->last_name ?? '' }}</p>
                                    <p class="text-[10px] text-gray-400">ADM: {{ $p->student->admission_number ?? 'N/A' }}</p>
                                </td>
                                <td class="text-xs text-gray-500">{{ $p->schoolBranch->name ?? 'Main Branch' }}</td>
                                <td class="font-bold" style="color:#1B6B3E;">₦{{ number_format($p->amount, 2) }}</td>
                                <td class="text-xs text-gray-500 capitalize">{{ $p->payment_method ?? $p->gateway ?? 'N/A' }}</td>
                                <td><span class="sa-badge {{ in_array($p->status, ['successful', 'paid']) ? 'active' : 'expired' }}">{{ ucfirst($p->status) }}</span></td>
                                <td class="text-xs text-gray-400">{{ $p->paid_at ? $p->paid_at->format('M d, Y') : ($p->created_at ? $p->created_at->format('M d, Y') : 'N/A') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-10 text-gray-400">No school fee payments found.</td></tr>
                            @endforelse
                        </tbody>
                        @endif
                    </table>
                </div>

                @if($payments->hasPages())
                <div class="p-4 border-t border-gray-50">
                    {{ $payments->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
