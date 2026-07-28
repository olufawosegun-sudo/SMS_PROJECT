@extends('layouts.app')

@section('title', 'Subscription Management — Super Admin')

@push('styles')
<style>
    .sa-body { background: #f0f2f5; min-height: 100vh; }
    .sa-main { margin-left: 280px; padding: 0; }
    @media (max-width: 1023px) { .sa-main { margin-left: 0; } }
    .sa-topbar { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0,0,0,0.04); padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 20; }
    .sa-panel { background: #fff; border-radius: 20px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02); overflow: hidden; }
    .sa-panel-header { padding: 20px 24px; border-bottom: 1px solid #f0f2f5; display: flex; align-items: center; justify-content: space-between; }
    .sa-table { width: 100%; text-align: left; font-size: 13px; }
    .sa-table thead th { padding: 12px 20px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700; color: #8b95a5; background: #fafbfc; border-bottom: 1px solid #f0f2f5; }
    .sa-table tbody td { padding: 14px 20px; border-bottom: 1px solid #f8f9fa; color: #374151; }
    .sa-table tbody tr:hover { background: #f8fafc; }
    .sa-table tbody tr:last-child td { border-bottom: none; }
    .sa-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; }
    .sa-badge.active { background: rgba(39,174,96,0.08); color: #27AE60; }
    .sa-badge.expired { background: rgba(231,76,60,0.08); color: #E74C3C; }
    .sa-badge.cancelled { background: rgba(149,165,166,0.08); color: #95A5A6; }
    .sa-badge.starter { background: rgba(52,152,219,0.06); color: #3498DB; }
    .sa-badge.standard { background: rgba(142,68,173,0.06); color: #8E44AD; }
    .sa-badge.premium { background: rgba(212,168,67,0.08); color: #B8912E; }
    .summary-card { background: #fff; border-radius: 16px; padding: 20px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
    .sa-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 10px; font-size: 12px; font-weight: 700; transition: all 0.2s; cursor: pointer; border: none; }
    .sa-btn-primary { background: #1B6B3E; color: #fff; }
    .sa-btn-primary:hover { background: #0F4D2A; }
    .sa-btn-sm { padding: 5px 10px; font-size: 11px; border-radius: 8px; }
    .filter-input { padding: 8px 14px; background: #fafbfc; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 12px; color: #374151; outline: none; transition: border-color 0.2s; }
    .filter-input:focus { border-color: #1B6B3E; }
</style>
@endpush

@section('body')
<div class="sa-body">
    @include('super-admin.partials._sidebar', ['activePage' => 'subscriptions'])

    <div class="sa-main">
        <div class="sa-topbar">
            <div class="flex items-center gap-3">
                <button onclick="toggleSASidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Subscription Management</h1>
                    <p class="text-xs text-gray-400">Manage subscription plans for all registered schools</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-semibold text-sm">{{ session('success') }}</div>
            @endif

            {{-- Summary Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="summary-card">
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Active</p>
                    <p class="text-2xl font-extrabold text-green-600 mt-1">{{ number_format($summaryStats['active']) }}</p>
                </div>
                <div class="summary-card">
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Expired</p>
                    <p class="text-2xl font-extrabold text-red-500 mt-1">{{ number_format($summaryStats['expired']) }}</p>
                </div>
                <div class="summary-card">
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Cancelled</p>
                    <p class="text-2xl font-extrabold text-gray-400 mt-1">{{ number_format($summaryStats['cancelled']) }}</p>
                </div>
                <div class="summary-card">
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">Revenue This Month</p>
                    <p class="text-2xl font-extrabold mt-1" style="color:#1B6B3E;">₦{{ number_format($summaryStats['revenue_this_month'], 0) }}</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="sa-panel">
                <div class="p-4 border-b border-gray-50">
                    <form method="GET" action="{{ route('super-admin.subscriptions') }}" class="flex flex-wrap items-center gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search school name..." class="filter-input flex-1 min-w-[200px]">
                        <select name="plan" class="filter-input">
                            <option value="">All Plans</option>
                            <option value="Starter" {{ request('plan') === 'Starter' ? 'selected' : '' }}>Starter</option>
                            <option value="Standard" {{ request('plan') === 'Standard' ? 'selected' : '' }}>Standard</option>
                            <option value="Premium" {{ request('plan') === 'Premium' ? 'selected' : '' }}>Premium</option>
                        </select>
                        <select name="status" class="filter-input">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                        <thead>
                            <tr>
                                <th>School</th>
                                <th>Plan</th>
                                <th>Price</th>
                                <th>Billing</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Days Left</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $sub)
                            <tr>
                                <td>
                                    <p class="font-bold text-gray-900 text-sm">{{ $sub->school->name ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $sub->school->email ?? '' }}</p>
                                </td>
                                <td>
                                    <span class="sa-badge {{ strtolower($sub->plan) }}">{{ $sub->plan }}</span>
                                </td>
                                <td class="font-semibold" style="color:#1B6B3E;">₦{{ number_format($sub->price, 0) }}</td>
                                <td class="text-xs text-gray-500 capitalize">{{ $sub->billing_cycle }}</td>
                                <td class="text-xs text-gray-500">{{ $sub->starts_at ? $sub->starts_at->format('M d, Y') : 'N/A' }}</td>
                                <td class="text-xs text-gray-500">{{ $sub->ends_at ? $sub->ends_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <span class="sa-badge {{ $sub->status }}">{{ ucfirst($sub->status) }}</span>
                                </td>
                                <td>
                                    @if($sub->isActive())
                                        @if($sub->isExpiringSoon())
                                            <span class="text-xs font-bold text-orange-500">{{ $sub->daysRemaining() }}d</span>
                                        @else
                                            <span class="text-xs font-bold text-green-600">{{ $sub->daysRemaining() }}d</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('super-admin.subscriptions.show', $sub->id) }}" class="sa-btn sa-btn-sm" style="background: rgba(27,107,62,0.06); color: #1B6B3E;">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center py-10 text-gray-400">No subscriptions found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($subscriptions->hasPages())
                <div class="p-4 border-t border-gray-50">
                    {{ $subscriptions->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
