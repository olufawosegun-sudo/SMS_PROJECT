@extends('layouts.app')

@section('title', 'Super Admin Command Center — EduWest Africa')

@push('styles')
<style>
    .sa-body { background: #f0f2f5; min-height: 100vh; }
    .sa-main { margin-left: 280px; padding: 0; }
    @media (max-width: 1023px) { .sa-main { margin-left: 0; } }

    /* Stat Cards */
    .stat-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px;
        border: 1px solid rgba(0,0,0,0.04);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: 20px 20px 0 0;
    }
    .stat-card.green::before { background: linear-gradient(90deg, #1B6B3E, #2D8F54); }
    .stat-card.gold::before { background: linear-gradient(90deg, #D4A843, #F5E6B8); }
    .stat-card.blue::before { background: linear-gradient(90deg, #3498DB, #5DADE2); }
    .stat-card.purple::before { background: linear-gradient(90deg, #8E44AD, #BB6BD9); }
    .stat-card.emerald::before { background: linear-gradient(90deg, #27AE60, #2ECC71); }
    .stat-card.orange::before { background: linear-gradient(90deg, #E67E22, #F39C12); }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
    }
    .stat-icon.green { background: rgba(27,107,62,0.08); color: #1B6B3E; }
    .stat-icon.gold { background: rgba(212,168,67,0.1); color: #D4A843; }
    .stat-icon.blue { background: rgba(52,152,219,0.08); color: #3498DB; }
    .stat-icon.purple { background: rgba(142,68,173,0.08); color: #8E44AD; }
    .stat-icon.emerald { background: rgba(39,174,96,0.08); color: #27AE60; }
    .stat-icon.orange { background: rgba(230,126,34,0.08); color: #E67E22; }

    /* Panel */
    .sa-panel {
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.04);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .sa-panel-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f0f2f5;
        display: flex; align-items: center; justify-content: space-between;
    }
    .sa-panel-body { padding: 0; }

    /* Table */
    .sa-table { width: 100%; text-align: left; font-size: 13px; }
    .sa-table thead th {
        padding: 12px 20px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-weight: 700;
        color: #8b95a5;
        background: #fafbfc;
        border-bottom: 1px solid #f0f2f5;
    }
    .sa-table tbody td {
        padding: 14px 20px;
        border-bottom: 1px solid #f8f9fa;
        color: #374151;
    }
    .sa-table tbody tr:hover { background: #f8fafc; }
    .sa-table tbody tr:last-child td { border-bottom: none; }

    /* Badge */
    .sa-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
    }
    .sa-badge.active { background: rgba(39,174,96,0.08); color: #27AE60; }
    .sa-badge.expired { background: rgba(231,76,60,0.08); color: #E74C3C; }
    .sa-badge.cancelled { background: rgba(149,165,166,0.08); color: #95A5A6; }
    .sa-badge.warning { background: rgba(243,156,18,0.08); color: #F39C12; }
    .sa-badge.starter { background: rgba(52,152,219,0.06); color: #3498DB; }
    .sa-badge.standard { background: rgba(142,68,173,0.06); color: #8E44AD; }
    .sa-badge.premium { background: rgba(212,168,67,0.08); color: #B8912E; }

    /* Chart container */
    .chart-container { position: relative; }

    /* Activity item */
    .activity-item {
        padding: 14px 20px;
        border-bottom: 1px solid #f8f9fa;
        display: flex; align-items: flex-start; gap: 12px;
        transition: background 0.15s;
    }
    .activity-item:hover { background: #f8fafc; }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        margin-top: 5px;
        flex-shrink: 0;
    }

    /* Topbar for SA */
    .sa-topbar {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(0,0,0,0.04);
        padding: 16px 32px;
        display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0; z-index: 20;
    }

    /* Animation */
    .animate-up { animation: saFadeUp 0.5s ease-out forwards; opacity: 0; }
    @keyframes saFadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.1s; }
    .delay-3 { animation-delay: 0.15s; }
    .delay-4 { animation-delay: 0.2s; }
    .delay-5 { animation-delay: 0.25s; }
    .delay-6 { animation-delay: 0.3s; }

    /* Expiring alert */
    .expiring-alert {
        background: linear-gradient(135deg, rgba(243,156,18,0.05) 0%, rgba(231,76,60,0.03) 100%);
        border: 1px solid rgba(243,156,18,0.12);
        border-radius: 14px;
        padding: 14px 16px;
        display: flex; align-items: center; gap: 12px;
    }
</style>
@endpush

@section('body')
<div class="sa-body">
    @include('super-admin.partials._sidebar', ['activePage' => 'dashboard'])

    <div class="sa-main">
        {{-- Top Bar --}}
        <div class="sa-topbar">
            <div class="flex items-center gap-3">
                <button onclick="toggleSASidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Command Center</h1>
                    <p class="text-xs text-gray-400">Welcome back, {{ Auth::user()->first_name }}. Here's your platform overview.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-100 rounded-full text-[11px] font-bold text-green-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    System Online
                </span>
                <span class="text-xs text-gray-400">{{ now()->format('D, M d Y — h:i A') }}</span>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-8">
            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-semibold text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
            @endif

            {{-- ============================================
                 STATS GRID — 6 Premium Metric Cards
                 ============================================ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5">
                {{-- Total Schools --}}
                <div class="stat-card green animate-up delay-1">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon green">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900">{{ number_format($stats['total_schools']) }}</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Registered Schools</p>
                    <p class="text-[10px] font-bold mt-2" style="color: #1B6B3E;">{{ $stats['active_schools'] }} active</p>
                </div>

                {{-- Total Students --}}
                <div class="stat-card blue animate-up delay-2">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon blue">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900">{{ number_format($stats['total_students']) }}</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Total Students</p>
                    <p class="text-[10px] font-bold text-blue-500 mt-2">Platform-wide</p>
                </div>

                {{-- Total Staff --}}
                <div class="stat-card purple animate-up delay-3">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon purple">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900">{{ number_format($stats['total_staff']) }}</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Teachers & Staff</p>
                    <p class="text-[10px] font-bold text-purple-500 mt-2">{{ number_format($stats['total_users']) }} total users</p>
                </div>

                {{-- Active Subscriptions --}}
                <div class="stat-card gold animate-up delay-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon gold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900">{{ number_format($stats['active_subscriptions']) }}</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Active Subscriptions</p>
                    @if($stats['expiring_soon'] > 0)
                    <p class="text-[10px] font-bold text-orange-500 mt-2">{{ $stats['expiring_soon'] }} expiring soon</p>
                    @else
                    <p class="text-[10px] font-bold text-green-500 mt-2">All healthy</p>
                    @endif
                </div>

                {{-- Total Revenue --}}
                <div class="stat-card emerald animate-up delay-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon emerald">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold" style="color: #1B6B3E;">₦{{ number_format($stats['total_revenue'], 0) }}</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Total Revenue</p>
                    <p class="text-[10px] font-bold text-green-600 mt-2">All-time collected</p>
                </div>

                {{-- This Month --}}
                <div class="stat-card orange animate-up delay-6">
                    <div class="flex items-start justify-between mb-3">
                        <div class="stat-icon orange">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900">₦{{ number_format($stats['this_month_revenue'], 0) }}</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">This Month</p>
                    <p class="text-[10px] font-bold text-orange-500 mt-2">{{ now()->format('F Y') }}</p>
                </div>
            </div>

            {{-- ============================================
                 ROW 2: Revenue Chart + Subscription Health
                 ============================================ --}}
            <div class="grid lg:grid-cols-3 gap-6">
                {{-- Revenue Chart --}}
                <div class="lg:col-span-2 sa-panel animate-up" style="animation-delay:0.35s;">
                    <div class="sa-panel-header">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Revenue Overview</h3>
                            <p class="text-[11px] text-gray-400 mt-0.5">Last 6 months — School Fees vs Subscriptions</p>
                        </div>
                        <a href="{{ route('super-admin.payments') }}" class="text-[11px] font-bold" style="color: #1B6B3E;">View All →</a>
                    </div>
                    <div class="p-6">
                        <div class="chart-container" style="height: 260px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                        <div class="flex items-center justify-center gap-6 mt-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-sm" style="background: #1B6B3E;"></span>
                                <span class="text-[11px] text-gray-500 font-medium">School Fees</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-sm" style="background: #D4A843;"></span>
                                <span class="text-[11px] text-gray-500 font-medium">Subscriptions</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Subscription Health --}}
                <div class="sa-panel animate-up" style="animation-delay:0.4s;">
                    <div class="sa-panel-header">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Subscription Plans</h3>
                            <p class="text-[11px] text-gray-400 mt-0.5">Active plan distribution</p>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col items-center">
                        <div class="chart-container" style="width: 180px; height: 180px;">
                            <canvas id="planChart"></canvas>
                        </div>
                        <div class="w-full mt-5 space-y-2.5">
                            @php
                                $planColors = ['Starter' => '#3498DB', 'Standard' => '#8E44AD', 'Premium' => '#D4A843'];
                                $totalPlans = array_sum($planDistribution) ?: 1;
                            @endphp
                            @foreach(['Starter', 'Standard', 'Premium'] as $plan)
                            @php $count = $planDistribution[$plan] ?? 0; @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $planColors[$plan] }};"></span>
                                    <span class="text-xs font-semibold text-gray-600">{{ $plan }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-900">{{ $count }}</span>
                                    <span class="text-[10px] text-gray-400">{{ round(($count / $totalPlans) * 100) }}%</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================
                 ROW 3: Expiring Subscriptions Alert
                 ============================================ --}}
            @if($expiringSubscriptions->count() > 0)
            <div class="sa-panel animate-up" style="animation-delay:0.45s;">
                <div class="sa-panel-header">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        <h3 class="text-sm font-bold text-gray-900">Subscriptions Expiring Soon</h3>
                    </div>
                    <a href="{{ route('super-admin.subscriptions') }}?status=active" class="text-[11px] font-bold" style="color: #1B6B3E;">Manage →</a>
                </div>
                <div class="p-4 grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($expiringSubscriptions as $sub)
                    <div class="expiring-alert">
                        <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ $sub->school->name ?? 'Unknown' }}</p>
                            <p class="text-[10px] text-gray-500">{{ $sub->plan }} plan · Expires {{ $sub->ends_at->format('M d') }}</p>
                            <p class="text-[10px] font-bold text-orange-600">{{ $sub->daysRemaining() }} days left</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ============================================
                 ROW 4: Recent Schools + Activity Feed
                 ============================================ --}}
            <div class="grid lg:grid-cols-3 gap-6">
                {{-- Recent Schools --}}
                <div class="lg:col-span-2 sa-panel animate-up" style="animation-delay:0.5s;">
                    <div class="sa-panel-header">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Recent Schools</h3>
                            <p class="text-[11px] text-gray-400 mt-0.5">Latest registered institutions</p>
                        </div>
                        <a href="{{ route('super-admin.schools') }}" class="text-[11px] font-bold" style="color: #1B6B3E;">View All →</a>
                    </div>
                    <div class="sa-panel-body overflow-x-auto">
                        <table class="sa-table">
                            <thead>
                                <tr>
                                    <th>School</th>
                                    <th>Students</th>
                                    <th>Staff</th>
                                    <th>Subscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSchools as $school)
                                <tr>
                                    <td>
                                        <p class="font-bold text-gray-900 text-sm">{{ $school->name }}</p>
                                        <p class="text-[11px] text-gray-400">{{ $school->city ?? 'N/A' }}, {{ $school->country ?? 'Nigeria' }}</p>
                                    </td>
                                    <td class="font-semibold">{{ number_format($school->students_count) }}</td>
                                    <td class="font-semibold">{{ number_format($school->staff_count) }}</td>
                                    <td>
                                        @if($school->activeSubscription)
                                            <span class="sa-badge {{ strtolower($school->activeSubscription->plan) }}">{{ $school->activeSubscription->plan }}</span>
                                        @else
                                            <span class="sa-badge expired">No Plan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('super-admin.schools.toggle-status', $school->id) }}" method="POST" class="inline" onsubmit="return confirm('Toggle status?');">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-bold px-3 py-1.5 rounded-lg transition-all" style="background: rgba(27,107,62,0.06); color: #1B6B3E;" onmouseover="this.style.background='rgba(27,107,62,0.12)'" onmouseout="this.style.background='rgba(27,107,62,0.06)'">
                                                Toggle
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-8 text-gray-400">No schools registered yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Live Activity --}}
                <div class="sa-panel animate-up" style="animation-delay:0.55s;">
                    <div class="sa-panel-header">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            <h3 class="text-sm font-bold text-gray-900">Live Activity</h3>
                        </div>
                        <a href="{{ route('super-admin.activities') }}" class="text-[11px] font-bold" style="color: #1B6B3E;">View All →</a>
                    </div>
                    <div class="sa-panel-body max-h-[420px] overflow-y-auto">
                        @forelse($recentActivities as $log)
                        <div class="activity-item">
                            <div class="activity-dot" style="background: {{ ['#1B6B3E','#3498DB','#8E44AD','#D4A843','#E74C3C'][$loop->index % 5] }};"></div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-gray-700 truncate">{{ $log->user->name ?? 'System' }}</p>
                                <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2">{{ $log->action ?? $log->description ?? 'System action' }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $log->created_at ? $log->created_at->diffForHumans() : 'N/A' }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 text-gray-400 text-xs">No activity recorded.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ============================================
                 ROW 5: Recent Payments
                 ============================================ --}}
            <div class="sa-panel animate-up" style="animation-delay:0.6s;">
                <div class="sa-panel-header">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Recent Payments</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Latest successful transactions across the platform</p>
                    </div>
                    <a href="{{ route('super-admin.payments') }}" class="text-[11px] font-bold" style="color: #1B6B3E;">View All →</a>
                </div>
                <div class="sa-panel-body overflow-x-auto">
                    <table class="sa-table">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSubPayments as $sp)
                            <tr>
                                <td>
                                    <p class="font-bold text-gray-800 text-xs">{{ $sp->payment_reference }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $sp->subscription->school->name ?? 'N/A' }}</p>
                                </td>
                                <td><span class="sa-badge" style="background: rgba(212,168,67,0.08); color: #B8912E;">Subscription</span></td>
                                <td class="font-bold" style="color: #1B6B3E;">₦{{ number_format($sp->amount, 2) }}</td>
                                <td class="text-xs text-gray-500">{{ ucfirst($sp->gateway ?? 'N/A') }}</td>
                                <td class="text-xs text-gray-400">{{ $sp->paid_at ? $sp->paid_at->format('M d, Y') : ($sp->created_at ? $sp->created_at->format('M d, Y') : 'N/A') }}</td>
                            </tr>
                            @endforeach
                            @foreach($recentPayments->take(5) as $p)
                            <tr>
                                <td>
                                    <p class="font-bold text-gray-800 text-xs">{{ $p->payment_reference }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $p->student->first_name ?? '' }} {{ $p->student->last_name ?? '' }}</p>
                                </td>
                                <td><span class="sa-badge active">School Fee</span></td>
                                <td class="font-bold" style="color: #1B6B3E;">₦{{ number_format($p->amount, 2) }}</td>
                                <td class="text-xs text-gray-500">{{ ucfirst($p->payment_method ?? $p->gateway ?? 'N/A') }}</td>
                                <td class="text-xs text-gray-400">{{ $p->paid_at ? $p->paid_at->format('M d, Y') : ($p->created_at ? $p->created_at->format('M d, Y') : 'N/A') }}</td>
                            </tr>
                            @endforeach
                            @if($recentPayments->isEmpty() && $recentSubPayments->isEmpty())
                            <tr><td colspan="5" class="text-center py-8 text-gray-400">No payments recorded yet.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="text-center py-4">
                <p class="text-[11px] text-gray-400">EduWest Africa — Super Admin Master Control · {{ now()->format('Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueData = @json($monthlyRevenue);
    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
        const ctx = revenueCanvas.getContext('2d');
        const dpr = window.devicePixelRatio || 1;
        const rect = revenueCanvas.parentElement.getBoundingClientRect();
        revenueCanvas.width = rect.width * dpr;
        revenueCanvas.height = rect.height * dpr;
        revenueCanvas.style.width = rect.width + 'px';
        revenueCanvas.style.height = rect.height + 'px';
        ctx.scale(dpr, dpr);

        const w = rect.width, h = rect.height;
        const padding = { top: 20, right: 20, bottom: 40, left: 70 };
        const chartW = w - padding.left - padding.right;
        const chartH = h - padding.top - padding.bottom;

        const maxVal = Math.max(...revenueData.map(d => d.total), 1);
        const barGroupWidth = chartW / revenueData.length;
        const barWidth = barGroupWidth * 0.3;
        const gap = 3;

        // Grid lines
        ctx.strokeStyle = '#f0f2f5';
        ctx.lineWidth = 1;
        ctx.font = '10px Inter, sans-serif';
        ctx.fillStyle = '#9ca3af';
        ctx.textAlign = 'right';
        for (let i = 0; i <= 4; i++) {
            const y = padding.top + chartH - (chartH / 4) * i;
            ctx.beginPath();
            ctx.moveTo(padding.left, y);
            ctx.lineTo(w - padding.right, y);
            ctx.stroke();
            const val = (maxVal / 4) * i;
            ctx.fillText(val >= 1000000 ? (val/1000000).toFixed(1)+'M' : val >= 1000 ? (val/1000).toFixed(0)+'K' : val.toFixed(0), padding.left - 8, y + 3);
        }

        // Bars
        revenueData.forEach((d, i) => {
            const x = padding.left + barGroupWidth * i + barGroupWidth / 2;
            const schoolH = (d.school_fees / maxVal) * chartH;
            const subH = (d.subscriptions / maxVal) * chartH;

            // School fees bar
            ctx.fillStyle = '#1B6B3E';
            const rx1 = x - barWidth - gap/2;
            const ry1 = padding.top + chartH - schoolH;
            roundRect(ctx, rx1, ry1, barWidth, schoolH, 4);

            // Subscription bar
            ctx.fillStyle = '#D4A843';
            const rx2 = x + gap/2;
            const ry2 = padding.top + chartH - subH;
            roundRect(ctx, rx2, ry2, barWidth, subH, 4);

            // Labels
            ctx.fillStyle = '#9ca3af';
            ctx.font = '10px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(d.month_short, x, h - padding.bottom + 20);
        });
    }

    // Plan Distribution Donut
    const planData = @json($planDistribution);
    const planCanvas = document.getElementById('planChart');
    if (planCanvas) {
        const ctx = planCanvas.getContext('2d');
        const dpr = window.devicePixelRatio || 1;
        const rect = planCanvas.parentElement.getBoundingClientRect();
        planCanvas.width = rect.width * dpr;
        planCanvas.height = rect.height * dpr;
        planCanvas.style.width = rect.width + 'px';
        planCanvas.style.height = rect.height + 'px';
        ctx.scale(dpr, dpr);

        const cx = rect.width / 2, cy = rect.height / 2;
        const radius = Math.min(cx, cy) - 10;
        const innerRadius = radius * 0.6;
        const colors = { 'Starter': '#3498DB', 'Standard': '#8E44AD', 'Premium': '#D4A843' };
        const plans = ['Starter', 'Standard', 'Premium'];
        const total = plans.reduce((sum, p) => sum + (planData[p] || 0), 0) || 1;

        let startAngle = -Math.PI / 2;
        plans.forEach(plan => {
            const val = planData[plan] || 0;
            const sliceAngle = (val / total) * 2 * Math.PI;
            ctx.beginPath();
            ctx.arc(cx, cy, radius, startAngle, startAngle + sliceAngle);
            ctx.arc(cx, cy, innerRadius, startAngle + sliceAngle, startAngle, true);
            ctx.closePath();
            ctx.fillStyle = colors[plan];
            ctx.fill();
            startAngle += sliceAngle;
        });

        // Center text
        ctx.fillStyle = '#1f2937';
        ctx.font = 'bold 22px Inter, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(total, cx, cy - 6);
        ctx.fillStyle = '#9ca3af';
        ctx.font = '10px Inter, sans-serif';
        ctx.fillText('Active', cx, cy + 12);
    }
});

function roundRect(ctx, x, y, w, h, r) {
    if (h < 1) return;
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.lineTo(x + w - r, y);
    ctx.quadraticCurveTo(x + w, y, x + w, y + r);
    ctx.lineTo(x + w, y + h);
    ctx.lineTo(x, y + h);
    ctx.lineTo(x, y + r);
    ctx.quadraticCurveTo(x, y, x + r, y);
    ctx.closePath();
    ctx.fill();
}
</script>
@endpush
