@extends('layouts.app')

@section('title', 'System Settings — Super Admin')

@push('styles')
<style>
    .sa-body { background: #f0f2f5; min-height: 100vh; }
    .sa-main { margin-left: 280px; padding: 0; }
    @media (max-width: 1023px) { .sa-main { margin-left: 0; } }
    .sa-topbar { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0,0,0,0.04); padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 20; }
    .sa-panel { background: #fff; border-radius: 20px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02); overflow: hidden; }
    .sa-panel-header { padding: 20px 24px; border-bottom: 1px solid #f0f2f5; display: flex; align-items: center; justify-content: space-between; }
    .sa-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; transition: all 0.2s; cursor: pointer; border: none; }
    .sa-btn-primary { background: #1B6B3E; color: #fff; }
    .sa-btn-primary:hover { background: #0F4D2A; }
    .form-input { width: 100%; padding: 10px 14px; background: #fafbfc; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 13px; color: #374151; outline: none; transition: border-color 0.2s; }
    .form-input:focus { border-color: #1B6B3E; background: #fff; }
</style>
@endpush

@section('body')
<div class="sa-body">
    @include('super-admin.partials._sidebar', ['activePage' => 'settings'])

    <div class="sa-main">
        <div class="sa-topbar">
            <div class="flex items-center gap-3">
                <button onclick="toggleSASidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-gray-900">System Configuration</h1>
                    <p class="text-xs text-gray-400">Platform-wide settings, subscription tiers, and broadcast announcements</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-semibold text-sm">{{ session('success') }}</div>
            @endif

            <div class="grid lg:grid-cols-2 gap-6">
                {{-- Subscription Tier Pricing --}}
                <div class="sa-panel p-6 space-y-5">
                    <div class="border-b border-gray-100 pb-3">
                        <h3 class="text-sm font-bold text-gray-900">Subscription Tier Pricing Matrix</h3>
                        <p class="text-xs text-gray-400">Configure standard platform plan rates</p>
                    </div>

                    <div class="space-y-4">
                        @foreach($planPricing as $plan => $prices)
                        <div class="p-4 bg-gray-50 rounded-xl space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold uppercase tracking-wider text-gray-700">{{ $plan }} Plan</span>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">Monthly Rate (₦)</label>
                                    <input type="number" value="{{ $prices['monthly'] }}" class="form-input text-xs" readonly>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">Yearly Rate (₦)</label>
                                    <input type="number" value="{{ $prices['yearly'] }}" class="form-input text-xs" readonly>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Platform Broadcast & Controls --}}
                <div class="space-y-6">
                    <div class="sa-panel p-6 space-y-5">
                        <div class="border-b border-gray-100 pb-3">
                            <h3 class="text-sm font-bold text-gray-900">Global System Announcement</h3>
                            <p class="text-xs text-gray-400">Broadcast a message across all school portals</p>
                        </div>

                        <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Global announcement feature configured!');" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Announcement Title</label>
                                <input type="text" placeholder="e.g. Scheduled System Maintenance" class="form-input">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Message Body</label>
                                <textarea rows="3" placeholder="Enter announcement text for all school admins..." class="form-input"></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="sa-btn sa-btn-primary">Broadcast Message</button>
                            </div>
                        </form>
                    </div>

                    <div class="sa-panel p-6 space-y-4">
                        <div class="border-b border-gray-100 pb-3">
                            <h3 class="text-sm font-bold text-gray-900">Platform Health & Backups</h3>
                            <p class="text-xs text-gray-400">Master database & security diagnostics</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-800">Database Master Backups</p>
                                <p class="text-[11px] text-gray-400">Manage database snapshots</p>
                            </div>
                            <a href="{{ route('database-backup.index') }}" class="sa-btn sa-btn-primary" style="font-size: 11px; padding: 6px 12px;">
                                Manage Backups
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
