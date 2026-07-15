@extends('layouts.app')

@section('title', 'Principal Dashboard — ' . ($school->name ?? 'EduWest Africa'))

@section('body')
<div class="flex min-h-screen bg-surface">
    {{-- ======================================== SIDEBAR ======================================== --}}
    @include('partials.sidebar', ['role' => 'principal'])

    {{-- ======================================== MAIN CONTENT ======================================== --}}
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        {{-- Top Bar --}}
        @include('partials.topbar')

        {{-- Page Content --}}
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Password Change Reminder Banner (for staff using default password) --}}
            @if(Hash::check('password123', Auth::user()->password))
            <div class="mb-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 rounded-lg p-4 shadow-sm" id="passwordReminder">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-semibold text-yellow-800">Security Reminder</h3>
                        <p class="mt-1 text-sm text-yellow-700">
                            You are using the default password. For security, please change your password.
                        </p>
                        <div class="mt-3 flex items-center gap-3">
                            <a href="{{ route('password.request') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Change Password
                            </a>
                            <button onclick="document.getElementById('passwordReminder').remove()" class="text-xs text-yellow-600 hover:text-yellow-800 font-medium transition-colors">
                                Dismiss
                            </button>
                        </div>
                    </div>
                    <button onclick="document.getElementById('passwordReminder').remove()" class="flex-shrink-0 ml-3 text-yellow-400 hover:text-yellow-600 transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            {{-- Welcome Banner --}}
            <div class="bg-gradient-to-r from-accent to-primary rounded-2xl p-5 md:p-8 mb-6 md:mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
                <div class="absolute bottom-0 right-20 w-32 h-32 bg-white/10 rounded-full translate-y-1/2"></div>
                <div class="relative z-10 text-left">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-white mb-2">Welcome back, {{ $user->first_name }}!</h1>
                            <p class="text-white/80 text-lg mb-1">{{ $school->name ?? 'West African Excellence Academy' }}</p>
                            <p class="text-white/60 italic text-sm">"{{ $school->motto ?? 'Knowledge, Character, and Excellence' }}"</p>
                        </div>
                        <div class="hidden lg:flex items-center gap-3">
                            <div class="bg-white/10 backdrop-blur-md rounded-xl px-4 py-3 border border-white/20">
                                <p class="text-xs text-white/60 mb-1">Current Session</p>
                                <p class="text-lg font-bold text-white">{{ $currentSession->name ?? date('Y').'/'.(date('Y')+1) }}</p>
                            </div>
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
                        <span class="px-3 py-2 bg-white/30 backdrop-blur-sm text-white rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            Principal Access
                        </span>
                        <span class="px-3 py-2 bg-success/20 backdrop-blur-sm text-white rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Academic Management
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick Action Buttons (Academic Focused) --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 mb-6 md:mb-8">
                @foreach([
                    ['label' => 'Manage Students', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'primary', 'route' => 'students.index'],
                    ['label' => 'Manage Teachers', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'info', 'route' => 'teachers.index'],
                    ['label' => 'Attendance', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'color' => 'success', 'route' => '#'],
                    ['label' => 'Announcements', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'color' => 'accent', 'route' => '#'],
                    ['label' => 'Reports', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'warning', 'route' => '#'],
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

            {{-- Academic Statistics Cards (NO Financial Data) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                @foreach([
                    ['label' => 'Total Students', 'value' => $stats['total_students'], 'change' => 'Enrolled', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'primary'],
                    ['label' => 'Total Teachers', 'value' => $stats['total_teachers'], 'change' => 'Active', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'info'],
                    ['label' => 'Total Classes', 'value' => $stats['total_classes'], 'change' => 'Active', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'accent'],
                    ['label' => 'Total Subjects', 'value' => $stats['total_subjects'], 'change' => 'Curriculum', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color' => 'success'],
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

            {{-- Today's Attendance Overview --}}
            <div class="bg-gradient-to-r from-success to-success-dark rounded-2xl p-4 md:p-6 mb-6 md:mb-8 text-white">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Today's Attendance</h3>
                        <p class="text-white/80">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20">
                            <p class="text-sm text-white/70 mb-1">Present</p>
                            <p class="text-2xl font-bold">{{ $stats['today_attendance']['present'] }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20">
                            <p class="text-sm text-white/70 mb-1">Absent</p>
                            <p class="text-2xl font-bold">{{ $stats['today_attendance']['absent'] }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20">
                            <p class="text-sm text-white/70 mb-1">Rate</p>
                            <p class="text-2xl font-bold">{{ $stats['today_attendance']['rate'] }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 mb-6 md:mb-8">
                {{-- Weekly Attendance Trend --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-dark mb-4">Weekly Attendance Trend</h3>
                    <canvas id="attendanceChart" height="200"></canvas>
                </div>

                {{-- Students by Class --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-dark mb-4">Students by Class</h3>
                    <div class="space-y-3">
                        @forelse($studentsByClass as $class)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700 font-medium">{{ $class->class_name }}</span>
                            <span class="px-3 py-1 bg-primary/10 text-primary rounded-lg font-bold">{{ $class->count }}</span>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">No class data available</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Recent Activities --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-6 md:mb-8">
                <h3 class="text-lg font-bold text-dark mb-4">Recent Activities</h3>
                <div class="space-y-3">
                    @forelse($recentActivities as $activity)
                    <div class="flex items-start gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-{{ $activity['color'] }}/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-{{ $activity['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-dark">{{ $activity['title'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['description'] }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $activity['time'] }}</span>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No recent activities</p>
                    @endforelse
                </div>
            </div>

            {{-- Announcements --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-dark mb-4">Recent Announcements</h3>
                <div class="space-y-4">
                    @forelse($announcements as $announcement)
                    <div class="border-l-4 border-accent pl-4 py-2">
                        <h4 class="font-semibold text-dark">{{ $announcement->title }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->description ?? '', 100) }}</p>
                        <p class="text-xs text-gray-400 mt-2">{{ $announcement->announced_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No announcements yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Attendance Chart
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($attendanceLabels) !!},
            datasets: [{
                label: 'Attendance Rate (%)',
                data: {!! json_encode($attendanceData) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
@endpush
@endsection
