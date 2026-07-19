@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('body')
<div class="min-h-screen bg-gray-50">
    {{-- Top Navigation --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-dark">{{ $school->name }}</h1>
                            <p class="text-xs text-gray-500">Teacher Dashboard</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Notifications --}}
                    <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    {{-- User Profile --}}
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-dark">{{ $user->first_name }} {{ $user->last_name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->role->name ?? 'Teacher' }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                    </div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
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

            {{-- Welcome Section --}}
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-dark mb-2">Welcome back, {{ $user->first_name }}! 👋</h2>
                <p class="text-gray-600">Here's what's happening in your classes today.</p>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">My Classes</p>
                            <h3 class="text-3xl font-bold text-dark">{{ $user->teacher->teacherSubjects->unique('class_id')->count() ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">My Students</p>
                            <h3 class="text-3xl font-bold text-dark">0</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Subjects</p>
                            <h3 class="text-3xl font-bold text-dark">{{ $user->teacher->teacherSubjects->unique('subject_id')->count() ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Attendance Rate</p>
                            <h3 class="text-3xl font-bold text-dark">0%</h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- My Classes --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-dark">My Classes & Subjects</h3>
                        </div>
                        <div class="p-6">
                            @if($user->teacher && $user->teacher->teacherSubjects->count() > 0)
                                <div class="space-y-4">
                                    @foreach($user->teacher->teacherSubjects as $assignment)
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-dark">{{ $assignment->subject->name ?? 'N/A' }}</h4>
                                                    <p class="text-sm text-gray-600">{{ $assignment->schoolClass->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <button class="px-4 py-2 text-sm font-medium text-primary hover:bg-primary hover:text-white rounded-lg border border-primary transition">
                                                View Details
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <p class="text-gray-500">No classes assigned yet</p>
                                    <p class="text-sm text-gray-400 mt-1">Contact your administrator to get assigned to classes</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Today's Schedule --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-dark">Today's Schedule</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-500">No classes scheduled for today</p>
                            </div>
                        </div>
                    </div>

                    {{-- Student Documents (View Only) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-dark">Student Documents</h3>
                                    <p class="text-sm text-gray-500 mt-1">Documents from your class students</p>
                                </div>
                            </div>
                        </div>

                        {{-- Document Stats --}}
                        <div class="p-6 border-b border-gray-100">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-8 h-8 bg-blue-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-2xl font-bold text-dark mb-1">{{ $documentStats['total_students'] ?? 0 }}</p>
                                    <p class="text-xs text-gray-600">Your Students</p>
                                </div>

                                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-8 h-8 bg-yellow-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-2xl font-bold text-dark mb-1">{{ $documentStats['missing_documents'] ?? 0 }}</p>
                                    <p class="text-xs text-gray-600">Missing Birth Cert</p>
                                </div>
                            </div>
                        </div>

                        {{-- Recent Documents List --}}
                        <div class="p-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Recent Documents</h4>
                            <div class="space-y-3">
                                @forelse($recentDocuments as $document)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm flex-shrink-0">
                                            {{ substr($document->student->user->first_name ?? 'S', 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-dark truncate">{{ $document->student->user->first_name ?? 'N/A' }} {{ $document->student->user->last_name ?? '' }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $document->getDocumentTypeLabel() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0 ml-2">
                                        <a href="{{ route('student-documents.view', $document->id) }}" class="p-1.5 text-info hover:bg-info/10 rounded transition" title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('student-documents.download', $document->id) }}" class="p-1.5 text-primary hover:bg-primary/10 rounded transition" title="Download">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">No documents yet</p>
                                    <p class="text-xs text-gray-400 mt-1">Documents will appear when your students enroll</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-8">
                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-dark">Quick Actions</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <button class="w-full flex items-center gap-3 p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="font-medium text-dark">Mark Attendance</span>
                            </button>

                            <button class="w-full flex items-center gap-3 p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <span class="font-medium text-dark">Create Assignment</span>
                            </button>

                            <button class="w-full flex items-center gap-3 p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                </div>
                                <span class="font-medium text-dark">Grade Assessments</span>
                            </button>

                            <button class="w-full flex items-center gap-3 p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="font-medium text-dark">View Timetable</span>
                            </button>
                        </div>
                    </div>

                    {{-- Profile Info --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-dark">Profile Information</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Staff Number</p>
                                <p class="font-semibold text-dark">{{ $user->teacher->staff_no ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Department</p>
                                <p class="font-semibold text-dark">{{ $user->teacher->department->name ?? 'Not Assigned' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Qualification</p>
                                <p class="font-semibold text-dark">{{ $user->teacher->qualification ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Employment Date</p>
                                <p class="font-semibold text-dark">{{ $user->teacher->employment_date ? \Carbon\Carbon::parse($user->teacher->employment_date)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold text-dark">{{ $user->email }}</p>
                                @if(!$user->email_verified_at)
                                    <p class="text-xs text-yellow-600 mt-1">⚠️ Email not verified</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
