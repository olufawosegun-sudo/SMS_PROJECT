@extends('layouts.app')
@section('title', 'Report Cards')

@push('styles')
<style>
    .report-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .report-card-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('body')
<div class="flex min-h-screen bg-surface">
    @if(Auth::user()->role->name === 'Teacher')
        @include('partials.teacher_sidebar')
    @else
        @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name)])
    @endif
    
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">📊 Report Cards</h1>
                    <p class="text-gray-500">
                        @if(in_array($userRole, ['Principal', 'Owner']))
                            Review and manage student terminal reports
                        @else
                            Generate and manage student terminal reports
                        @endif
                    </p>
                </div>
                @if($userRole === 'Teacher')
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('generateModal').classList.remove('hidden')" 
                        class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Generate Report Card
                    </button>
                </div>
                @endif
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('info') }}
            </div>
            @endif

            {{-- Owner Monitoring Dashboard Banner --}}
            @if($userRole === 'Owner')
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg mb-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold mb-2">👑 Owner's Monitoring Dashboard</h2>
                        <p class="text-sm text-purple-100 mb-3">
                            As the school owner, you have full visibility into all report card activities. 
                            Monitor teacher performance, principal approvals, and overall system usage.
                        </p>
                        <div class="grid grid-cols-3 gap-3 mt-4">
                            <div class="bg-white/10 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold">{{ $reportCards->count() }}</p>
                                <p class="text-xs text-purple-200 mt-1">Total Reports</p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold">{{ $draftCount }}</p>
                                <p class="text-xs text-purple-200 mt-1">Awaiting Approval</p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold">{{ $publishedCount }}</p>
                                <p class="text-xs text-purple-200 mt-1">Published</p>
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-purple-800/30 rounded-lg border border-purple-500/30">
                            <p class="text-xs text-purple-100 flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span><strong>Your Role:</strong> Monitor staff activities, review teacher comments, principal approvals, and ensure quality standards are maintained. You cannot create or edit reports - this maintains proper accountability.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Principal Review Banner --}}
            @if($userRole === 'Principal')
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow-lg mb-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold mb-2">👔 Principal's Review Center</h2>
                        <p class="text-sm text-blue-100 mb-3">
                            Review draft report cards submitted by teachers, add your official comments, and approve for publication.
                        </p>
                        @if($draftCount > 0)
                        <div class="bg-yellow-400 text-yellow-900 rounded-lg p-3 flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold">{{ $draftCount }} report card(s) awaiting your approval!</span>
                        </div>
                        @else
                        <div class="bg-green-400/20 border border-green-400/30 text-green-100 rounded-lg p-3 flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold">All reports reviewed! No pending approvals.</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Filters --}}
            {{-- Status Filter Bar (NOT for Students/Guardians) --}}
            @if(!in_array($userRole, ['Student', 'Guardian']))
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 mb-8 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h3 class="text-white font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter Reports by Status
                    </h3>
                    <p class="text-blue-100 text-xs mt-1">View all reports or filter by approval status</p>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('report-cards.index') }}" 
                            class="group relative flex-1 min-w-[180px] px-5 py-4 rounded-xl font-semibold transition-all duration-300 {{ !$selectedStatus ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg scale-105' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:shadow-md' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg {{ !$selectedStatus ? 'bg-white/20' : 'bg-blue-100' }} flex items-center justify-center">
                                        <svg class="w-5 h-5 {{ !$selectedStatus ? 'text-white' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold">All Reports</p>
                                        <p class="text-xs opacity-75">Complete overview</p>
                                    </div>
                                </div>
                                <span class="text-2xl font-bold">{{ $draftCount + $approvedCount + $publishedCount }}</span>
                            </div>
                        </a>
                        
                        <a href="{{ route('report-cards.index', ['status' => 'draft']) }}" 
                            class="group relative flex-1 min-w-[180px] px-5 py-4 rounded-xl font-semibold transition-all duration-300 {{ $selectedStatus === 'draft' ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg scale-105' : 'bg-amber-50 text-amber-700 hover:bg-amber-100 hover:shadow-md' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg {{ $selectedStatus === 'draft' ? 'bg-white/20' : 'bg-amber-100' }} flex items-center justify-center">
                                        <svg class="w-5 h-5 {{ $selectedStatus === 'draft' ? 'text-white' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold">{{ in_array($userRole, ['Principal', 'Owner']) ? 'Pending Approval' : 'Draft' }}</p>
                                        <p class="text-xs opacity-75">{{ in_array($userRole, ['Principal', 'Owner']) ? 'Requires review' : 'Not submitted yet' }}</p>
                                    </div>
                                </div>
                                <span class="text-2xl font-bold">{{ $draftCount }}</span>
                            </div>
                            @if($draftCount > 0 && $selectedStatus !== 'draft')
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold animate-pulse">
                                !
                            </div>
                            @endif
                        </a>
                        
                        <a href="{{ route('report-cards.index', ['status' => 'approved']) }}" 
                            class="group relative flex-1 min-w-[180px] px-5 py-4 rounded-xl font-semibold transition-all duration-300 {{ $selectedStatus === 'approved' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg scale-105' : 'bg-blue-50 text-blue-700 hover:bg-blue-100 hover:shadow-md' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg {{ $selectedStatus === 'approved' ? 'bg-white/20' : 'bg-blue-100' }} flex items-center justify-center">
                                        <svg class="w-5 h-5 {{ $selectedStatus === 'approved' ? 'text-white' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold">Approved</p>
                                        <p class="text-xs opacity-75">Ready to publish</p>
                                    </div>
                                </div>
                                <span class="text-2xl font-bold">{{ $approvedCount }}</span>
                            </div>
                            @if($approvedCount > 0 && $selectedStatus !== 'approved' && !in_array($userRole, ['Principal', 'Owner']))
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold animate-pulse">
                                !
                            </div>
                            @endif
                        </a>
                        
                        <a href="{{ route('report-cards.index', ['status' => 'published']) }}" 
                            class="group relative flex-1 min-w-[180px] px-5 py-4 rounded-xl font-semibold transition-all duration-300 {{ $selectedStatus === 'published' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-lg scale-105' : 'bg-green-50 text-green-700 hover:bg-green-100 hover:shadow-md' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg {{ $selectedStatus === 'published' ? 'bg-white/20' : 'bg-green-100' }} flex items-center justify-center">
                                        <svg class="w-5 h-5 {{ $selectedStatus === 'published' ? 'text-white' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold">Published</p>
                                        <p class="text-xs opacity-75">Approved & Live</p>
                                    </div>
                                </div>
                                <span class="text-2xl font-bold">{{ $publishedCount }}</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            
            {{-- Additional Class/Session/Term Filters for Teachers ONLY --}}
            @if($userRole === 'Teacher')
            {{-- Full Filters with Class/Session/Term for Teachers --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8 shadow-sm">
                <form method="GET" action="{{ route('report-cards.index') }}" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Class</label>
                        <select name="class_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Session</label>
                        <select name="session_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ $selectedSessionId == $session->id ? 'selected' : '' }}>
                                {{ $session->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Term</label>
                        <select name="term_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach($terms as $term)
                            <option value="{{ $term->id }}" {{ $selectedTermId == $term->id ? 'selected' : '' }}>
                                {{ $term->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                    </button>
                </form>
            </div>
            @endif

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">{{ $reportCards->count() }}</h3>
                    <p class="text-blue-100 text-sm">Total Report Cards</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">{{ $reportCards->where('status', 'published')->count() }}</h3>
                    <p class="text-green-100 text-sm">Published Reports</p>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-bold mb-1">{{ $studentsWithoutReports->count() }}</h3>
                    <p class="text-amber-100 text-sm">Pending Reports</p>
                </div>
            </div>

            {{-- Principal Instructions when viewing Pending Approval --}}
            @if(in_array($userRole, ['Principal', 'Owner']) && $selectedStatus === 'draft' && $draftCount > 0)
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-300 rounded-2xl p-8 mb-8 shadow-xl">
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <h2 class="text-2xl font-bold text-amber-900">📋 {{ $draftCount }} Report Card{{ $draftCount > 1 ? 's' : '' }} Awaiting Your Approval</h2>
                            <span class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-bold animate-pulse">ACTION REQUIRED</span>
                        </div>
                        <p class="text-amber-800 mb-6 text-base">
                            As the Principal, you must review and approve these draft reports before they can be published to students and parents. 
                            Each report below requires your professional comment and final approval.
                        </p>
                        <div class="bg-white rounded-xl p-5 border-2 border-amber-200 shadow-sm">
                            <p class="text-sm font-bold text-amber-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                HOW TO APPROVE REPORTS:
                            </p>
                            <ol class="text-sm text-amber-800 space-y-2 ml-6 list-decimal">
                                <li><strong>Click "Review & Approve"</strong> button on any report card below</li>
                                <li><strong>Review Performance:</strong> Check student's average, position, attendance, and subject scores</li>
                                <li><strong>Read Teacher's Comment:</strong> Review the class teacher's professional remarks (read-only)</li>
                                <li><strong>Add Your Comment:</strong> Write your Principal's comment in the editable field provided</li>
                                <li><strong>Change Status:</strong> Select "Published" radio button (it will be on "Draft" by default)</li>
                                <li><strong>Click "Approve & Publish Report"</strong> button to finalize and make report available</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Report Cards Grid --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-lg">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-5 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                @if($selectedStatus === 'draft')
                                    Draft Report Cards Pending Approval
                                @elseif($selectedStatus === 'published')
                                    Published Report Cards
                                @else
                                    All Report Cards
                                @endif
                            </h2>
                            <p class="text-sm text-gray-300 mt-1">
                                @if(in_array($userRole, ['Principal', 'Owner']))
                                    {{ $selectedStatus === 'draft' ? 'Review and approve draft reports below' : 'Manage and monitor student report cards' }}
                                @else
                                    Generate and manage student terminal reports
                                @endif
                            </p>
                        </div>
                        <div class="bg-white/10 rounded-xl px-4 py-2">
                            <p class="text-3xl font-bold text-white">{{ $reportCards->count() }}</p>
                            <p class="text-xs text-gray-300">{{ $reportCards->count() === 1 ? 'Report' : 'Reports' }}</p>
                        </div>
                    </div>
                </div>

                @if($reportCards->count() > 0)
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($reportCards as $reportCard)
                        <div class="bg-white border-2 {{ $reportCard->status === 'draft' ? 'border-amber-400 bg-amber-50' : 'border-gray-200' }} rounded-xl p-6 shadow-lg hover:shadow-xl transition-all">
                            {{-- URGENT BADGE for Drafts --}}
                            @if($reportCard->status === 'draft' && in_array($userRole, ['Principal', 'Owner']))
                            <div class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full inline-block mb-3 animate-pulse">
                                ⚠️ ACTION REQUIRED
                            </div>
                            @endif
                            
                            {{-- Student Info --}}
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-900">
                                    {{ $reportCard->student->user->first_name }} {{ $reportCard->student->user->last_name }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ $reportCard->schoolClass->name }}</p>
                                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold {{ 
                                    $reportCard->status === 'draft' ? 'bg-amber-200 text-amber-900' : 
                                    ($reportCard->status === 'approved' ? 'bg-blue-200 text-blue-900' : 
                                    'bg-green-200 text-green-900') 
                                }}">
                                    {{ strtoupper($reportCard->status) }}
                                </span>
                            </div>
                            
                            {{-- Performance Stats --}}
                            <div class="grid grid-cols-3 gap-2 mb-4 bg-white rounded-lg p-3 border border-gray-200">
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-blue-600">{{ number_format($reportCard->average ?? 0, 1) }}%</p>
                                    <p class="text-xs text-gray-500">Average</p>
                                </div>
                                <div class="text-center border-x">
                                    <p class="text-xl font-bold text-amber-600">{{ $reportCard->overall_position ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Position</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-bold text-gray-700">{{ $reportCard->attendance ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">Attendance</p>
                                </div>
                            </div>
                            
                            {{-- Session & Term --}}
                            <div class="text-xs text-gray-600 mb-4 space-y-1">
                                <div>📅 {{ $reportCard->session->name ?? 'N/A' }} - {{ $reportCard->term->name ?? 'N/A' }}</div>
                                <div>👤 By: {{ $reportCard->generatedBy->first_name ?? 'System' }}</div>
                            </div>
                            
                            {{-- ACTION BUTTONS --}}
                            @if($reportCard->status === 'draft' && $userRole === 'Principal')
                                {{-- PRINCIPAL: Review & Approve Draft --}}
                                <a href="{{ route('report-cards.edit', $reportCard->id) }}" 
                                    class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-center font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                    <div class="flex items-center justify-center gap-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-lg">Review & Approve</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </a>
                            @elseif($reportCard->status === 'approved' && !in_array($userRole, ['Principal', 'Owner']))
                                {{-- TEACHER: Publish Approved Report --}}
                                <form method="POST" action="{{ route('report-cards.publish', $reportCard->id) }}" class="mb-2">
                                    @csrf
                                    <button type="submit" 
                                        class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-center font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all flex items-center justify-center gap-3"
                                        onclick="return confirm('Publish this report card? Students and guardians will be able to view it.');">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-lg">✅ Publish Report</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </form>
                                <a href="{{ route('report-cards.show', $reportCard->id) }}" 
                                    class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 text-center font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                                    Preview Before Publishing
                                </a>
                            @elseif($userRole === 'Principal')
                                {{-- PRINCIPAL: View Approved/Published --}}
                                <a href="{{ route('report-cards.show', $reportCard->id) }}" 
                                    class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 text-center font-semibold py-3 px-4 rounded-lg transition-colors">
                                    View Report
                                </a>
                            @elseif($userRole === 'Owner')
                                {{-- OWNER: Monitor Only --}}
                                <a href="{{ route('report-cards.show', $reportCard->id) }}" 
                                    class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center font-semibold py-3 px-4 rounded-lg transition-colors">
                                    Monitor Report
                                </a>
                            @elseif(in_array($userRole, ['Student', 'Guardian']))
                                 {{-- STUDENT / GUARDIAN: View & Print Only (HIGHLY VISIBLE) --}}
                                 <a href="{{ route('report-cards.show', $reportCard->id) }}" 
                                     style="display:flex; align-items:center; justify-content:center; gap:10px; width:100%; padding:16px 20px; background:linear-gradient(135deg, #059669, #047857); color:#ffffff; font-weight:800; font-size:16px; text-decoration:none; border-radius:12px; box-shadow:0 4px 15px rgba(5,150,105,0.4); transition:all 0.2s; text-align:center; letter-spacing:0.5px; border:2px solid #10b981;">
                                     <svg style="width:24px; height:24px; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                     </svg>
                                     👁️ VIEW & PRINT REPORT CARD
                                     <svg style="width:20px; height:20px; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                     </svg>
                                 </a>
                            @else
                                {{-- TEACHER: Normal Buttons --}}
                                <div class="flex gap-2">
                                    <a href="{{ route('report-cards.show', $reportCard->id) }}" 
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-2 px-4 rounded-lg transition-colors">
                                        View
                                    </a>
                                    @if($reportCard->status === 'draft')
                                    <a href="{{ route('report-cards.edit', $reportCard->id) }}" 
                                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="p-12 text-center text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-500 mb-2">No Report Cards Found</h3>
                    <p class="text-sm text-gray-400 mb-6">
                        @if($userRole === 'Student')
                            No published report cards available yet. Your teacher will create and publish your report card once all assessments are completed.
                        @elseif(in_array($userRole, ['Principal', 'Owner']))
                            No report cards available for review. Teachers will create report cards for students.
                        @else
                            Generate your first report card to get started
                        @endif
                    </p>
                    @if($userRole === 'Teacher')
                    <button type="button" onclick="document.getElementById('generateModal').classList.remove('hidden')" 
                        class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Generate Report Card
                    </button>
                    @endif
                </div>
                @endif
            </div>

            {{-- Students Without Reports - COLLATION CENTER (Teachers Only) --}}
            @if($userRole === 'Teacher' && $selectedClassId)
                @if($studentsWithoutReports->count() > 0)
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border-2 border-amber-300 overflow-hidden shadow-lg mt-8">
                    <div class="p-6 border-b-2 border-amber-300 bg-gradient-to-r from-amber-100 to-orange-100">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-amber-900 flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    📊 Collation Center - Students Pending Report Cards
                                </h2>
                                <p class="text-sm text-amber-700 mt-2">
                                    <strong>{{ $studentsWithoutReports->count() }}</strong> student(s) need report cards for 
                                    <strong>{{ $sessions->find($selectedSessionId)->name ?? 'this session' }}</strong> - 
                                    <strong>{{ $terms->find($selectedTermId)->name ?? 'this term' }}</strong>
                                </p>
                            </div>
                            <div class="bg-amber-500 text-white rounded-full px-4 py-2 text-sm font-bold">
                                {{ $studentsWithoutReports->count() }} Pending
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-white/60 rounded-lg border border-amber-200">
                            <p class="text-xs text-amber-800 flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span><strong>How it works:</strong> Click "Collate & Generate" to automatically pull all recorded subject scores, calculate averages, and create the report card. You only need to add your professional comments.</span>
                            </p>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($studentsWithoutReports as $student)
                            <div class="bg-white rounded-xl p-5 border-2 border-gray-200 hover:border-primary hover:shadow-xl transition-all duration-300 group">
                                <div class="flex items-start gap-4">
                                    {{-- Student Avatar --}}
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-bold text-lg flex-shrink-0 group-hover:scale-110 transition-transform">
                                        {{ substr($student->user->first_name, 0, 1) }}{{ substr($student->user->last_name, 0, 1) }}
                                    </div>
                                    
                                    {{-- Student Info --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-dark text-lg truncate">
                                            {{ $student->user->first_name }} {{ $student->user->last_name }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            {{ $student->schoolClass->name }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            🆔 {{ $student->admission_number ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                
                                {{-- Action Button --}}
                                <a href="{{ route('report-cards.create', ['student_id' => $student->id]) }}" 
                                    class="mt-4 w-full px-4 py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg hover:shadow-lg transition-all text-sm font-bold flex items-center justify-center gap-2 group-hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    Collate & Generate
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-green-50 rounded-2xl border border-green-200 overflow-hidden shadow-sm mt-8">
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-green-900 mb-2">All Students Have Reports!</h3>
                        <p class="text-sm text-green-700">Every student in this class already has a report card for {{ $sessions->find($selectedSessionId)->name ?? 'this session' }} - {{ $terms->find($selectedTermId)->name ?? 'this term' }}</p>
                        <p class="text-xs text-green-600 mt-3">You can view and edit existing reports above, or select a different session/term to generate new reports.</p>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </main>
</div>

{{-- Generate Report Card Modal --}}
<div id="generateModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-dark">Generate Report Card</h3>
            <button type="button" onclick="document.getElementById('generateModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <p class="text-gray-600 mb-6">First, select a class to see students who need report cards.</p>
        <form method="GET" action="{{ route('report-cards.index') }}">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select Class</label>
                <select name="class_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">Choose a class...</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('generateModal').classList.add('hidden')" 
                    class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-sm">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">
                    Continue
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
