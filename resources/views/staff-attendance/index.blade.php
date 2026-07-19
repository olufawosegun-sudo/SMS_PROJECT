@extends('layouts.app')
@section('title', 'Staff Attendance')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Staff Attendance</h1>
                    <p class="text-gray-500">Track daily attendance for all staff members (teachers, principals, admin)</p>
                </div>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif
            @if(session('error'))
            <div class="mb-6 p-4 bg-danger/10 border border-danger/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-danger font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <form method="GET" action="{{ route('staff-attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Select Date</label>
                        <input type="date" name="date" value="{{ $selectedDate }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Load Attendance</button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Mark Staff Attendance --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-dark mb-4">Mark Attendance ({{ date('D, M d, Y', strtotime($selectedDate)) }})</h3>
                    @if($staffMembers->count() > 0)
                    <form method="POST" action="{{ route('staff-attendance.store') }}" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
                        <div class="overflow-x-auto mb-6">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Staff Member</th>
                                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Role</th>
                                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Late (mins)</th>
                                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase">Remark</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($staffMembers as $idx => $staff)
                                    @php
                                        $existingAtt = $attendance->where('staff_id', $staff->id)->first();
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3">
                                            <input type="hidden" name="staff[{{ $idx }}][staff_id]" value="{{ $staff->id }}">
                                            <p class="text-sm font-semibold text-dark">{{ $staff->user->first_name }} {{ $staff->user->last_name }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $staff->staff_no }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-xs font-semibold px-2 py-1 rounded-lg bg-accent/10 text-accent">
                                                {{ $staff->staff_type }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <select name="staff[{{ $idx }}][status]" class="status-select px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-primary/20" data-index="{{ $idx }}">
                                                <option value="present" {{ ($existingAtt && $existingAtt->status === 'present') ? 'selected' : '' }}>Present</option>
                                                <option value="late" {{ ($existingAtt && $existingAtt->status === 'late') ? 'selected' : '' }}>Late</option>
                                                <option value="absent" {{ ($existingAtt && $existingAtt->status === 'absent') ? 'selected' : '' }}>Absent</option>
                                                <option value="on_leave" {{ ($existingAtt && $existingAtt->status === 'on_leave') ? 'selected' : '' }}>On Leave</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="staff[{{ $idx }}][late_minutes]" 
                                                   value="{{ $existingAtt->late_minutes ?? '' }}"
                                                   placeholder="0" 
                                                   min="0" 
                                                   class="late-minutes w-20 px-2 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-primary/20" 
                                                   id="late_{{ $idx }}"
                                                   {{ ($existingAtt && $existingAtt->status === 'late') ? '' : 'disabled' }}>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="staff[{{ $idx }}][remark]" 
                                                   value="{{ $existingAtt->remark ?? '' }}"
                                                   placeholder="Optional note" 
                                                   maxlength="500"
                                                   class="w-full px-2 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-primary/20">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm shadow-lg shadow-primary/20">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Attendance
                        </button>
                    </form>
                    @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <p class="text-gray-400 text-sm">No active staff members registered.</p>
                    </div>
                    @endif
                </div>

                {{-- Right: Summary & Logs --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-dark mb-4">Summary</h3>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 border border-gray-100">
                                <span class="text-sm font-semibold text-gray-700">Total Staff</span>
                                <span class="text-lg font-bold text-dark">{{ $summary['total'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-success/5 border border-success/20">
                                <span class="text-sm font-semibold text-gray-700">Present</span>
                                <span class="text-lg font-bold text-success">{{ $summary['present'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-warning/5 border border-warning/20">
                                <span class="text-sm font-semibold text-gray-700">Late</span>
                                <span class="text-lg font-bold text-warning">{{ $summary['late'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-danger/5 border border-danger/20">
                                <span class="text-sm font-semibold text-gray-700">Absent</span>
                                <span class="text-lg font-bold text-danger">{{ $summary['absent'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-lg bg-primary/5 border border-primary/20">
                                <span class="text-sm font-semibold text-gray-700">On Leave</span>
                                <span class="text-lg font-bold text-primary">{{ $summary['on_leave'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-bold text-dark mb-3">Today's Records</h4>
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                            @forelse($attendance as $att)
                            <div class="flex items-center justify-between p-2 border-b border-gray-100 text-xs">
                                <div class="flex-1">
                                    <p class="font-semibold text-dark">{{ $att->staff->user->first_name ?? 'N/A' }} {{ $att->staff->user->last_name ?? '' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $att->staff->staff_type ?? 'Staff' }}</p>
                                    @if($att->late_minutes)
                                    <p class="text-[10px] text-warning mt-0.5">⏱ {{ $att->late_minutes }} min late</p>
                                    @endif
                                </div>
                                @php
                                    $statusColors = [
                                        'present' => ['bg' => 'bg-success/10', 'text' => 'text-success'],
                                        'late' => ['bg' => 'bg-warning/10', 'text' => 'text-warning'],
                                        'absent' => ['bg' => 'bg-danger/10', 'text' => 'text-danger'],
                                        'on_leave' => ['bg' => 'bg-primary/10', 'text' => 'text-primary'],
                                    ];
                                    $color = $statusColors[$att->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
                                @endphp
                                <span class="font-bold {{ $color['text'] }} uppercase text-[10px] {{ $color['bg'] }} px-2 py-0.5 rounded">{{ str_replace('_', ' ', $att->status) }}</span>
                            </div>
                            @empty
                            <p class="text-gray-400 text-center py-4 text-xs">No records for this date</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Enable/disable late minutes input based on status selection
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const index = this.dataset.index;
        const lateInput = document.getElementById(`late_${index}`);
        
        if (this.value === 'late') {
            lateInput.disabled = false;
            lateInput.focus();
        } else {
            lateInput.disabled = true;
            lateInput.value = '';
        }
    });
});
</script>
@endsection
