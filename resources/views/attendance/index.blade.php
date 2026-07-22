@extends('layouts.app')
@section('title', 'Student Attendance')
@section('body')
<div class="flex min-h-screen bg-surface">
    @if($userRole === 'Teacher')
        @include('partials.teacher_sidebar')
    @else
        @include('partials.sidebar', ['role' => strtolower($userRole)])
    @endif

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">

            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">
                        @if(in_array($userRole, ['Principal', 'Owner']))
                            📊 Student Attendance Overview
                        @else
                            ✏️ Student Attendance
                        @endif
                    </h1>
                    <p class="text-gray-500">
                        @if(in_array($userRole, ['Principal', 'Owner']))
                            School-wide daily attendance report and class breakdown
                        @else
                            Record and monitor student presence in class
                        @endif
                    </p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- ===================================================================== --}}
            {{-- PRINCIPAL / OWNER: Analytics Dashboard View                            --}}
            {{-- ===================================================================== --}}
            @if(in_array($userRole, ['Principal', 'Owner']))

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
                $totalMarked = $totalPresent + $totalLate + $totalAbsent;
                $overallRate = $totalStudentsInSchool > 0 ? round(($totalPresent + ($totalLate * 0.5)) / $totalStudentsInSchool * 100) : 0;
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                {{-- Total Students --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-dark">{{ $totalStudentsInSchool }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Total Students</p>
                </div>

                {{-- Overall Attendance Rate --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl {{ $overallRate >= 80 ? 'bg-green-100' : ($overallRate >= 60 ? 'bg-amber-100' : 'bg-red-100') }} flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $overallRate >= 80 ? 'text-green-600' : ($overallRate >= 60 ? 'text-amber-600' : 'text-red-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold {{ $overallRate >= 80 ? 'text-green-600' : ($overallRate >= 60 ? 'text-amber-600' : 'text-red-600') }}">{{ $overallRate }}%</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Attendance Rate</p>
                </div>

                {{-- Present --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-green-600">{{ $totalPresent }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Present</p>
                </div>

                {{-- Late --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-amber-600">{{ $totalLate }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Late</p>
                </div>

                {{-- Absent --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-red-600">{{ $totalAbsent }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Absent</p>
                </div>

                {{-- Unmarked --}}
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-500">{{ $totalUnmarked }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-1">Not Yet Marked</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Class-by-Class Breakdown Table --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-dark">Class-by-Class Breakdown</h3>
                            <p class="text-xs text-gray-500 mt-1">Attendance summary for {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1.5 bg-primary/10 text-primary rounded-full">{{ $classSummaries->count() }} Classes</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50/80">
                                    <th class="text-left px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="text-center px-4 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="text-center px-4 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Present</th>
                                    <th class="text-center px-4 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Late</th>
                                    <th class="text-center px-4 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Absent</th>
                                    <th class="text-center px-4 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Unmarked</th>
                                    <th class="text-center px-6 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Rate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($classSummaries as $cs)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-dark">{{ $cs['name'] }}</p>
                                    </td>
                                    <td class="text-center px-4 py-4">
                                        <span class="text-sm font-semibold text-gray-700">{{ $cs['total'] }}</span>
                                    </td>
                                    <td class="text-center px-4 py-4">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">{{ $cs['present'] }}</span>
                                    </td>
                                    <td class="text-center px-4 py-4">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">{{ $cs['late'] }}</span>
                                    </td>
                                    <td class="text-center px-4 py-4">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold {{ $cs['absent'] > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500' }}">{{ $cs['absent'] }}</span>
                                    </td>
                                    <td class="text-center px-4 py-4">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-bold {{ $cs['unmarked'] > 0 ? 'bg-gray-200 text-gray-600' : 'bg-green-50 text-green-600' }}">{{ $cs['unmarked'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500 {{ $cs['rate'] >= 80 ? 'bg-green-500' : ($cs['rate'] >= 60 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $cs['rate'] }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold {{ $cs['rate'] >= 80 ? 'text-green-600' : ($cs['rate'] >= 60 ? 'text-amber-600' : 'text-red-600') }} min-w-[36px] text-right">{{ $cs['rate'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12 text-gray-400 text-sm">No classes found in this school.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($classSummaries->count() > 0)
                            <tfoot>
                                <tr class="bg-gray-50 font-bold">
                                    <td class="px-6 py-4 text-sm text-dark">TOTAL</td>
                                    <td class="text-center px-4 py-4 text-sm text-dark">{{ $totalStudentsInSchool }}</td>
                                    <td class="text-center px-4 py-4 text-sm text-green-700">{{ $totalPresent }}</td>
                                    <td class="text-center px-4 py-4 text-sm text-amber-700">{{ $totalLate }}</td>
                                    <td class="text-center px-4 py-4 text-sm text-red-700">{{ $totalAbsent }}</td>
                                    <td class="text-center px-4 py-4 text-sm text-gray-600">{{ $totalUnmarked }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold {{ $overallRate >= 80 ? 'text-green-600' : ($overallRate >= 60 ? 'text-amber-600' : 'text-red-600') }}">{{ $overallRate }}%</span>
                                    </td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Right: Today's Absentees & Late Students --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-red-50/50">
                        <h3 class="text-lg font-bold text-dark flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Absent & Late Students
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Needs follow-up for {{ \Carbon\Carbon::parse($selectedDate)->format('M j, Y') }}</p>
                    </div>
                    <div class="divide-y divide-gray-50 max-h-[600px] overflow-y-auto">
                        @forelse($absentStudents as $record)
                        <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 {{ $record->status === 'absent' ? 'bg-red-500' : 'bg-amber-500' }}">
                                    {{ substr($record->student->user->first_name ?? '', 0, 1) }}{{ substr($record->student->user->last_name ?? '', 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-dark truncate">
                                        {{ $record->student->user->first_name ?? 'N/A' }} {{ $record->student->user->last_name ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $record->student->schoolClass->name ?? 'N/A' }}</p>
                                    @if($record->remark)
                                    <p class="text-xs text-gray-400 italic mt-1">{{ $record->remark }}</p>
                                    @endif
                                    @if($record->student->guardians && $record->student->guardians->count() > 0)
                                    <div class="mt-2 flex items-center gap-1.5">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span class="text-[10px] text-gray-400">
                                            {{ $record->student->guardians->first()->user->phone ?? 'No phone' }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $record->status === 'absent' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $record->status }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-12 text-center">
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-sm font-semibold text-green-800">All Clear!</p>
                            <p class="text-xs text-gray-500 mt-1">No absences or late arrivals recorded for this date.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ===================================================================== --}}
            {{-- TEACHER: Marking Form View                                             --}}
            {{-- ===================================================================== --}}
            @else

            {{-- Filters --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <form method="GET" action="{{ route('attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Date</label>
                        <input type="date" name="date" value="{{ $selectedDate }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Class</label>
                        <select name="class_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                            <option value="">All Classes</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Filter & Load</button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Mark Attendance Form --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-dark mb-4">Mark Attendance ({{ $selectedDate }})</h3>
                    @if($students->count() > 0)
                    <form method="POST" action="{{ route('attendance.store') }}">
                        @csrf
                        <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full">
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
                                            <select name="students[{{ $idx }}][status]" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold focus:outline-none">
                                                <option value="present" {{ $existingStatus === 'present' ? 'selected' : '' }}>Present</option>
                                                <option value="late" {{ $existingStatus === 'late' ? 'selected' : '' }}>Late</option>
                                                <option value="absent" {{ $existingStatus === 'absent' ? 'selected' : '' }}>Absent</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="students[{{ $idx }}][remark]" placeholder="Notes" 
                                                value="{{ $attendance->where('student_id', $student->id)->first()?->remark }}"
                                                class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs w-full focus:outline-none">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Submit Attendance</button>
                    </form>
                    @else
                    <p class="text-gray-400 py-8 text-center text-sm">No active students to display. Select a class above.</p>
                    @endif
                </div>

                {{-- Right: Attendance Logs / Summary --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-dark mb-4">Summary Stats</h3>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between p-3 rounded-lg bg-primary/5 border border-primary/20">
                                <span class="text-sm font-semibold text-gray-700">Present</span>
                                <span class="text-lg font-bold text-primary">{{ $summary['present'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-warning/5 border border-warning/20">
                                <span class="text-sm font-semibold text-gray-700">Late</span>
                                <span class="text-lg font-bold text-warning">{{ $summary['late'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-danger/5 border border-danger/20">
                                <span class="text-sm font-semibold text-gray-700">Absent</span>
                                <span class="text-lg font-bold text-danger">{{ $summary['absent'] }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-bold text-dark mb-3">Logs Recorded</h4>
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                            @forelse($attendance as $att)
                            <div class="flex items-center justify-between p-2 border-b border-gray-100 text-xs">
                                <div>
                                    <p class="font-semibold text-dark">{{ $att->student->user->first_name ?? 'N/A' }} {{ $att->student->user->last_name ?? '' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $att->student->schoolClass->name ?? 'N/A' }}</p>
                                </div>
                                @php
                                    $col = $att->status === 'present' ? 'success' : ($att->status === 'late' ? 'warning' : 'danger');
                                @endphp
                                <span class="font-bold text-{{ $col }} uppercase text-[10px] bg-{{ $col }}/10 px-2 py-0.5 rounded">{{ $att->status }}</span>
                            </div>
                            @empty
                            <p class="text-gray-400 text-center py-4 text-xs">No records today</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            @endif
        </div>
    </main>
</div>
@endsection
