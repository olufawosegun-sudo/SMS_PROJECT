@extends('layouts.app')

@section('title', 'Global Activity Feed — Super Admin')

@push('styles')
<style>
    .sa-body { background: #f0f2f5; min-height: 100vh; }
    .sa-main { margin-left: 280px; padding: 0; }
    @media (max-width: 1023px) { .sa-main { margin-left: 0; } }
    .sa-topbar { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(0,0,0,0.04); padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 20; }
    .sa-panel { background: #fff; border-radius: 20px; border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02); overflow: hidden; }
    .activity-row { padding: 16px 24px; border-bottom: 1px solid #f8f9fa; display: flex; flex-direction: column; gap: 4px; transition: background 0.15s; }
    @media (min-width: 768px) { .activity-row { flex-direction: row; align-items: center; justify-content: space-between; } }
    .activity-row:hover { background: #f8fafc; }
    .activity-row:last-child { border-bottom: none; }
</style>
@endpush

@section('body')
<div class="sa-body">
    @include('super-admin.partials._sidebar', ['activePage' => 'activities'])

    <div class="sa-main">
        <div class="sa-topbar">
            <div class="flex items-center gap-3">
                <button onclick="toggleSASidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-ping"></span>
                        Live Global Activity Feed
                    </h1>
                    <p class="text-xs text-gray-400">Real-time audit log stream across all institutions</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            <div class="sa-panel">
                <div class="divide-y divide-gray-50">
                    @forelse($activities as $act)
                    <div class="activity-row">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-gray-900 text-sm">{{ $act->user->name ?? 'System User' }}</span>
                                <span class="text-xs text-gray-400">({{ $act->user->email ?? 'N/A' }})</span>
                            </div>
                            <p class="text-xs text-gray-700 font-medium">{{ $act->action ?? $act->description ?? 'Performed system operation' }}</p>
                            @if($act->ip_address)
                            <span class="text-[10px] font-mono text-gray-400">IP: {{ $act->ip_address }}</span>
                            @endif
                        </div>

                        <div class="text-xs text-gray-400 flex flex-col md:items-end">
                            <span class="font-bold text-gray-700">{{ $act->created_at ? $act->created_at->format('M d, Y') : 'N/A' }}</span>
                            <span>{{ $act->created_at ? $act->created_at->format('h:i A') . ' (' . $act->created_at->diffForHumans() . ')' : '' }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-gray-400 text-sm">No activity logs recorded yet.</div>
                    @endforelse
                </div>

                @if($activities->hasPages())
                <div class="p-4 border-t border-gray-50">
                    {{ $activities->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
