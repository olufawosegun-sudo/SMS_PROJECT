@extends('layouts.app')

@section('title', 'Teacher Details')

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            {{-- Page Header --}}
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('teachers.index') }}" class="w-12 h-12 rounded-xl bg-white shadow-lg flex items-center justify-center hover:shadow-xl hover:scale-105 transition-all">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-dark mb-2">Teacher Details</h1>
                        <p class="text-gray-500">View complete teacher profile information</p>
                    </div>
                </div>
                <a href="{{ route('teachers.edit', $teacher->id) }}"
                   class="px-6 py-3 bg-warning text-white rounded-xl hover:bg-warning/90 transition-colors font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Teacher
                </a>
            </div>

            <div class="grid lg:grid-cols-3 gap-6">
                {{-- Profile Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                        <div class="text-center">
                            <div class="w-32 h-32 mx-auto mb-4 rounded-2xl bg-info/10 flex items-center justify-center overflow-hidden">
                                @if($teacher->user->profile_photo)
                                <img src="{{ asset('storage/' . $teacher->user->profile_photo) }}" class="w-full h-full object-cover">
                                @else
                                <span class="text-4xl font-bold text-info">{{ substr($teacher->user->first_name, 0, 1) }}{{ substr($teacher->user->last_name, 0, 1) }}</span>
                                @endif
                            </div>
                            <h2 class="text-2xl font-bold text-dark mb-1">{{ $teacher->user->first_name }} {{ $teacher->user->last_name }}</h2>
                            <p class="text-sm text-gray-500 mb-4">{{ $teacher->user->email }}</p>
                            
                            @php
                                $statusColors = [
                                    'active' => 'success',
                                    'inactive' => 'warning',
                                    'suspended' => 'danger',
                                    'resigned' => 'gray'
                                ];
                                $color = $statusColors[$teacher->status] ?? 'gray';
                            @endphp
                            <span class="inline-block px-4 py-2 text-sm font-bold bg-{{ $color }}/10 text-{{ $color }} rounded-full capitalize">
                                {{ $teacher->status }}
                            </span>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Staff No:</span>
                                <span class="font-bold text-dark">{{ $teacher->staff_no }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Gender:</span>
                                <span class="font-semibold text-dark capitalize">{{ $teacher->user->gender }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Phone:</span>
                                <span class="font-semibold text-dark">{{ $teacher->user->phone }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-500">Date of Birth:</span>
                                <span class="font-semibold text-dark">{{ $teacher->user->date_of_birth ? date('M d, Y', strtotime($teacher->user->date_of_birth)) : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Employment Information --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                        <h3 class="text-xl font-bold text-dark mb-6 pb-4 border-b border-gray-100">Employment Information</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Department</label>
                                <p class="text-base font-semibold text-dark">{{ $teacher->department->name ?? 'Not Assigned' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Qualification</label>
                                <p class="text-base font-semibold text-dark">{{ $teacher->qualification ?? 'Not Specified' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Employment Date</label>
                                <p class="text-base font-semibold text-dark">{{ $teacher->employment_date ? date('M d, Y', strtotime($teacher->employment_date)) : 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Monthly Salary</label>
                                <p class="text-base font-semibold text-dark">{{ $teacher->salary ? '₦' . number_format($teacher->salary, 2) : 'Not Set' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Subject Assignments --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                        <h3 class="text-xl font-bold text-dark mb-6 pb-4 border-b border-gray-100">Subject & Class Assignments</h3>
                        @if($teacher->teacherSubjects->count() > 0)
                        <div class="space-y-3">
                            @foreach($teacher->teacherSubjects as $assignment)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-dark">{{ $assignment->subject->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $assignment->schoolClass->name }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold bg-success/10 text-success rounded-full">Assigned</span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-gray-500 font-semibold">No subject assignments yet</p>
                        </div>
                        @endif
                    </div>

                    {{-- Account Information --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                        <h3 class="text-xl font-bold text-dark mb-6 pb-4 border-b border-gray-100">Account Information</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Account Created</label>
                                <p class="text-base font-semibold text-dark">{{ $teacher->created_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Last Updated</label>
                                <p class="text-base font-semibold text-dark">{{ $teacher->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
