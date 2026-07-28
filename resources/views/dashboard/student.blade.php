@extends('layouts.app')

@section('title', 'Student Dashboard — ' . ($school->name ?? 'SMS Project'))

@section('body')
<div class="flex min-h-screen bg-gray-50/50">
    {{-- ======================================== SIDEBAR ======================================== --}}
    @include('partials.student_sidebar')

    {{-- ======================================== MAIN CONTENT ======================================== --}}
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        {{-- Top Bar --}}
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">
            {{-- Security / Password Reminder --}}
            @if(Hash::check('password123', Auth::user()->password))
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-xl p-4 shadow-sm" id="passwordReminder">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-amber-500/10 rounded-lg text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-amber-900">Security Recommendation</h3>
                            <p class="text-xs text-amber-700 mt-0.5">You are logged in with a default password. Please update your account password to protect your academic records.</p>
                            <div class="mt-2.5 flex items-center gap-3">
                                <a href="{{ route('password.request') }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                                    Change Password
                                </a>
                                <button onclick="document.getElementById('passwordReminder').remove()" class="text-xs font-medium text-amber-700 hover:text-amber-900">
                                    Dismiss
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Welcome Banner --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-emerald-900/10">
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold text-emerald-100 border border-white/10">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Student Portal • {{ $school->name ?? 'School Management System' }}
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Welcome back, {{ Auth::user()->first_name }}! 👋</h1>
                        <p class="text-emerald-100/80 text-sm max-w-xl">Track your academic continuous assessments, check your terminal results, view class schedules, and engage with your teachers.</p>
                    </div>

                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/15">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-lg font-bold text-white shadow-inner">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-emerald-200">Current Standing</p>
                            <p class="text-sm font-bold text-white">Active Student</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metric Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
                {{-- Enrolled Subjects --}}
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Enrolled Subjects</span>
                        <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-gray-900">9</span>
                        <span class="text-xs font-medium text-emerald-600">Active Term</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Core & Elective Subjects</p>
                </div>

                {{-- Attendance Score --}}
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Term Attendance</span>
                        <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-gray-900">96.4%</span>
                        <span class="text-xs font-medium text-emerald-600">Punctual</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">42 of 44 Days Attended</p>
                </div>

                {{-- Continuous Assessment --}}
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">CA Average Score</span>
                        <div class="p-2.5 bg-purple-50 text-purple-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-gray-900">34.5 / 40</span>
                        <span class="text-xs font-medium text-purple-600">Grade A</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">First & Second Test Combined</p>
                </div>

                {{-- CBT Tests Available --}}
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Active CBT Exams</span>
                        <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-black text-gray-900">2</span>
                        <span class="text-xs font-medium text-amber-600">Ready to Take</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Mid-Term Assessment Portal</p>
                </div>
            </div>

            {{-- Published Report Cards Section --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 border-2 border-emerald-500/30 shadow-xl shadow-emerald-900/5 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold mb-2">
                            <span>📄 Terminal Performance Documents</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">My Official Report Cards</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Approved & published term reports available to view online or print as official document</p>
                    </div>
                    <a href="{{ route('report-cards.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-extrabold rounded-xl border border-emerald-200 transition-colors">
                        <span>View All Reports</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                @if(isset($publishedReportCards) && $publishedReportCards->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($publishedReportCards as $reportCard)
                            <div class="bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 rounded-3xl p-6 text-white shadow-2xl shadow-emerald-900/20 relative overflow-hidden flex flex-col justify-between border border-emerald-700/50">
                                <div class="absolute -right-8 -top-8 w-36 h-36 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                                <div>
                                    <div class="flex items-center justify-between pb-4 border-b border-white/10">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center text-white text-xl shadow-inner border border-white/20">
                                                📜
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-extrabold text-white tracking-tight">{{ $reportCard->schoolClass->name ?? 'Class Report' }}</h4>
                                                <p class="text-xs font-semibold text-emerald-200">{{ $reportCard->term->name ?? 'Term' }} • {{ $reportCard->session->name ?? '' }}</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 bg-emerald-400/20 text-emerald-200 text-[11px] font-extrabold rounded-full uppercase tracking-wider border border-emerald-400/30 backdrop-blur-sm">
                                            ✓ Published
                                        </span>
                                    </div>

                                    {{-- Stats Grid --}}
                                    <div class="grid grid-cols-2 gap-3 my-5">
                                        <div class="p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 text-center">
                                            <p class="text-[11px] font-bold text-emerald-200 uppercase tracking-wider">Overall Average</p>
                                            <p class="text-3xl font-black text-white mt-1">{{ number_format($reportCard->average ?? 0, 1) }}%</p>
                                        </div>
                                        <div class="p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 text-center">
                                            <p class="text-[11px] font-bold text-emerald-200 uppercase tracking-wider">Class Position</p>
                                            <p class="text-3xl font-black text-amber-300 mt-1">{{ $reportCard->overall_position ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- HIGH VISIBILITY ACTION BUTTONS --}}
                                <div class="grid grid-cols-2 gap-3 pt-3 border-t border-white/10">
                                    <a href="{{ route('report-cards.show', $reportCard->id) }}" class="px-5 py-3.5 bg-emerald-400 hover:bg-emerald-300 text-emerald-950 font-black text-sm rounded-2xl shadow-xl hover:shadow-2xl transition-all text-center flex items-center justify-center gap-2 group/btn">
                                        <svg class="w-5 h-5 text-emerald-950 group-hover/btn:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span>VIEW REPORT</span>
                                    </a>
                                    <a href="{{ route('report-cards.show', $reportCard->id) }}?print=1" class="px-5 py-3.5 bg-white/15 hover:bg-white/25 text-white font-extrabold text-sm rounded-2xl border border-white/20 backdrop-blur-md shadow-md transition-all text-center flex items-center justify-center gap-2 group/btn">
                                        <svg class="w-5 h-5 text-amber-300 group-hover/btn:scale-125 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        <span>PRINT PDF</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-base font-bold text-gray-900">No Published Report Cards Available</p>
                        <p class="text-xs text-gray-500 max-w-md mx-auto mt-1">When your class teacher completes and publishes your terminal report card, it will appear right here for viewing and printing.</p>
                    </div>
                @endif
            </div>

            {{-- Quick Links & Modules --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span>⚡ Quick Student Tools</span>
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('results.index') }}" class="group p-4 bg-gray-50 hover:bg-emerald-50/60 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-all text-left block">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900 group-hover:text-emerald-800">Check Results</p>
                        <p class="text-xs text-gray-500 mt-0.5">Terminal scores & report cards</p>
                    </a>

                    <a href="{{ route('cbt-exams.index') }}" class="group p-4 bg-gray-50 hover:bg-blue-50/60 rounded-2xl border border-gray-100 hover:border-blue-200 transition-all text-left block">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900 group-hover:text-blue-800">CBT Exams</p>
                        <p class="text-xs text-gray-500 mt-0.5">Online quizzes & assessments</p>
                    </a>

                    <a href="{{ route('timetables.index') }}" class="group p-4 bg-gray-50 hover:bg-purple-50/60 rounded-2xl border border-gray-100 hover:border-purple-200 transition-all text-left block">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900 group-hover:text-purple-800">Class Timetable</p>
                        <p class="text-xs text-gray-500 mt-0.5">Period schedules & subjects</p>
                    </a>

                    <a href="{{ route('messages.index') }}" class="group p-4 bg-gray-50 hover:bg-amber-50/60 rounded-2xl border border-gray-100 hover:border-amber-200 transition-all text-left block">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900 group-hover:text-amber-800">Messages</p>
                        <p class="text-xs text-gray-500 mt-0.5">Reach teachers & school staff</p>
                    </a>
                </div>
            </div>

            {{-- Content Section: Announcements & Class Schedule --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- School Announcements --}}
                <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <span>📢 Principal & School Announcements</span>
                        </h3>
                        <a href="{{ route('announcements.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline">View All</a>
                    </div>

                    <div class="space-y-3">
                        <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100/80">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Office of the Principal</span>
                                <span class="text-[11px] text-emerald-600">Today</span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900">Mid-Term Examination & CBT Schedule Update</h4>
                            <p class="text-xs text-gray-600 mt-1">All senior secondary students (SSS1-3) are expected to review their revision guidelines. CBT practice sessions start on Monday.</p>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Academic Board</span>
                                <span class="text-[11px] text-gray-500">2 days ago</span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900">Inter-House Sports Competition Registration</h4>
                            <p class="text-xs text-gray-600 mt-1">Interested students should submit physical health forms to their respective class teachers before Friday.</p>
                        </div>
                    </div>
                </div>

                {{-- Daily Timetable Overview --}}
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">📅 Today's Classes</h3>
                        <span class="text-xs font-medium text-gray-500">Wednesday</span>
                    </div>

                    <div class="space-y-3">
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-900">Mathematics</p>
                                <p class="text-[11px] text-gray-500">08:15 AM - 09:00 AM</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md">Period 1</span>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-900">English Language</p>
                                <p class="text-[11px] text-gray-500">09:00 AM - 09:45 AM</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md">Period 2</span>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-900">Physics Practical</p>
                                <p class="text-[11px] text-gray-500">10:15 AM - 11:45 AM</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 bg-purple-100 text-purple-700 rounded-md">Lab</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
