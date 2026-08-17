@extends('layouts.app')

@section('title', 'Owner Dashboard — ' . ($school->name ?? 'EduWest Africa'))

@section('body')
<div class="flex min-h-screen bg-surface overflow-x-hidden">
    {{-- ======================================== SIDEBAR ======================================== --}}
    @include('partials.sidebar', ['role' => 'owner'])

    {{-- ======================================== MAIN CONTENT ======================================== --}}
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        {{-- Top Bar --}}
        @include('partials.topbar')

        {{-- Page Content --}}
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Welcome Banner --}}
            <div class="bg-gradient-to-r from-primary to-primary-dark rounded-2xl p-5 md:p-8 mb-6 md:mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
                <div class="absolute bottom-0 right-20 w-32 h-32 bg-accent/10 rounded-full translate-y-1/2"></div>
                <div class="relative z-10 text-left">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-white mb-2">Welcome back, {{ $user->first_name }}!</h1>
                            <p class="text-white/80 text-lg mb-1">{{ $school->name ?? 'West African Excellence Academy' }}</p>
                            <p class="text-white/60 italic text-sm">"{{ $school->motto ?? 'Knowledge, Character, and Excellence' }}"</p>
                        </div>
                        <div class="hidden lg:flex items-center gap-3">
                            <button type="button" onclick="openDashboardSessionModal()" class="group bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20 text-left transition-all cursor-pointer">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs text-white/60 mb-0.5">Current Session</p>
                                    <span class="text-[10px] text-accent font-bold group-hover:underline flex items-center gap-0.5">
                                        Change
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </span>
                                </div>
                                <p class="text-lg font-bold text-white">{{ $currentSession->name ?? date('Y').'/'.(date('Y')+1) }}</p>
                            </button>
                            <div class="bg-white/10 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20">
                                <p class="text-xs text-white/60 mb-1">Current Term</p>
                                <p class="text-lg font-bold text-white">{{ $currentTerm->name ?? 'First Term' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-white/90">
                        <span class="px-3 py-2 bg-white/20 backdrop-blur-sm rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $school->country ?? 'Nigeria' }}
                        </span>
                        <span class="px-3 py-2 bg-accent text-dark rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Owner Access
                        </span>
                    </div>

                    {{-- Portal Links — stacked inside the welcome banner --}}
                    <div class="flex flex-col gap-2 mt-5 pt-4 border-t border-white/15">
                        {{-- School Website --}}
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-xl px-3 py-2.5 border border-white/15 min-w-0 overflow-hidden">
                            <svg class="w-4 h-4 text-white/70 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg>
                            <div class="min-w-0 flex-1 overflow-hidden">
                                <p class="text-[10px] text-white/50 font-semibold uppercase tracking-wider leading-none mb-0.5">School Website</p>
                                <p class="text-xs text-white font-mono truncate">{{ $school->public_url }}</p>
                            </div>
                            <button type="button" id="copyWebsiteBtn" onclick="copyPortalLink('{{ $school->public_url }}', 'copyWebsiteBtn')" class="flex-shrink-0 px-2.5 py-1.5 bg-white/15 hover:bg-white/25 rounded-lg text-[11px] font-bold text-white transition-all active:scale-95 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Copy</span>
                            </button>
                            <a href="{{ $school->public_url }}" target="_blank" class="flex-shrink-0 px-2.5 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-[11px] font-bold text-white transition-all active:scale-95">
                                Visit ↗
                            </a>
                        </div>

                        {{-- Careers Portal --}}
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-xl px-3 py-2.5 border border-white/15 min-w-0 overflow-hidden">
                            <svg class="w-4 h-4 text-white/70 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75"/></svg>
                            <div class="min-w-0 flex-1 overflow-hidden">
                                <p class="text-[10px] text-white/50 font-semibold uppercase tracking-wider leading-none mb-0.5">Careers Portal</p>
                                <p class="text-xs text-white font-mono truncate">{{ $school->careers_url }}</p>
                            </div>
                            <button type="button" id="copyCareersBtn" onclick="copyPortalLink('{{ $school->careers_url }}', 'copyCareersBtn')" class="flex-shrink-0 px-2.5 py-1.5 bg-white/15 hover:bg-white/25 rounded-lg text-[11px] font-bold text-white transition-all active:scale-95 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Copy</span>
                            </button>
                            <a href="{{ $school->careers_url }}" target="_blank" class="flex-shrink-0 px-2.5 py-1.5 bg-white/20 hover:bg-white/30 rounded-lg text-[11px] font-bold text-white transition-all active:scale-95">
                                Visit ↗
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Copy-to-clipboard micro-interaction --}}
            <script>
            function copyPortalLink(url, btnId) {
                navigator.clipboard.writeText(url).then(() => {
                    const btn = document.getElementById(btnId);
                    const original = btn.innerHTML;
                    btn.innerHTML = `<svg class="w-3 h-3 text-emerald-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg><span class="text-emerald-300">Copied!</span>`;
                    setTimeout(() => { btn.innerHTML = original; }, 2000);
                });
            }
            </script>

            {{-- Quick Action Buttons --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6 md:mb-8">
                @foreach([
                    ['label' => 'Add Student', 'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', 'color' => 'primary', 'route' => 'students.create'],
                    ['label' => 'Add Teacher', 'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', 'color' => 'info', 'route' => 'teachers.create'],
                    ['label' => 'Add Parent', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'warning', 'route' => 'guardians.create'],
                    ['label' => 'Create Invoice', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'success', 'route' => 'invoices.create'],
                    ['label' => 'WAEC Remittance', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'accent', 'route' => 'owner.waec.remittance.index'],
                    ['label' => 'Financial Reports', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'color' => 'info', 'route' => 'financial-reports.index'],
                ] as $action)
                <a href="{{ $action['route'] !== '#' ? route($action['route']) : '#' }}" class="bg-white rounded-xl p-4 border border-gray-100 hover:border-{{ $action['color'] }}/30 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group text-center block">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-xl bg-{{ $action['color'] }}/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-{{ $action['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-700 group-hover:text-{{ $action['color'] }} transition-colors">{{ $action['label'] }}</p>
                </a>
                @endforeach
            </div>

            {{-- Main Statistics Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-6 mb-6 md:mb-8">
                @foreach([
                    ['label' => 'Total Students', 'value' => $stats['total_students'], 'change' => 'Enrolled', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'primary'],
                    ['label' => 'Total Teachers', 'value' => $stats['total_teachers'], 'change' => 'Instructors', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'info'],
                    ['label' => 'Total Guardians', 'value' => $stats['total_guardians'], 'change' => 'Parents', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'warning'],
                    ['label' => 'Total Classes', 'value' => $stats['total_classes'], 'change' => 'Active', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'accent'],
                    ['label' => 'Total Subjects', 'value' => $stats['total_subjects'], 'change' => 'Curriculum', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color' => 'success'],
                    ['label' => 'Departments', 'value' => $stats['total_departments'], 'change' => 'Units', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'color' => 'danger'],
                ] as $card)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-{{ $card['color'] }}/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-{{ $card['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-{{ $card['color'] }} bg-{{ $card['color'] }}/10 px-2.5 py-1 rounded-full uppercase">{{ $card['change'] }}</span>
                    </div>
                    <p class="text-3xl font-extrabold text-dark mb-1 text-left">{{ $card['value'] }}</p>
                    <p class="text-sm text-gray-400 text-left">{{ $card['label'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Today's Attendance Card --}}
            <div class="bg-gradient-to-r from-primary to-primary-dark rounded-2xl p-4 md:p-6 mb-6 md:mb-8 text-white">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold mb-2">Today's Attendance</h3>
                        <div class="flex flex-wrap items-center gap-4 md:gap-6">
                            <div>
                                <p class="text-3xl font-extrabold">{{ $stats['today_attendance']['rate'] }}%</p>
                                <p class="text-sm text-white/70">Attendance Rate</p>
                            </div>
                            <div class="h-12 w-px bg-white/20"></div>
                            <div class="flex gap-4 text-sm">
                                <div>
                                    <p class="font-bold">{{ $stats['today_attendance']['present'] }}</p>
                                    <p class="text-white/70">Present</p>
                                </div>
                                <div>
                                    <p class="font-bold">{{ $stats['today_attendance']['late'] }}</p>
                                    <p class="text-white/70">Late</p>
                                </div>
                                <div>
                                    <p class="font-bold">{{ $stats['today_attendance']['absent'] }}</p>
                                    <p class="text-white/70">Absent</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:block w-24 h-24 bg-white/10 rounded-xl flex items-center justify-center">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Charts and Analytics Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-8 mb-6 md:mb-8">
                {{-- Weekly Attendance Chart --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="text-left">
                            <h3 class="text-lg font-bold text-dark">Weekly Attendance Trend</h3>
                            <p class="text-sm text-gray-400">Last 7 days student presence tracking</p>
                        </div>
                        <span class="px-3 py-1.5 text-xs font-semibold bg-primary/10 text-primary rounded-lg">Live Data</span>
                    </div>
                    <div class="flex items-end justify-between gap-3 h-48">
                        @foreach($attendanceData as $index => $value)
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <span class="text-xs font-semibold text-gray-500">{{ $value }}%</span>
                            <div class="w-full rounded-t-lg transition-all duration-500 hover:opacity-85 relative group bg-gradient-to-t from-primary to-primary-light"
                                 style="height: {{ $value }}%;">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-dark text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                    {{ $value }}% present
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">{{ $attendanceLabels[$index] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Student Enrollment Trend --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="text-left">
                            <h3 class="text-lg font-bold text-dark">Student Enrollment Trend</h3>
                            <p class="text-sm text-gray-400">Monthly new admissions (last 6 months)</p>
                        </div>
                    </div>
                    @if(count($enrollmentData) > 0)
                    <div class="flex items-end justify-between gap-3 h-48">
                        @foreach($enrollmentData as $index => $count)
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <span class="text-xs font-semibold text-gray-500">{{ $count }}</span>
                            <div class="w-full rounded-t-lg transition-all duration-500 hover:opacity-85 relative group bg-gradient-to-t from-accent to-accent-light"
                                 style="height: {{ $count > 0 ? min(($count / max($enrollmentData)) * 100, 100) : 10 }}%;">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-dark text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                    {{ $count }} students
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 font-medium">{{ $enrollmentLabels[$index] ?? 'N/A' }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="h-48 flex items-center justify-center text-gray-400">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <p class="text-sm">No enrollment data available yet</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Distribution Cards --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8 mb-6 md:mb-8">
                {{-- Gender Distribution --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-dark mb-6 text-left">Gender Distribution</h3>
                    <div class="space-y-4">
                        @php
                            $totalGender = ($genderStats->male ?? 0) + ($genderStats->female ?? 0);
                            $malePercent = $totalGender > 0 ? round((($genderStats->male ?? 0) / $totalGender) * 100) : 0;
                            $femalePercent = $totalGender > 0 ? round((($genderStats->female ?? 0) / $totalGender) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">Male Students</span>
                                <span class="text-sm font-bold text-info">{{ $genderStats->male ?? 0 }} ({{ $malePercent }}%)</span>
                            </div>
                            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-info rounded-full transition-all duration-500" style="width: {{ $malePercent }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">Female Students</span>
                                <span class="text-sm font-bold text-danger">{{ $genderStats->female ?? 0 }} ({{ $femalePercent }}%)</span>
                            </div>
                            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-danger rounded-full transition-all duration-500" style="width: {{ $femalePercent }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Classes & Class Arms --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 lg:col-span-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <h3 class="text-lg font-bold text-dark text-left">Classes & Class Arms</h3>
                        <div class="w-full sm:w-48">
                            <select id="dashboardClassFilter" onchange="filterDashboardClasses(this.value)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/20 bg-white cursor-pointer transition-all">
                                <option value="all">All Classes</option>
                                <option value="JSS1">JSS1</option>
                                <option value="JSS2">JSS2</option>
                                <option value="JSS3">JSS3</option>
                                <option value="SS1">SS1</option>
                                <option value="SS2">SS2</option>
                                <option value="SS3">SS3</option>
                            </select>
                        </div>
                    </div>
                    @if($classesWithArms->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($classesWithArms as $class)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-primary/30 hover:shadow-md transition-all flex flex-col justify-between dashboard-class-card" data-class-name="{{ $class->name }}">
                            <div class="mb-3 text-left">
                                <span class="text-[10px] font-bold text-accent-dark bg-accent-light/50 px-2 py-0.5 rounded-md uppercase tracking-wider">{{ $class->level }}</span>
                                <h4 class="text-base font-extrabold text-dark mt-1">{{ $class->name }}</h4>
                                <p class="text-xs font-semibold text-primary mt-0.5">{{ $class->students_count }} Students</p>
                            </div>
                            
                            @if($class->arms->count() > 0)
                            <div class="border-t border-gray-200/50 pt-2.5 space-y-1.5 text-left">
                                @foreach($class->arms as $arm)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 font-medium">{{ $arm->name }}</span>
                                    <span class="font-bold text-gray-700 bg-white border border-gray-150 px-2 py-0.5 rounded-md shadow-sm">{{ $arm->students_count }} / {{ $arm->capacity }}</span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-xs text-gray-400 italic text-left pt-2 border-t border-gray-200/50">No arms defined</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-10 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <p class="text-sm">No class data available</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Recent Activities and Announcements --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8 mb-6 md:mb-8">
                {{-- User Activity Monitoring --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="text-left">
                            <h3 class="text-lg font-bold text-dark">User Activity Monitoring</h3>
                            <p class="text-sm text-gray-400">Real-time activity across all user roles</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        @foreach([
                            ['label' => 'Teachers', 'count' => $userActivityBreakdown['teachers']['active_today'], 'total' => $userActivityBreakdown['teachers']['total'], 'activity' => $userActivityBreakdown['teachers']['recent_activity'], 'color' => 'info', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                            ['label' => 'Students', 'count' => $userActivityBreakdown['students']['present_today'], 'total' => $userActivityBreakdown['students']['total'], 'activity' => $userActivityBreakdown['students']['recent_activity'], 'color' => 'success', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                            ['label' => 'Parents', 'count' => $userActivityBreakdown['guardians']['active_this_week'], 'total' => $userActivityBreakdown['guardians']['total'], 'activity' => $userActivityBreakdown['guardians']['recent_activity'], 'color' => 'warning', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['label' => 'Principals', 'count' => $userActivityBreakdown['principals']['active_today'], 'total' => $userActivityBreakdown['principals']['total'], 'activity' => $userActivityBreakdown['principals']['recent_activity'], 'color' => 'primary', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                        ] as $userType)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-{{ $userType['color'] }}/30 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-{{ $userType['color'] }}/10 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-{{ $userType['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $userType['icon'] }}"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">{{ $userType['label'] }}</span>
                                </div>
                                <span class="text-xs font-bold text-{{ $userType['color'] }} bg-{{ $userType['color'] }}/10 px-2 py-1 rounded-full">{{ $userType['count'] }}/{{ $userType['total'] }}</span>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $userType['activity'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Announcements & Quick Stats --}}
                <div class="space-y-6">
                    {{-- Quick Statistics --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-dark mb-4 text-left">Quick Stats</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 rounded-lg bg-warning/5 border border-warning/20">
                                <span class="text-sm font-semibold text-gray-700">Pending Admissions</span>
                                <span class="text-lg font-bold text-warning">{{ $quickStats['pending_admissions'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-info/5 border border-info/20">
                                <span class="text-sm font-semibold text-gray-700">Upcoming Events</span>
                                <span class="text-lg font-bold text-info">{{ $quickStats['upcoming_events'] }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Announcements --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-dark mb-4 text-left">Announcements</h3>
                        <div class="space-y-3">
                            @forelse($announcements as $announcement)
                            <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 hover:border-primary/20 transition-all text-left">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-md">Notice</span>
                                    <span class="text-[10px] text-gray-400 font-semibold">{{ $announcement->announced_at->format('M d') }}</span>
                                </div>
                                <h4 class="text-sm font-bold text-dark mb-1">{{ $announcement->title }}</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ Str::limit($announcement->body, 80) }}</p>
                            </div>
                            @empty
                            <div class="text-center py-6 text-gray-400 text-sm">
                                <span class="text-2xl block mb-2">📢</span>
                                No announcements
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Student Documents Section --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-6 md:mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div class="text-left">
                        <h3 class="text-lg font-bold text-dark">Student Documents</h3>
                        <p class="text-sm text-gray-400">Manage and monitor student documentation</p>
                    </div>
                    <a href="{{ route('students.index') }}" class="text-sm font-semibold text-primary hover:text-primary-dark transition-colors">View All Students</a>
                </div>

                {{-- Document Statistics Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-info/10 to-info/5 rounded-xl p-4 border border-info/20">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-10 h-10 bg-info/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-info bg-info/20 px-2 py-1 rounded-full">Total</span>
                        </div>
                        <p class="text-2xl font-extrabold text-dark mb-1">{{ $documentStats['total_documents'] }}</p>
                        <p class="text-xs text-gray-500">Documents Uploaded</p>
                    </div>

                    <div class="bg-gradient-to-br from-warning/10 to-warning/5 rounded-xl p-4 border border-warning/20">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-10 h-10 bg-warning/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-warning bg-warning/20 px-2 py-1 rounded-full">Alert</span>
                        </div>
                        <p class="text-2xl font-extrabold text-dark mb-1">{{ $documentStats['missing_documents'] }}</p>
                        <p class="text-xs text-gray-500">Students Missing Birth Cert</p>
                    </div>

                    <div class="bg-gradient-to-br from-accent/10 to-accent/5 rounded-xl p-4 border border-accent/20">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-accent bg-accent/20 px-2 py-1 rounded-full">Soon</span>
                        </div>
                        <p class="text-2xl font-extrabold text-dark mb-1">{{ $documentStats['expiring_soon'] }}</p>
                        <p class="text-xs text-gray-500">Expiring in 30 Days</p>
                    </div>

                    <div class="bg-gradient-to-br from-danger/10 to-danger/5 rounded-xl p-4 border border-danger/20">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-10 h-10 bg-danger/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-danger bg-danger/20 px-2 py-1 rounded-full">Expired</span>
                        </div>
                        <p class="text-2xl font-extrabold text-dark mb-1">{{ $documentStats['expired'] }}</p>
                        <p class="text-xs text-gray-500">Expired Documents</p>
                    </div>
                </div>

                {{-- Recent Documents Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Student</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Document Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">File Size</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Uploaded</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentDocuments as $document)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
                                            {{ substr($document->student->user->first_name ?? 'S', 0, 1) }}
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-semibold text-dark">{{ $document->student->user->first_name ?? 'N/A' }} {{ $document->student->user->last_name ?? '' }}</p>
                                            <p class="text-xs text-gray-400">{{ $document->student->admission_no ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-medium text-gray-700">{{ $document->getDocumentTypeLabel() }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm text-gray-600">{{ $document->getFormattedFileSize() }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs text-gray-500">{{ $document->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($document->isExpired())
                                        <span class="text-xs font-bold text-danger bg-danger/10 px-2 py-1 rounded-full">Expired</span>
                                    @elseif($document->isExpiringSoon())
                                        <span class="text-xs font-bold text-warning bg-warning/10 px-2 py-1 rounded-full">Expiring Soon</span>
                                    @else
                                        <span class="text-xs font-bold text-success bg-success/10 px-2 py-1 rounded-full">Active</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('student-documents.view', $document->id) }}" class="text-info hover:text-info-dark transition-colors" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('student-documents.download', $document->id) }}" class="text-primary hover:text-primary-dark transition-colors" title="Download">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center">
                                    <div class="text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-sm font-medium">No documents uploaded yet</p>
                                        <p class="text-xs mt-1">Documents will appear here when students are enrolled</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Activities List --}}
            <div class="grid grid-cols-1 gap-4 md:gap-8">
                {{-- Recent Activities --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="text-left">
                            <h3 class="text-lg font-bold text-dark">All Recent Activities</h3>
                            <p class="text-sm text-gray-400">Latest events across your school</p>
                        </div>
                        <button class="text-sm font-semibold text-primary hover:text-primary-dark transition-colors">View All</button>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentActivities as $activity)
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 border border-gray-100 hover:border-{{ $activity['color'] }}/30 hover:shadow-md transition-all">
                            <div class="w-10 h-10 rounded-lg bg-{{ $activity['color'] }}/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-{{ $activity['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="text-sm font-bold text-dark">{{ $activity['title'] }}</h4>
                                    <span class="text-xs px-2 py-1 rounded-full bg-{{ $activity['color'] }}/10 text-{{ $activity['color'] }} font-semibold">{{ ucfirst($activity['type']) }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-400">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 text-gray-400">
                            <p class="text-sm">No recent activities</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Set Academic Session Modal --}}
<div id="dashboardSessionModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-gray-100 transform transition-all">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-dark">Set Academic Session</h3>
                    <p class="text-xs text-gray-400">Switch current active calendar session</p>
                </div>
            </div>
            <button type="button" onclick="closeDashboardSessionModal()" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="space-y-3 mb-6 max-h-72 overflow-y-auto pr-1">
            @forelse($allSessions ?? [] as $sess)
            <form method="POST" action="{{ route('sessions.set-active', $sess->id) }}">
                @csrf
                <button type="submit" class="w-full p-4 rounded-2xl border transition-all text-left flex items-center justify-between {{ $sess->is_current ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-gray-200 hover:border-primary/30 hover:bg-gray-50' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl {{ $sess->is_current ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center font-bold text-sm">
                            {{ substr($sess->name, 0, 4) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold {{ $sess->is_current ? 'text-primary' : 'text-dark' }}">{{ $sess->name }}</p>
                            <p class="text-xs text-gray-400">{{ $sess->start_date?->format('M Y') }} — {{ $sess->end_date?->format('M Y') }}</p>
                        </div>
                    </div>
                    @if($sess->is_current)
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-primary text-white uppercase">Current</span>
                    @else
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-gray-100 text-gray-600 uppercase hover:bg-primary hover:text-white transition-colors">Select</span>
                    @endif
                </button>
            </form>
            @empty
            <div class="text-center py-6 text-gray-400">
                <p class="text-sm">No academic sessions found.</p>
                <a href="{{ route('sessions.index') }}" class="text-xs text-primary font-semibold hover:underline mt-1 inline-block">Create New Session</a>
            </div>
            @endforelse
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
            <a href="{{ route('sessions.index') }}" class="text-xs text-primary font-semibold hover:underline flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Manage All Sessions
            </a>
            <button type="button" onclick="closeDashboardSessionModal()" class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold text-xs rounded-xl hover:bg-gray-200 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDashboardSessionModal() {
    document.getElementById('dashboardSessionModal').classList.remove('hidden');
}
function closeDashboardSessionModal() {
    document.getElementById('dashboardSessionModal').classList.add('hidden');
}

function filterDashboardClasses(selectedValue) {
    const cards = document.querySelectorAll('.dashboard-class-card');
    cards.forEach(card => {
        const className = card.getAttribute('data-class-name');
        if (selectedValue === 'all' || className === selectedValue) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endpush
@endsection
