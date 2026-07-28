@extends('layouts.app')

@section('title', 'Master School Directory — Super Admin')

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
    .sa-badge.starter { background: rgba(52,152,219,0.06); color: #3498DB; }
    .sa-badge.standard { background: rgba(142,68,173,0.06); color: #8E44AD; }
    .sa-badge.premium { background: rgba(212,168,67,0.08); color: #B8912E; }
    .sa-btn { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 10px; font-size: 11px; font-weight: 700; transition: all 0.2s; cursor: pointer; border: none; }
    .sa-btn-primary { background: #1B6B3E; color: #fff; }
    .sa-btn-primary:hover { background: #0F4D2A; }
    .filter-input { padding: 8px 14px; background: #fafbfc; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 12px; color: #374151; outline: none; transition: border-color 0.2s; }
    .filter-input:focus { border-color: #1B6B3E; }
</style>
@endpush

@section('body')
<div class="sa-body">
    @include('super-admin.partials._sidebar', ['activePage' => 'schools'])

    <div class="sa-main">
        <div class="sa-topbar">
            <div class="flex items-center gap-3">
                <button onclick="toggleSASidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Master Schools Directory</h1>
                    <p class="text-xs text-gray-400">View, manage, and monitor all registered institutions across West Africa</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-semibold text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-700 font-semibold text-sm">{{ session('error') }}</div>
            @endif

            <div class="sa-panel">
                {{-- Filters --}}
                <div class="p-4 border-b border-gray-50">
                    <form method="GET" action="{{ route('super-admin.schools') }}" class="flex flex-wrap items-center gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search school name, email, city, country..." class="filter-input flex-1 min-w-[220px]">
                        <select name="subscription_status" class="filter-input">
                            <option value="">All Subscriptions</option>
                            <option value="active" {{ request('subscription_status') === 'active' ? 'selected' : '' }}>Active Plan</option>
                            <option value="expired" {{ request('subscription_status') === 'expired' ? 'selected' : '' }}>Expired Plan</option>
                        </select>
                        <button type="submit" class="sa-btn sa-btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Search
                        </button>
                    </form>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="sa-table">
                        <thead>
                            <tr>
                                <th>School Details</th>
                                <th>Location</th>
                                <th>Users</th>
                                <th>Students</th>
                                <th>Staff</th>
                                <th>Plan</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schools as $school)
                            <tr>
                                <td>
                                    <p class="font-bold text-gray-900 text-sm">{{ $school->name }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $school->email }}</p>
                                    <span class="font-mono text-[10px] font-bold text-indigo-600">{{ $school->school_code }}</span>
                                </td>
                                <td class="text-xs text-gray-500">
                                    {{ $school->city ?? 'N/A' }}, {{ $school->state ?? '' }} <br/>
                                    <span class="text-gray-400">{{ $school->country ?? 'Nigeria' }}</span>
                                </td>
                                <td class="font-semibold text-gray-700">{{ number_format($school->users_count) }}</td>
                                <td class="font-bold" style="color:#1B6B3E;">{{ number_format($school->students_count) }}</td>
                                <td class="font-semibold text-purple-600">{{ number_format($school->staff_count) }}</td>
                                <td>
                                    @if($school->activeSubscription)
                                        <span class="sa-badge {{ strtolower($school->activeSubscription->plan) }}">{{ $school->activeSubscription->plan }}</span>
                                    @else
                                        <span class="sa-badge expired">No Plan</span>
                                    @endif
                                </td>
                                <td class="text-xs text-gray-400">{{ $school->created_at ? $school->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('super-admin.schools.toggle-status', $school->id) }}" method="POST" class="inline" onsubmit="return confirm('Toggle status for {{ $school->name }}?');">
                                            @csrf
                                            <button type="submit" class="sa-btn" style="background: rgba(243,156,18,0.1); color: #D68910;">
                                                Toggle
                                            </button>
                                        </form>
                                        <form action="{{ route('super-admin.schools.login-as', $school->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="sa-btn" style="background: rgba(212,168,67,0.12); color: #B8912E;" title="Login as school admin">
                                                Impersonate
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center py-10 text-gray-400">No schools found matching your search.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($schools->hasPages())
                <div class="p-4 border-t border-gray-50">
                    {{ $schools->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
