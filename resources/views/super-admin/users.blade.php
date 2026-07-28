@extends('layouts.app')

@section('title', 'Platform Users — Super Admin')

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
    .sa-badge.inactive { background: rgba(231,76,60,0.08); color: #E74C3C; }
    .sa-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 10px; font-size: 12px; font-weight: 700; transition: all 0.2s; cursor: pointer; border: none; }
    .sa-btn-primary { background: #1B6B3E; color: #fff; }
    .sa-btn-primary:hover { background: #0F4D2A; }
    .filter-input { padding: 8px 14px; background: #fafbfc; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 12px; color: #374151; outline: none; transition: border-color 0.2s; }
    .filter-input:focus { border-color: #1B6B3E; }
</style>
@endpush

@section('body')
<div class="sa-body">
    @include('super-admin.partials._sidebar', ['activePage' => 'users'])

    <div class="sa-main">
        <div class="sa-topbar">
            <div class="flex items-center gap-3">
                <button onclick="toggleSASidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Platform Users Audit</h1>
                    <p class="text-xs text-gray-400">Search and monitor all user accounts across all registered schools</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            <div class="sa-panel">
                {{-- Filters --}}
                <div class="p-4 border-b border-gray-50">
                    <form method="GET" action="{{ route('super-admin.users') }}" class="flex flex-wrap items-center gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user name, email, phone..." class="filter-input flex-1 min-w-[200px]">
                        <select name="status" class="filter-input">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                                <th>User Name</th>
                                <th>School</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Registered Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <p class="font-bold text-gray-900 text-sm">{{ $user->first_name }} {{ $user->last_name }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $user->email }}</p>
                                    <span class="text-[10px] font-mono text-gray-400">{{ $user->phone ?? 'No Phone' }}</span>
                                </td>
                                <td class="text-xs font-semibold text-gray-700">
                                    {{ $user->school->name ?? 'System Master' }}
                                </td>
                                <td>
                                    <span class="sa-badge" style="background: rgba(52,152,219,0.06); color: #3498DB;">
                                        {{ $user->role->name ?? ($user->is_super_admin ? 'Super Admin' : 'User') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="sa-badge {{ $user->status === 'active' ? 'active' : 'inactive' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="text-xs text-gray-400">
                                    {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-10 text-gray-400">No users found matching search criteria.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                <div class="p-4 border-t border-gray-50">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
