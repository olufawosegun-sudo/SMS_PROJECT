@extends('layouts.app')

@section('title', 'Dashboard — ' . ($school->name ?? 'EduWest Africa'))

@section('body')
<div class="flex min-h-screen bg-surface">
    {{-- Mobile Overlay --}}
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden" onclick="toggleSidebar()"></div>

    {{-- ========================================
         SIDEBAR
         ======================================== --}}
    <aside class="fixed top-0 left-0 bottom-0 w-64 sidebar-gradient z-40 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0" id="sidebar">
        {{-- Logo & Brand --}}
        <div class="px-6 py-6 border-b border-white/10">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="text-left">
                    <span class="text-lg font-bold text-white">Edu<span class="text-accent">West</span></span>
                    <span class="block text-[9px] text-white/40 -mt-1 tracking-widest uppercase">Africa</span>
                </div>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 overflow-y-auto">
            <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mb-4 px-3">Main Menu</p>
            <ul class="space-y-1">
                @foreach([
                    ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard', 'active' => true],
                    ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Students', 'active' => false],
                    ['icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Teachers', 'active' => false],
                    ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Classes', 'active' => false],
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'Attendance', 'active' => false],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Grades', 'active' => false],
                    ['icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Fees', 'active' => false],
                ] as $item)
                <li>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                        {{ $item['active']
                            ? 'bg-primary text-white shadow-lg shadow-primary/30'
                            : 'text-white/60 hover:text-white hover:bg-white/5'
                        }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span>{{ $item['label'] }}</span>
                        @if($item['label'] === 'Students')
                        <span class="ml-auto text-[10px] bg-accent/20 text-accent px-2 py-0.5 rounded-full font-bold">{{ $stats['total_students'] }}</span>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>

            <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mt-8 mb-4 px-3">Configuration</p>
            <ul class="space-y-1">
                @foreach([
                    ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Timetable'],
                    ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'label' => 'Settings'],
                ] as $item)
                <li>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all duration-200">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </nav>

        {{-- User Profile at Bottom --}}
        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-accent/20 flex items-center justify-center text-sm font-bold text-accent">
                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-white/40 truncate">{{ Auth::user()->role->name ?? 'Administrator' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500/20 flex items-center justify-center transition-colors group" title="Logout">
                        <svg class="w-4 h-4 text-white/40 group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ========================================
         MAIN CONTENT
         ======================================== --}}
    <main class="flex-1 w-full lg:ml-64 transition-all duration-300">
        {{-- Top Bar --}}
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-lg border-b border-gray-100">
            <div class="flex items-center justify-between px-4 md:px-8 py-4">
                {{-- Mobile Menu Button --}}
                <button onclick="toggleSidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors mr-4">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                {{-- Search --}}
                <div class="relative flex-1 max-w-md hidden md:block">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Search..."
                           class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-2 md:gap-4 ml-auto md:ml-6">
                    {{-- Notifications --}}
                    <button class="relative w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(count($notifications) > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-danger rounded-full text-[10px] font-bold text-white flex items-center justify-center">{{ count($notifications) }}</span>
                        @endif
                    </button>

                    {{-- User Profile details --}}
                    <div class="flex items-center gap-2 md:gap-3 pl-2 md:pl-4 border-l border-gray-200">
                        <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-primary/10 flex items-center justify-center text-xs md:text-sm font-bold text-primary">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-semibold text-dark">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->role->name ?? 'Administrator' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Welcome Banner --}}
            <div class="bg-gradient-to-r from-primary to-primary-dark rounded-2xl p-6 md:p-8 mb-6 md:mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 md:w-64 md:h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
                <div class="absolute bottom-0 right-10 md:right-20 w-20 h-20 md:w-32 md:h-32 bg-accent/10 rounded-full translate-y-1/2"></div>
                <div class="relative z-10 text-left">
                    <h1 class="text-xl md:text-2xl font-bold text-white mb-1">{{ $school->name ?? 'West African Excellence Academy' }}</h1>
                    <p class="text-white/80 italic text-xs md:text-sm mb-3 md:mb-4">"{{ $school->motto ?? 'Knowledge, Character, and Excellence' }}"</p>
                    <div class="flex flex-wrap items-center gap-2 md:gap-4 text-xs font-semibold text-white/90">
                        <span class="px-2 md:px-2.5 py-1 bg-white/20 rounded-md">Session: {{ $currentSession->name ?? date('Y').'/'.(date('Y')+1) }}</span>
                        <span class="px-2 md:px-2.5 py-1 bg-white/20 rounded-md">Term: {{ $currentTerm->name ?? 'First Term' }}</span>
                        <span class="px-2 md:px-2.5 py-1 bg-accent text-dark rounded-md">Country: {{ $school->country ?? 'Nigeria' }}</span>
                    </div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                @foreach([
                    [
                        'label' => 'Total Students',
                        'value' => $stats['total_students'],
                        'change' => 'Enrolled',
                        'trend' => 'up',
                        'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                        'color' => 'primary',
                        'bg' => 'bg-primary/10',
                    ],
                    [
                        'label' => 'Total Teachers',
                        'value' => $stats['total_teachers'],
                        'change' => 'Instructors',
                        'trend' => 'up',
                        'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                        'color' => 'info',
                        'bg' => 'bg-info/10',
                    ],
                    [
                        'label' => 'Total Classes',
                        'value' => $stats['total_classes'],
                        'change' => 'Active classes',
                        'trend' => 'up',
                        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                        'color' => 'accent',
                        'bg' => 'bg-accent/10',
                    ],
                    [
                        'label' => 'Attendance Rate',
                        'value' => $stats['attendance_rate'] . '%',
                        'change' => 'Avg 7-day rate',
                        'trend' => 'up',
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                        'color' => 'success',
                        'bg' => 'bg-success/10',
                    ],
                ] as $index => $card)
                <div class="bg-white rounded-2xl p-4 md:p-6 border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-start justify-between mb-3 md:mb-4">
                        <div class="w-10 h-10 md:w-12 md:h-12 {{ $card['bg'] }} rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-{{ $card['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1 text-[9px] md:text-[10px] font-bold text-{{ $card['color'] }} bg-{{ $card['color'] }}/10 px-2 md:px-2.5 py-1 rounded-full uppercase tracking-wider">
                            {{ $card['change'] }}
                        </span>
                    </div>
                    <p class="text-2xl md:text-3xl font-extrabold text-dark mb-1 text-left">{{ $card['value'] }}</p>
                    <p class="text-xs md:text-sm text-gray-400 text-left">{{ $card['label'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 mb-6 md:mb-8">
                {{-- Attendance Chart --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-4 md:p-6 border border-gray-100">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 md:mb-6 gap-3">
                        <div class="text-left">
                            <h3 class="text-base md:text-lg font-bold text-dark">Weekly Student Attendance</h3>
                            <p class="text-xs md:text-sm text-gray-400">Weekly tracking of active students</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 text-xs font-semibold bg-primary/10 text-primary rounded-lg">Real Data</span>
                        </div>
                    </div>
                    <div class="flex items-end gap-2 md:gap-3 h-40 md:h-48">
                        @foreach($attendanceData as $index => $value)
                        <div class="flex-1 flex flex-col items-center gap-1 md:gap-2">
                            <span class="text-[10px] md:text-xs font-semibold text-gray-500">{{ $value }}%</span>
                            <div class="w-full rounded-t-lg transition-all duration-500 hover:opacity-85 relative group"
                                 style="height: {{ $value }}%; background: linear-gradient(to top, #1B6B3E, #2D8F54);">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-dark text-white text-[10px] px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                    {{ $value }}% present
                                </div>
                            </div>
                            <span class="text-[10px] md:text-xs text-gray-400 font-medium">{{ $attendanceLabels[$index] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white rounded-2xl p-4 md:p-6 border border-gray-100">
                    <h3 class="text-base md:text-lg font-bold text-dark mb-4 md:mb-6 text-left">Administrative Controls</h3>
                    <div class="space-y-2 md:space-y-3">
                        @foreach([
                            ['icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', 'label' => 'Enroll New Student', 'color' => 'primary'],
                            ['icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Generate Report Cards', 'color' => 'accent'],
                            ['icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'label' => 'Send SMS Notice', 'color' => 'info'],
                            ['icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Collect Fee Payment', 'color' => 'success'],
                        ] as $action)
                        <button class="w-full flex items-center gap-2 md:gap-3 px-3 md:px-4 py-2.5 md:py-3 rounded-xl border border-gray-100 hover:border-{{ $action['color'] }}/30 hover:bg-{{ $action['color'] }}/5 transition-all duration-200 group text-left">
                            <div class="w-8 h-8 md:w-9 md:h-9 rounded-lg bg-{{ $action['color'] }}/10 flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                                <svg class="w-4 h-4 md:w-4.5 md:h-4.5 text-{{ $action['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"/>
                                </svg>
                            </div>
                            <span class="text-xs md:text-sm font-semibold text-gray-700 group-hover:text-dark transition-colors flex-1">{{ $action['label'] }}</span>
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-{{ $action['color'] }} group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Recent Activity & Announcements --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                {{-- Recent Activity Table --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 md:p-6 border-b border-gray-100 gap-3">
                        <div class="text-left">
                            <h3 class="text-base md:text-lg font-bold text-dark">Recent Enrolled Students</h3>
                            <p class="text-xs md:text-sm text-gray-400">Latest students admitted to class levels</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="text-left text-[10px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 md:px-6 py-3">Student</th>
                                    <th class="text-left text-[10px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 md:px-6 py-3 hidden sm:table-cell">Class/Arm</th>
                                    <th class="text-left text-[10px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 md:px-6 py-3 hidden md:table-cell">Date</th>
                                    <th class="text-left text-[10px] md:text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 md:px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($recentActivities as $activity)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-left">
                                        <div class="flex items-center gap-2 md:gap-3">
                                            <div class="w-8 h-8 md:w-9 md:h-9 rounded-lg bg-primary/10 flex items-center justify-center text-[10px] md:text-xs font-bold text-primary flex-shrink-0">
                                                {{ strtoupper(substr($activity['student'], 0, 2)) }}
                                            </div>
                                            <span class="text-xs md:text-sm font-semibold text-dark truncate">{{ $activity['student'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm text-gray-600 text-left hidden sm:table-cell">{{ $activity['action'] }}</td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm text-gray-400 text-left hidden md:table-cell">{{ $activity['date'] }}</td>
                                    <td class="px-4 md:px-6 py-3 md:py-4 text-left">
                                        <span class="inline-flex items-center gap-1 px-2 md:px-2.5 py-1 text-[10px] md:text-xs font-semibold bg-success/10 text-success rounded-full">
                                            <span class="w-1.5 h-1.5 bg-success rounded-full"></span>
                                            Active
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- School Announcements --}}
                <div class="bg-white rounded-2xl p-4 md:p-6 border border-gray-100">
                    <h3 class="text-base md:text-lg font-bold text-dark mb-4 md:mb-6 text-left">Official Announcements</h3>
                    <div class="space-y-3 md:space-y-4">
                        @forelse($announcements as $announcement)
                        <div class="p-3 md:p-4 rounded-xl bg-gray-50 border border-gray-100 hover:border-primary/20 transition-all text-left">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] md:text-xs font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-md">Notice</span>
                                <span class="text-[9px] md:text-[10px] text-gray-400 font-semibold">{{ $announcement->announced_at->format('M d, Y') }}</span>
                            </div>
                            <h4 class="text-xs md:text-sm font-bold text-dark mb-1">{{ $announcement->title }}</h4>
                            <p class="text-[11px] md:text-xs text-gray-500 leading-relaxed">{{ $announcement->body }}</p>
                        </div>
                        @empty
                        <div class="text-center py-8 md:py-10 text-gray-400 text-xs md:text-sm">
                            <span class="text-2xl md:text-3xl block mb-2">📢</span>
                            No current announcements.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Mobile Sidebar Toggle Script --}}
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
    
    // Prevent body scroll when sidebar is open on mobile
    if (!sidebar.classList.contains('-translate-x-full')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}

// Close sidebar when clicking outside on mobile
document.getElementById('sidebarOverlay').addEventListener('click', function() {
    toggleSidebar();
});

// Close sidebar when resizing to desktop
window.addEventListener('resize', function() {
    if (window.innerWidth >= 1024) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
});
</script>
@endsection
