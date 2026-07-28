@extends('layouts.app')
@section('title', 'Academic Timetable')
@section('body')
@php $userRole = Auth::user()->role->name; @endphp
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar')
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">

            @if(in_array($userRole, ['Student', 'Guardian']))
            {{-- ===================== STUDENT VIEW ===================== --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">📅 Class Timetable</h1>
                <p class="text-gray-500">View your daily class schedule</p>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Class Filter for Student --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <form method="GET" action="{{ route('timetables.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Select Your Class</label>
                        <select name="class_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                            <option value="">Select Class to View Timetable</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">View Schedule</button>
                </form>
            </div>

            {{-- Timetable Display (Read-Only for Students) --}}
            <div class="space-y-6">
                @if($selectedClassId)
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                    <div class="bg-white rounded-2xl p-5 border border-gray-100">
                        <h4 class="text-sm font-bold text-primary mb-3 uppercase tracking-wider">{{ $day }}</h4>
                        <div class="space-y-2">
                            @forelse($timetableByDay->get($day, []) as $slot)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-150 text-xs">
                                <div>
                                    <p class="font-bold text-dark text-sm">{{ $slot->subject->name }} ({{ $slot->subject->code }})</p>
                                    <p class="text-gray-400 mt-0.5">Teacher: {{ $slot->teacher->user->first_name ?? 'N/A' }} {{ $slot->teacher->user->last_name ?? '' }}</p>
                                </div>
                                <span class="font-bold text-gray-700 bg-white border border-gray-200 px-2.5 py-1 rounded-md shadow-sm">
                                    {{ date('h:i A', strtotime($slot->start_time)) }} - {{ date('h:i A', strtotime($slot->end_time)) }}
                                </span>
                            </div>
                            @empty
                            <p class="text-gray-400 italic text-xs py-1">No periods scheduled.</p>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm font-semibold mb-1">Select Your Class</p>
                    <p class="text-xs">Please select your class from the dropdown above to view your timetable.</p>
                </div>
                @endif
            </div>

            @else
            {{-- ===================== ADMIN / TEACHER VIEW ===================== --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Class Timetables</h1>
                    <p class="text-gray-500">Add schedule slots and filter by classroom level</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Filters --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <form method="GET" action="{{ route('timetables.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Class Filter</label>
                        <select name="class_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                            <option value="">Select Class to View Timetable</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">View Schedule</button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Create entry --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Add Class Period</h3>
                    <form method="POST" action="{{ route('timetables.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Class</label>
                            <select name="class_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                                <option value="">Select Class</option>
                                @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Subject</label>
                            <select name="subject_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Teacher</label>
                            <select name="teacher_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->user->first_name }} {{ $t->user->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Day</label>
                                <select name="day" required class="w-full px-2 py-2 border border-gray-200 rounded-lg text-xs">
                                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday'] as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Start Time</label>
                                <input type="time" name="start_time" required class="w-full px-2 py-2 border border-gray-200 rounded-lg text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">End Time</label>
                                <input type="time" name="end_time" required class="w-full px-2 py-2 border border-gray-200 rounded-lg text-xs">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Schedule Slot</button>
                    </form>
                </div>

                {{-- Right: Timetable Schedule --}}
                <div class="lg:col-span-2 space-y-6">
                    @if($selectedClassId)
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                        <div class="bg-white rounded-2xl p-5 border border-gray-100">
                            <h4 class="text-sm font-bold text-primary mb-3 uppercase tracking-wider">{{ $day }}</h4>
                            <div class="space-y-2">
                                @forelse($timetableByDay->get($day, []) as $slot)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-150 text-xs">
                                    <div>
                                        <p class="font-bold text-dark text-sm">{{ $slot->subject->name }} ({{ $slot->subject->code }})</p>
                                        <p class="text-gray-400 mt-0.5">Teacher: {{ $slot->teacher->user->first_name ?? 'N/A' }} {{ $slot->teacher->user->last_name ?? '' }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="font-bold text-gray-700 bg-white border border-gray-200 px-2.5 py-1 rounded-md shadow-sm">
                                            {{ date('h:i A', strtotime($slot->start_time)) }} - {{ date('h:i A', strtotime($slot->end_time)) }}
                                        </span>
                                        <form method="POST" action="{{ route('timetables.destroy', $slot->id) }}" onsubmit="return confirm('Delete slot?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-danger hover:text-danger-dark font-bold">✕</button>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <p class="text-gray-400 italic text-xs py-1">No periods scheduled.</p>
                                @endforelse
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center text-gray-400">
                        <p class="text-sm">Please select a class from the filter dropdown above to view its timetable.</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
