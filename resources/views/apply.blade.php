@extends('layouts.app')

@section('title', 'Apply for Admission')

@section('body')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
    {{-- Modern Header --}}
    <header class="bg-white/80 backdrop-blur-lg border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center shadow-lg shadow-primary/25 group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        <span class="text-2xl font-bold text-gray-900">Edu<span class="text-primary">West</span></span>
                        <span class="block text-xs text-gray-500 -mt-1">Africa</span>
                    </div>
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('landing') }}" class="text-sm font-semibold text-gray-600 hover:text-primary transition-colors">Home</a>
                    <span class="px-4 py-2 bg-primary/10 text-primary rounded-full text-xs font-bold">Apply Now</span>
                </div>
            </div>
        </div>
    </header>

    @if(session('success_application'))
        {{-- Success State --}}
        <div class="min-h-[calc(100vh-5rem)] flex items-center justify-center p-4 sm:p-6">
            <div class="max-w-2xl w-full">
                <div class="bg-white rounded-3xl shadow-2xl shadow-success/10 overflow-hidden border border-success/20">
                    {{-- Success Header --}}
                    <div class="bg-gradient-to-r from-success via-success-dark to-success p-8 sm:p-12 text-center relative overflow-hidden">
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full"></div>
                            <div class="absolute bottom-10 right-10 w-40 h-40 bg-white rounded-full"></div>
                        </div>
                        <div class="relative">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Application Submitted Successfully! 🎉</h2>
                            <p class="text-white/90 text-sm sm:text-base">Your application for <strong>{{ session('success_application')['student_name'] }}</strong></p>
                        </div>
                    </div>

                    {{-- Success Body --}}
                    <div class="p-6 sm:p-10">
                        <div class="bg-gradient-to-br from-primary/5 to-success/5 rounded-2xl p-6 sm:p-8 mb-8 border border-primary/10">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Application Reference</span>
                                    <span class="block text-xl sm:text-2xl font-black text-primary">{{ session('success_application')['application_no'] }}</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs sm:text-sm text-blue-900">
                                    A confirmation email with full details has been sent to <strong>{{ session('success_application')['guardian_email'] }}</strong>. 
                                    Please check your inbox and spam folder.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4 mb-8">
                            <h3 class="text-lg font-bold text-gray-900">What Happens Next?</h3>
                            <div class="space-y-3">
                                @foreach([
                                    ['number' => '1', 'title' => 'Application Review', 'desc' => 'Our admissions team will review your application and documents within 3-5 business days.'],
                                    ['number' => '2', 'title' => 'Decision Notification', 'desc' => 'You will receive an email with our admission decision and next steps.'],
                                    ['number' => '3', 'title' => 'Enrollment Process', 'desc' => 'If accepted, complete the enrollment by submitting required fees and documents.']
                                ] as $step)
                                <div class="flex gap-4 group">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center flex-shrink-0 text-white font-bold shadow-lg shadow-primary/25 group-hover:scale-110 transition-transform">
                                        {{ $step['number'] }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 mb-1">{{ $step['title'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $step['desc'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('apply') }}" class="flex-1 py-4 px-6 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold text-center shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/30 transition-all">
                                Apply for Another Child
                            </a>
                            <a href="{{ route('landing') }}" class="flex-1 py-4 px-6 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-xl font-semibold text-center transition-all">
                                Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Application Form --}}
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            {{-- Hero Section --}}
            <div class="text-center mb-8 sm:mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 rounded-full mb-4">
                    <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                    <span class="text-xs sm:text-sm font-semibold text-primary">Online Admission Portal</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 mb-4 leading-tight">
                    Begin Your Child's <br class="hidden sm:block"/>
                    <span class="bg-gradient-to-r from-primary via-primary-dark to-success bg-clip-text text-transparent">Academic Journey</span>
                </h1>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                    Complete the application form below to apply for admission. All fields marked with <span class="text-danger">*</span> are required.
                </p>
            </div>

            {{-- Progress Steps --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-8">
                <div class="flex items-center justify-between">
                    @foreach([
                        ['number' => '1', 'title' => 'School & Class'],
                        ['number' => '2', 'title' => 'Student Info'],
                        ['number' => '3', 'title' => 'Guardian Details'],
                        ['number' => '4', 'title' => 'Documents']
                    ] as $index => $step)
                    <div class="flex items-center flex-1">
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm sm:text-base border-2 border-primary/20">
                                {{ $step['number'] }}
                            </div>
                            <span class="text-xs sm:text-sm font-medium text-gray-600 mt-2 hidden sm:block">{{ $step['title'] }}</span>
                        </div>
                        @if($index < 3)
                        <div class="w-full h-0.5 bg-gray-200 mx-2"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('apply') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Step 1: School Selection --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary/10 to-primary/5 flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">School & Class Selection</h3>
                            <p class="text-xs text-gray-500">Choose your preferred institution and class level</p>
                        </div>
                    </div>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Target Institution <span class="text-danger">*</span></label>
                            <select name="school_id" id="school_id" required class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all @error('school_id') border-danger @enderror">
                                <option value="">Select a school...</option>
                                @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }} ({{ $school->city }}, {{ $school->state }})
                                </option>
                                @endforeach
                            </select>
                            @error('school_id')
                            <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Applied Class <span class="text-danger">*</span></label>
                            <select name="applied_class_id" id="applied_class_id" required class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all @error('applied_class_id') border-danger @enderror">
                                <option value="">Select class level...</option>
                                @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" data-school="{{ $cls->school_id }}" {{ old('applied_class_id') == $cls->id ? 'selected' : '' }}>
                                    {{ $cls->name }} - {{ $cls->level }}
                                </option>
                                @endforeach
                            </select>
                            @error('applied_class_id')
                            <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Step 2: Student Information --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-success/10 to-success/5 flex items-center justify-center">
                            <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Student Information</h3>
                            <p class="text-xs text-gray-500">Provide the applicant's personal details</p>
                        </div>
                    </div>
                    
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="e.g. Amina" class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all placeholder:text-gray-400 @error('first_name') border-danger @enderror">
                                @error('first_name')
                                <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="e.g. Mensah" class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all placeholder:text-gray-400 @error('last_name') border-danger @enderror">
                                @error('last_name')
                                <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Other Name <span class="text-gray-400 text-xs">(Optional)</span></label>
                            <input type="text" name="other_name" value="{{ old('other_name') }}" placeholder="Middle or other name" class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all placeholder:text-gray-400">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Gender <span class="text-danger">*</span></label>
                                <select name="gender" required class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all @error('gender') border-danger @enderror">
                                    <option value="">Select gender...</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="dob" value="{{ old('dob') }}" required class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all @error('dob') border-danger @enderror">
                                @error('dob')
                                <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Previous School <span class="text-gray-400 text-xs">(Optional)</span></label>
                            <input type="text" name="previous_school" value="{{ old('previous_school') }}" placeholder="Name of previous school attended" class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all placeholder:text-gray-400">
                        </div>
                    </div>
                </div>

                {{-- Step 3: Guardian Details --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/10 to-blue-500/5 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Guardian Details</h3>
                            <p class="text-xs text-gray-500">Parent or legal guardian contact information</p>
                        </div>
                    </div>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Guardian Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" required placeholder="e.g. Mr. John Mensah" class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all placeholder:text-gray-400 @error('guardian_name') border-danger @enderror">
                            @error('guardian_name')
                            <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="guardian_email" value="{{ old('guardian_email') }}" required placeholder="parent@example.com" class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all placeholder:text-gray-400 @error('guardian_email') border-danger @enderror">
                                @error('guardian_email')
                                <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="guardian_phone" value="{{ old('guardian_phone') }}" required placeholder="+234 803 123 4567" class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all placeholder:text-gray-400 @error('guardian_phone') border-danger @enderror">
                                @error('guardian_phone')
                                <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Residential Address <span class="text-danger">*</span></label>
                            <textarea name="address" rows="3" required placeholder="Home or residential address" class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 bg-white hover:border-primary/50 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all placeholder:text-gray-400 resize-none @error('address') border-danger @enderror">{{ old('address') }}</textarea>
                            @error('address')
                            <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Step 4: Required Documents --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/10 to-amber-500/5 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Required Documents</h3>
                            <p class="text-xs text-gray-500">Upload clear copies (PDF, JPG, PNG - Max 2MB each)</p>
                        </div>
                    </div>
                    
                    <div class="space-y-5">
                        {{-- Birth Certificate --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Birth Certificate <span class="text-danger">*</span></label>
                            <div class="relative">
                                <input type="file" name="birth_certificate" id="birth_certificate" accept=".pdf,.jpg,.jpeg,.png" required class="hidden" onchange="updateFileName(this, 'birth_cert_name')">
                                <label for="birth_certificate" class="flex items-center gap-4 w-full px-4 py-4 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:border-primary hover:bg-primary/5 cursor-pointer transition-all group @error('birth_certificate') border-danger bg-danger/5 @enderror">
                                    <div class="w-12 h-12 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0 group-hover:border-primary group-hover:bg-primary/10 transition-colors">
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-semibold text-gray-900 group-hover:text-primary transition-colors" id="birth_cert_name">Click to upload birth certificate</span>
                                        <span class="block text-xs text-gray-500 mt-1">PDF, JPG or PNG (max 2MB)</span>
                                    </div>
                                </label>
                            </div>
                            @error('birth_certificate')
                            <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Passport Photograph --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Passport Photograph <span class="text-danger">*</span></label>
                            <div class="relative">
                                <input type="file" name="passport_photo" id="passport_photo" accept=".jpg,.jpeg,.png" required class="hidden" onchange="updateFileName(this, 'passport_photo_name')">
                                <label for="passport_photo" class="flex items-center gap-4 w-full px-4 py-4 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:border-primary hover:bg-primary/5 cursor-pointer transition-all group @error('passport_photo') border-danger bg-danger/5 @enderror">
                                    <div class="w-12 h-12 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0 group-hover:border-primary group-hover:bg-primary/10 transition-colors">
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-semibold text-gray-900 group-hover:text-primary transition-colors" id="passport_photo_name">Click to upload passport photo</span>
                                        <span class="block text-xs text-gray-500 mt-1">JPG or PNG (max 2MB)</span>
                                    </div>
                                </label>
                            </div>
                            @error('passport_photo')
                            <p class="text-danger text-xs mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Previous School Report --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Previous School Report <span class="text-gray-400 text-xs">(Optional)</span></label>
                            <div class="relative">
                                <input type="file" name="school_report" id="school_report" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="updateFileName(this, 'school_report_name')">
                                <label for="school_report" class="flex items-center gap-4 w-full px-4 py-4 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:border-primary hover:bg-primary/5 cursor-pointer transition-all group">
                                    <div class="w-12 h-12 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0 group-hover:border-primary group-hover:bg-primary/10 transition-colors">
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-semibold text-gray-900 group-hover:text-primary transition-colors" id="school_report_name">Click to upload report card</span>
                                        <span class="block text-xs text-gray-500 mt-1">PDF, JPG or PNG (max 2MB)</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Medical Certificate --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Medical Fitness Certificate <span class="text-gray-400 text-xs">(Optional)</span></label>
                            <div class="relative">
                                <input type="file" name="medical_certificate" id="medical_certificate" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="updateFileName(this, 'medical_cert_name')">
                                <label for="medical_certificate" class="flex items-center gap-4 w-full px-4 py-4 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 hover:border-primary hover:bg-primary/5 cursor-pointer transition-all group">
                                    <div class="w-12 h-12 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0 group-hover:border-primary group-hover:bg-primary/10 transition-colors">
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-semibold text-gray-900 group-hover:text-primary transition-colors" id="medical_cert_name">Click to upload medical certificate</span>
                                        <span class="block text-xs text-gray-500 mt-1">PDF, JPG or PNG (max 2MB)</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Info Notice --}}
                        <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-blue-900 leading-relaxed">All documents must be clear and legible. Supported formats: PDF, JPG, PNG. Maximum file size: 2MB per document.</p>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4">
                    <button type="submit" class="w-full py-4 sm:py-5 bg-gradient-to-r from-primary via-primary-dark to-primary hover:from-primary-dark hover:via-primary hover:to-primary-dark text-white font-bold text-base sm:text-lg rounded-xl shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/35 transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-3 group">
                        <span>Submit Admission Application</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                    <p class="text-center text-xs text-gray-500 mt-4">By submitting, you agree to provide accurate information for admission processing</p>
                </div>
            </form>
        @endif
    </div>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 py-6 mt-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
                <span>&copy; {{ date('Y') }} EduWest Africa. All Rights Reserved.</span>
                <div class="flex items-center gap-4">
                    <a href="{{ route('landing') }}" class="hover:text-primary transition-colors font-semibold">Home</a>
                    <span class="text-gray-300">|</span>
                    <a href="#" class="hover:text-primary transition-colors font-semibold">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>
</div>

@push('scripts')
<script>
// Update filename display when file is selected
function updateFileName(input, displayId) {
    const display = document.getElementById(displayId);
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
        display.innerHTML = `<span class="text-primary font-semibold">${fileName}</span> <span class="text-gray-400">(${fileSize} MB)</span>`;
        display.closest('label').classList.add('border-primary', 'bg-primary/5');
        display.closest('label').classList.remove('border-gray-300');
    }
}

// Filter classes by selected school
document.getElementById('school_id').addEventListener('change', function() {
    const schoolId = this.value;
    const classSelect = document.getElementById('applied_class_id');
    const options = classSelect.querySelectorAll('option');
    
    classSelect.value = '';
    
    options.forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
            return;
        }
        
        const optionSchool = option.getAttribute('data-school');
        if (optionSchool === schoolId) {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });
});

// Trigger on page load if school is already selected (for validation errors)
if (document.getElementById('school_id').value) {
    document.getElementById('school_id').dispatchEvent(new Event('change'));
}
</script>
@endpush
@endsection
