@extends('layouts.app')
@section('title', 'Teacher Attendance')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Teacher Attendance</h1>
                    <p class="text-gray-500">Record and review daily staff presence logs</p>
                </div>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <form method="GET" action="{{ route('teacher-attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Select Date</label>
                        <input type="date" name="date" value="{{ $selectedDate }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Load Roster</button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Mark Teacher Attendance --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-dark mb-4 font-bold">Mark Attendance ({{ $selectedDate }})</h3>
                    @if($teachers->count() > 0)
                    <form method="POST" action="{{ route('teacher-attendance.store') }}">
                        @csrf
                        <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Teacher</th>
                                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Subject/Dept</th>
                                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($teachers as $idx => $teacher)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <input type="hidden" name="teachers[{{ $idx }}][staff_id]" value="{{ $teacher->id }}">
                                            <p class="text-sm font-semibold text-dark">{{ $teacher->user->first_name }} {{ $teacher->user->last_name }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $teacher->designation ?? 'Teacher' }}</td>
                                        <td class="px-4 py-3">
                                            <select name="teachers[{{ $idx }}][status]" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold focus:outline-none">
                                                <option value="present">Present</option>
                                                <option value="late">Late</option>
                                                <option value="absent">Absent</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Roster</button>
                    </form>
                    @else
                    <p class="text-gray-400 py-8 text-center text-sm">No active teachers registered.</p>
                    @endif
                </div>

                {{-- Right: Status Logs --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-dark mb-4">Summary</h3>
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
                                    <p class="font-semibold text-dark">{{ $att->staff->user->first_name ?? 'N/A' }} {{ $att->staff->user->last_name ?? '' }}</p>
                                </div>
                                @php
                                    $col = $att->status === 'present' ? 'success' : ($att->status === 'late' ? 'warning' : 'warning-dark');
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
        </div>
    </main>
</div>
@endsection
