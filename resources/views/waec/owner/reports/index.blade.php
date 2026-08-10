@extends('layouts.app')
@section('title', 'WAEC Reports Dashboard')
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
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">WAEC Reports Dashboard</h1>
                    <p class="text-gray-500">Comprehensive WAEC payment analytics and insights</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('owner.waec.fees.configuration') }}" 
                       class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold transition-colors duration-200 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Fee Configuration
                    </a>
                    <a href="{{ route('owner.waec.reports.export') }}?session_id={{ $sessionId }}" 
                       class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-dark rounded-xl font-semibold transition-colors duration-200 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Data
                    </a>
                </div>
            </div>

            {{-- Session Filter --}}
            <div class="bg-white rounded-2xl p-4 border border-gray-100 mb-6">
                <form method="GET" action="{{ route('owner.waec.reports') }}" class="flex items-center gap-4">
                    <label class="text-sm font-semibold text-gray-600">Filter by Session:</label>
                    <select name="session_id" 
                            onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-200 rounded-xl text-sm">
                        <option value="">All Sessions</option>
                        @foreach($sessions as $session)
                        <option value="{{ $session->id }}" {{ $sessionId == $session->id ? 'selected' : '' }}>
                            {{ $session->name }}
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Financial Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-full bg-success/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-success mb-1">{{ $currencySymbol }}{{ number_format($summary['total_collected'], 2) }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Collected</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-full bg-warning/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-warning mb-1">{{ $currencySymbol }}{{ number_format($summary['pending_amount'], 2) }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Pending Approval</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-primary mb-1">{{ $summary['approved_count'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Approved Payments</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 rounded-full bg-accent/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-accent-dark mb-1">{{ $summary['total_candidates'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Candidates</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                {{-- Payments by Session Chart --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-dark mb-4">Payments by Session</h3>
                    @if($paymentsBySession->isEmpty())
                    <p class="text-gray-500 text-center py-8">No payment data available</p>
                    @else
                    <div class="space-y-3">
                        @foreach($paymentsBySession as $item)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-semibold text-dark">{{ $item->session_name }}</span>
                                <span class="text-sm font-bold text-success">{{ $currencySymbol }}{{ number_format($item->total, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-success h-2 rounded-full" style="width: {{ $paymentsBySession->max('total') > 0 ? ($item->total / $paymentsBySession->max('total') * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Payments by Class --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-dark mb-4">Payments by Class</h3>
                    @if($paymentsByClass->isEmpty())
                    <p class="text-gray-500 text-center py-8">No class data available</p>
                    @else
                    <div class="space-y-3">
                        @foreach($paymentsByClass->take(5) as $item)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-semibold text-dark">{{ $item->class_name }}</span>
                                <span class="text-sm font-bold text-primary">{{ $currencySymbol }}{{ number_format($item->total, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full" style="width: {{ $paymentsByClass->max('total') > 0 ? ($item->total / $paymentsByClass->max('total') * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Payment Methods Distribution --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-dark mb-4">Payment Methods</h3>
                    @if($paymentsByMethod->isEmpty())
                    <p class="text-gray-500 text-center py-8">No payment method data</p>
                    @else
                    <div class="space-y-4">
                        @foreach($paymentsByMethod as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-dark">{{ ucfirst(str_replace('_', ' ', $item->payment_method)) }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->count }} payment(s)</p>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-success">{{ $currencySymbol }}{{ number_format($item->total, 2) }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Recent Activities --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-dark mb-4">Recent Activities</h3>
                    @if($recentActivities->isEmpty())
                    <p class="text-gray-500 text-center py-8">No recent activities</p>
                    @else
                    <div class="space-y-3">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="w-10 h-10 rounded-full {{ $activity->action === 'approved' ? 'bg-success/10 text-success' : ($activity->action === 'rejected' ? 'bg-danger/10 text-danger' : 'bg-blue-100 text-blue-700') }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($activity->action === 'approved')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    @elseif($activity->action === 'rejected')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    @endif
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-dark">{{ ucfirst($activity->action) }}: {{ $activity->student_name }}</p>
                                <p class="text-xs text-gray-500">{{ $currencySymbol }}{{ number_format($activity->amount, 2) }} • {{ $activity->user_name }} • {{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
