@extends('layouts.app')

@section('title', 'Subscription Detail — Super Admin')

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
    .sa-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 8px; font-size: 11px; font-weight: 700; }
    .sa-badge.active { background: rgba(39,174,96,0.08); color: #27AE60; }
    .sa-badge.expired { background: rgba(231,76,60,0.08); color: #E74C3C; }
    .sa-badge.cancelled { background: rgba(149,165,166,0.08); color: #95A5A6; }
    .sa-badge.starter { background: rgba(52,152,219,0.06); color: #3498DB; }
    .sa-badge.standard { background: rgba(142,68,173,0.06); color: #8E44AD; }
    .sa-badge.premium { background: rgba(212,168,67,0.08); color: #B8912E; }
    .sa-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; transition: all 0.2s; cursor: pointer; border: none; }
    .sa-btn-primary { background: #1B6B3E; color: #fff; }
    .sa-btn-primary:hover { background: #0F4D2A; }
    .form-input { width: 100%; padding: 10px 14px; background: #fafbfc; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 13px; color: #374151; outline: none; transition: border-color 0.2s; }
    .form-input:focus { border-color: #1B6B3E; background: #fff; }
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
                    <div class="flex items-center gap-2">
                        <a href="{{ route('super-admin.subscriptions') }}" class="text-xs text-gray-400 hover:text-gray-600">Subscriptions</a>
                        <span class="text-xs text-gray-300">/</span>
                        <h1 class="text-lg font-bold text-gray-900">{{ $subscription->school->name ?? 'School Subscription' }}</h1>
                    </div>
                </div>
            </div>
            <a href="{{ route('super-admin.subscriptions') }}" class="text-xs font-bold text-gray-500 hover:text-gray-700">← Back to List</a>
        </div>

        <div class="p-6 md:p-8 space-y-6">
            @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-semibold text-sm">{{ session('success') }}</div>
            @endif

            <div class="grid lg:grid-cols-3 gap-6">
                {{-- Subscription Details & Edit Form --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="sa-panel p-6 space-y-6">
                        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 pb-4">
                            <div>
                                <span class="sa-badge {{ strtolower($subscription->plan) }} text-xs px-3 py-1">{{ $subscription->plan }} Plan</span>
                                <h2 class="text-xl font-black text-gray-900 mt-2">{{ $subscription->school->name ?? 'N/A' }}</h2>
                                <p class="text-xs text-gray-400">{{ $subscription->school->email ?? '' }} · Code: {{ $subscription->school->school_code ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="sa-badge {{ $subscription->status }}">{{ ucfirst($subscription->status) }}</span>
                                <p class="text-xl font-extrabold mt-2" style="color:#1B6B3E;">₦{{ number_format($subscription->price, 2) }}</p>
                                <p class="text-[11px] text-gray-400 capitalize">{{ $subscription->billing_cycle }} cycle</p>
                            </div>
                        </div>

                        {{-- Update Subscription Form --}}
                        <form action="{{ route('super-admin.subscriptions.update', $subscription->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <h3 class="text-sm font-bold text-gray-900">Modify Subscription Parameters</h3>

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Plan Tier</label>
                                    <select name="plan" class="form-input">
                                        <option value="Starter" {{ $subscription->plan === 'Starter' ? 'selected' : '' }}>Starter</option>
                                        <option value="Standard" {{ $subscription->plan === 'Standard' ? 'selected' : '' }}>Standard</option>
                                        <option value="Premium" {{ $subscription->plan === 'Premium' ? 'selected' : '' }}>Premium</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                                    <select name="status" class="form-input">
                                        <option value="active" {{ $subscription->status === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="expired" {{ $subscription->status === 'expired' ? 'selected' : '' }}>Expired</option>
                                        <option value="cancelled" {{ $subscription->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Price (₦)</label>
                                    <input type="number" name="price" step="0.01" value="{{ $subscription->price }}" class="form-input">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Expiration Date (Ends At)</label>
                                    <input type="date" name="ends_at" value="{{ $subscription->ends_at ? $subscription->ends_at->format('Y-m-d') : '' }}" class="form-input">
                                </div>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" class="sa-btn sa-btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>

                    {{-- Payment History for this subscription --}}
                    <div class="sa-panel">
                        <div class="sa-panel-header">
                            <h3 class="text-sm font-bold text-gray-900">Subscription Payment History</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="sa-table">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Gateway</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Paid Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subscription->payments as $p)
                                    <tr>
                                        <td class="font-mono text-xs font-bold text-gray-800">{{ $p->payment_reference }}</td>
                                        <td class="text-xs text-gray-500 capitalize">{{ $p->gateway ?? 'N/A' }}</td>
                                        <td class="font-bold" style="color:#1B6B3E;">₦{{ number_format($p->amount, 2) }}</td>
                                        <td><span class="sa-badge {{ $p->status === 'paid' ? 'active' : 'expired' }}">{{ ucfirst($p->status) }}</span></td>
                                        <td class="text-xs text-gray-400">{{ $p->paid_at ? $p->paid_at->format('M d, Y') : ($p->created_at ? $p->created_at->format('M d, Y') : 'N/A') }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center py-6 text-gray-400">No subscription payments recorded for this school yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- School Snapshot Sidebar --}}
                <div class="space-y-6">
                    <div class="sa-panel p-6 space-y-4">
                        <h3 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3">School Snapshot</h3>
                        <div>
                            <p class="text-[11px] text-gray-400 font-bold uppercase">School Code</p>
                            <p class="text-sm font-mono font-bold text-gray-800">{{ $subscription->school->school_code ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 font-bold uppercase">Location</p>
                            <p class="text-xs font-semibold text-gray-700">{{ $subscription->school->city ?? 'N/A' }}, {{ $subscription->school->country ?? 'Nigeria' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 font-bold uppercase">Phone</p>
                            <p class="text-xs font-semibold text-gray-700">{{ $subscription->school->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 font-bold uppercase">Registered Date</p>
                            <p class="text-xs font-semibold text-gray-700">{{ $subscription->school->created_at ? $subscription->school->created_at->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-100">
                            <form action="{{ route('super-admin.schools.login-as', $subscription->school_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full sa-btn" style="background: rgba(212,168,67,0.12); color: #B8912E; justify-content: center;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                    Login as School Admin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
