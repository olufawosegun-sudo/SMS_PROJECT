@extends('layouts.app')
@section('title', 'My Attendance Record')
@section('body')
@php $userRole = Auth::user()->role->name; @endphp
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar')

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">

            @if($userRole === 'Student')
            {{-- ===================================================================== --}}
            {{-- STUDENT VIEW: Personal Attendance --}}
            {{-- ===================================================================== --}}
            @php
                $st = $studentStats ?? [
                    'total' => 0,
                    'present' => 0,
                    'late' => 0,
                    'absent' => 0,
                    'rate' => 100,
                ];
                $attendanceRate = $st['rate'];
                $punctualityRate = $st['total'] > 0 ? round(($st['present'] / $st['total']) * 100, 1) : 100;
            @endphp

            {{-- West African Secondary School Management Standard Student Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-900 rounded-3xl p-6 md:p-8 text-white shadow-xl">
                <div>
                    <span class="inline-block px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-full text-xs font-bold uppercase tracking-wider mb-2 border border-emerald-500/30">
                        OFFICIAL ACADEMIC ATTENDANCE LOG
                    </span>
                    <h1 class="text-3xl font-extrabold tracking-tight flex items-center gap-3">
                        ✋ My Attendance Record
                    </h1>
                    <p class="text-emerald-100/70 text-sm mt-1">
                        Track your daily roll-call, punctuality metrics, and terminal attendance standing.
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 flex items-center gap-4 text-right">
                    <div>
                        <p class="text-xs text-emerald-200/80 uppercase font-semibold">Attendance Rate</p>
                        <p class="text-3xl font-black {{ $attendanceRate >= 80 ? 'text-emerald-300' : ($attendanceRate >= 60 ? 'text-amber-300' : 'text-red-300') }}">
                            {{ $attendanceRate }}%
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/30 flex items-center justify-center text-2xl font-bold border border-emerald-400/40">
                        {{ $attendanceRate >= 80 ? '🏆' : ($attendanceRate >= 60 ? '⚠️' : '🚨') }}
                    </div>
                </div>
            </div>

            {{-- 4 Stat Metric Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-3xl font-black text-gray-900">{{ $st['total'] }}</p>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Total School Days</p>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-emerald-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-3xl font-black text-emerald-600">{{ $st['present'] }}</p>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Days Present</p>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-amber-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-3xl font-black text-amber-600">{{ $st['late'] }}</p>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Late Arrivals</p>
                </div>

                <div class="bg-white rounded-2xl p-5 border border-red-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-3xl font-black text-red-600">{{ $st['absent'] }}</p>
                    <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Days Absent</p>
                </div>
            </div>

            {{-- Progress & Quality Rating Bar --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-8">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-bold text-gray-700">Punctuality Score Rating</span>
                    <span class="text-sm font-extrabold text-emerald-600">{{ $punctualityRate }}% On-Time</span>
                </div>
                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full transition-all duration-500" style="width: {{ $punctualityRate }}%"></div>
                </div>
            </div>

            {{-- Daily Attendance Log Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">📅 Daily Attendance Register</h3>
                        <p class="text-xs text-gray-500 mt-1">Chronological history of roll-calls recorded for your account</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200">
                        {{ $studentAttendanceHistory->count() }} Entries Logged
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/80 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Time Recorded</th>
                                <th class="text-center px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Attendance Status</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Teacher Remark</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($studentAttendanceHistory as $log)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                    🗓️ {{ \Carbon\Carbon::parse($log->attendance_date)->format('D, M j, Y') }}
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-gray-500">
                                    {{ $log->attendance_time ? date('h:i A', strtotime($log->attendance_time)) : 'Morning Roll Call' }}
                                </td>
                                <td class="text-center px-6 py-4">
                                    @if($log->status === 'present')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        PRESENT
                                    </span>
                                    @elseif($log->status === 'late')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        LATE ARRIVAL
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-red-100 text-red-800 border border-red-300">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        ABSENT
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600">
                                    {{ $log->remark ?? '—' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-sm font-semibold text-gray-500">No Attendance History</p>
                                    <p class="text-xs text-gray-400 mt-1">Your daily attendance records will appear here as roll-call is submitted by your class teacher.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @elseif($userRole === 'Guardian')
            {{-- ===================================================================== --}}
            {{-- GUARDIAN VIEW: Ward Attendance --}}
            {{-- ===================================================================== --}}

            {{-- Guardian Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 bg-gradient-to-r from-purple-800 via-indigo-900 to-slate-900 rounded-3xl p-6 md:p-8 text-white shadow-xl">
                <div>
                    <span class="inline-block px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-xs font-bold uppercase tracking-wider mb-2 border border-purple-500/30">
                        PARENT/GUARDIAN PORTAL
                    </span>
                    <h1 class="text-3xl font-extrabold tracking-tight flex items-center gap-3">
                        👨‍👩‍👧‍👦 My Wards' Attendance
                    </h1>
                    <p class="text-purple-100/70 text-sm mt-1">
                        Monitor your children's school attendance, punctuality, and daily presence records.
                    </p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 flex items-center gap-4">
                    <div>
                        <p class="text-xs text-purple-200/80 uppercase font-semibold">Total Wards</p>
                        <p class="text-3xl font-black text-purple-300">
                            {{ $wardsStats->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-500/30 flex items-center justify-center text-2xl font-bold border border-purple-400/40">
                        👥
                    </div>
                </div>
            </div>

            @if($wardsStats->isEmpty())
            {{-- No Wards Linked --}}
            <div class="bg-white rounded-2xl p-12 border border-gray-100 shadow-sm text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No Wards Linked</h3>
                <p class="text-sm text-gray-500">You don't have any students linked to your guardian account yet.</p>
                <p class="text-xs text-gray-400 mt-2">Please contact the school administration to link your children to your account.</p>
            </div>
            @else
            {{-- Loop Through Each Ward --}}
            @foreach($wardsStats as $wardData)
            @php
                $ward = $wardData['student'];
                $total = $wardData['total'];
                $present = $wardData['present'];
                $late = $wardData['late'];
                $absent = $wardData['absent'];
                $rate = $wardData['rate'];
                $recentAttendance = $wardData['recent_attendance'];
                $punctualityRate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
            @endphp

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-8 overflow-hidden">
                {{-- Ward Header --}}
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold text-indigo-600 border-2 border-indigo-200">
                                {{ substr($ward->user->first_name, 0, 1) }}{{ substr($ward->user->last_name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $ward->user->first_name }} {{ $ward->user->last_name }}</h3>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">{{ $ward->schoolClass->name ?? 'N/A' }}</span>
                                    @if($ward->arm)
                                        - {{ $ward->arm->name }}
                                    @endif
                                    • Admission No: <span class="font-mono">{{ $ward->admission_no }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase font-semibold">Attendance Rate</p>
                            <p class="text-3xl font-black {{ $rate >= 80 ? 'text-emerald-600' : ($rate >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                {{ $rate }}%
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Statistics Cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 border-b border-gray-100">
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <p class="text-2xl font-black text-blue-600">{{ $total }}</p>
                        <p class="text-xs font-semibold text-gray-600 uppercase mt-1">School Days</p>
                    </div>
                    <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                        <p class="text-2xl font-black text-emerald-600">{{ $present }}</p>
                        <p class="text-xs font-semibold text-gray-600 uppercase mt-1">Present</p>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                        <p class="text-2xl font-black text-amber-600">{{ $late }}</p>
                        <p class="text-xs font-semibold text-gray-600 uppercase mt-1">Late</p>
                    </div>
                    <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                        <p class="text-2xl font-black text-red-600">{{ $absent }}</p>
                        <p class="text-xs font-semibold text-gray-600 uppercase mt-1">Absent</p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-gray-700">Punctuality Score</span>
                        <span class="text-xs font-extrabold text-emerald-600">{{ $punctualityRate }}% On-Time</span>
                    </div>
                    <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full transition-all duration-500" style="width: {{ $punctualityRate }}%"></div>
                    </div>
                </div>

                {{-- Recent Attendance History --}}
                <div class="p-6">
                    <h4 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Recent Attendance (Last 10 Days)
                    </h4>

                    @if($recentAttendance->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <p class="text-sm">No attendance records yet</p>
                    </div>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Date</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Time</th>
                                    <th class="text-center px-4 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Remark</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($recentAttendance as $log)
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($log->attendance_date)->format('D, M j, Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-xs font-mono text-gray-500">
                                        {{ $log->attendance_time ? date('h:i A', strtotime($log->attendance_time)) : 'Morning' }}
                                    </td>
                                    <td class="text-center px-4 py-3">
                                        @if($log->status === 'present')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            PRESENT
                                        </span>
                                        @elseif($log->status === 'late')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            LATE
                                        </span>
                                        @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            ABSENT
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        {{ $log->remark ?? '—' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            @endif

            @elseif(in_array($userRole, ['Principal', 'Owner']))
            {{-- ===================================================================== --}}
            {{-- PRINCIPAL / OWNER: Analytics Dashboard View                            --}}
            {{-- ===================================================================== --}}

            {{-- Date Filter --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <form method="GET" action="{{ route('attendance.index') }}" class="flex flex-col sm:flex-row items-end gap-4">
                    <div class="flex-1 w-full sm:w-auto">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Select Date</label>
                        <input type="date" name="date" value="{{ $selectedDate }}" required 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    </div>
                    <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        View Report
                    </button>
                </form>
            </div>

            {{-- Summary Stat Cards --}}
            @php
                $totalStudentsInSchool = $classSummaries->sum('total');
                $totalPresent = $classSummaries->sum('present');
                $totalLate = $classSummaries->sum('late');
                $totalAbsent = $classSummaries->sum('absent');
                $totalUnmarked = $classSummaries->sum('unmarked');
                $overallRate = $totalStudentsInSchool > 0 ? round(($totalPresent + ($totalLate * 0.5)) / $totalStudentsInSchool * 100) : 0;
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-2xl font-bold text-dark">{{ $totalStudentsInSchool }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Total Students</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-2xl font-bold {{ $overallRate >= 80 ? 'text-green-600' : 'text-amber-600' }}">{{ $overallRate }}%</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Attendance Rate</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-2xl font-bold text-green-600">{{ $totalPresent }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Present</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-2xl font-bold text-amber-600">{{ $totalLate }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Late</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-2xl font-bold text-red-600">{{ $totalAbsent }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Absent</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-2xl font-bold text-gray-500">{{ $totalUnmarked }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Unmarked</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-dark">Class-by-Class Breakdown</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50/80">
                                    <th class="text-left px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Class</th>
                                    <th class="text-center px-4 py-3.5 text-xs font-bold text-gray-500 uppercase">Total</th>
                                    <th class="text-center px-4 py-3.5 text-xs font-bold text-gray-500 uppercase">Present</th>
                                    <th class="text-center px-4 py-3.5 text-xs font-bold text-gray-500 uppercase">Late</th>
                                    <th class="text-center px-4 py-3.5 text-xs font-bold text-gray-500 uppercase">Absent</th>
                                    <th class="text-center px-6 py-3.5 text-xs font-bold text-gray-500 uppercase">Rate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($classSummaries as $cs)
                                <tr>
                                    <td class="px-6 py-4 font-bold text-dark">{{ $cs['name'] }}</td>
                                    <td class="text-center px-4 py-4">{{ $cs['total'] }}</td>
                                    <td class="text-center px-4 py-4 text-green-600 font-bold">{{ $cs['present'] }}</td>
                                    <td class="text-center px-4 py-4 text-amber-600 font-bold">{{ $cs['late'] }}</td>
                                    <td class="text-center px-4 py-4 text-red-600 font-bold">{{ $cs['absent'] }}</td>
                                    <td class="text-center px-6 py-4 font-bold">{{ $cs['rate'] }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-red-50/50">
                        <h3 class="text-lg font-bold text-dark">Absent & Late Students</h3>
                    </div>
                    <div class="divide-y divide-gray-50 max-h-[600px] overflow-y-auto">
                        @forelse($absentStudents as $record)
                        <div class="px-6 py-4">
                            <p class="text-sm font-bold text-dark">{{ $record->student->user->first_name ?? '' }} {{ $record->student->user->last_name ?? '' }}</p>
                            <p class="text-xs text-gray-500">{{ $record->student->schoolClass->name ?? '' }} • <span class="font-bold uppercase text-red-600">{{ $record->status }}</span></p>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center text-xs text-gray-400">No absentees reported today.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            @else
            {{-- ===================================================================== --}}
            {{-- TEACHER: Marking Form View                                             --}}
            {{-- ===================================================================== --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <form method="GET" action="{{ route('attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Date</label>
                        <input type="date" name="date" value="{{ $selectedDate }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Class</label>
                        <select name="class_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <option value="">All Classes</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm">Filter & Load</button>
                </form>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-dark mb-4">Mark Attendance ({{ $selectedDate }})</h3>
                @if($students->count() > 0)
                <form method="POST" action="{{ route('attendance.store') }}">
                    @csrf
                    <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
                    <table class="w-full mb-6">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Student</th>
                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Class</th>
                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Remark</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($students as $idx => $student)
                            <tr>
                                <td class="px-4 py-3">
                                    <input type="hidden" name="students[{{ $idx }}][student_id]" value="{{ $student->id }}">
                                    <p class="text-sm font-semibold text-dark">{{ $student->user->first_name }} {{ $student->user->last_name }}</p>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $student->schoolClass->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $existingStatus = $attendance->where('student_id', $student->id)->first()?->status;
                                    @endphp
                                    <select name="students[{{ $idx }}][status]" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold">
                                        <option value="present" {{ $existingStatus === 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="late" {{ $existingStatus === 'late' ? 'selected' : '' }}>Late</option>
                                        <option value="absent" {{ $existingStatus === 'absent' ? 'selected' : '' }}>Absent</option>
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="students[{{ $idx }}][remark]" placeholder="Notes" 
                                        value="{{ $attendance->where('student_id', $student->id)->first()?->remark }}"
                                        class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs w-full">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm">Submit Attendance</button>
                </form>
                @else
                <p class="text-gray-400 py-8 text-center text-sm">No active students to display. Select a class above.</p>
                @endif
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
