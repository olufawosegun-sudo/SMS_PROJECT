@extends('layouts.app')

@section('title', 'Add New Teacher')

@section('body')
<div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-8">
            {{-- Page Header --}}
            <div class="mb-6 md:mb-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-2">
                    <a href="{{ route('teachers.index') }}" class="w-12 h-12 rounded-2xl bg-white shadow-lg flex items-center justify-center hover:shadow-xl hover:scale-105 transition-all duration-300">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-1">Add New Teacher</h1>
                        <p class="text-sm md:text-base text-gray-600">Create a comprehensive teacher profile and account</p>
                    </div>
                </div>
            </div>

            {{-- Registration Form --}}
            <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid lg:grid-cols-3 gap-6 lg:gap-8">
                    {{-- Main Form --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Personal Information Section --}}
                        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                                    <p class="text-sm text-gray-500 mt-1">Teacher's basic identity and contact details</p>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 placeholder-gray-400"
                                           placeholder="Enter first name">
                                    @error('first_name')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 placeholder-gray-400"
                                           placeholder="Enter last name">
                                    @error('last_name')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 placeholder-gray-400"
                                           placeholder="teacher@school.com">
                                    @error('email')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" required
                                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 placeholder-gray-400"
                                           placeholder="+234 800 000 0000">
                                    @error('phone')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        Gender <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select name="gender" required
                                                class="w-full px-4 py-3.5 pr-12 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 appearance-none bg-white cursor-pointer">
                                            <option value="">Choose Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('gender')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        Date of Birth <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200">
                                    @error('date_of_birth')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Employment Information Section --}}
                        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900">Employment Information</h3>
                                    <p class="text-sm text-gray-500 mt-1">Professional qualifications and work details</p>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Department
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <select name="department_id"
                                                class="w-full px-4 py-3.5 pr-12 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200 appearance-none bg-white cursor-pointer">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('department_id')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                    @if($departments->isEmpty())
                                    <p class="text-xs text-amber-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        No departments available
                                    </p>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            Campus / Branch
                                        </span>
                                    </label>
                                    @if(isset($branches) && $branches->count() > 0)
                                    <div class="relative">
                                        <select name="school_branch_id"
                                                class="w-full px-4 py-3.5 pr-12 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200 appearance-none bg-white cursor-pointer">
                                            <option value="">Main Campus / All Branches</option>
                                            @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ old('school_branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @else
                                    <input type="text" name="custom_branch" value="{{ old('custom_branch') }}" placeholder="e.g. Main Campus, Ikeja Branch, Victoria Island Campus"
                                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200 placeholder-gray-400">
                                    @endif
                                    @error('school_branch_id')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                    @error('custom_branch')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                            Qualification
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <select name="qualification"
                                                class="w-full px-4 py-3.5 pr-12 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200 appearance-none bg-white cursor-pointer">
                                            <option value="">Select Qualification</option>
                                            @foreach([
                                                'NCE' => 'NCE — Nigeria Certificate in Education',
                                                'OND' => 'OND — Ordinary National Diploma',
                                                'HND' => 'HND — Higher National Diploma',
                                                'B.Ed' => 'B.Ed — Bachelor of Education',
                                                'B.Sc' => 'B.Sc — Bachelor of Science',
                                                'B.Sc. Ed' => 'B.Sc. Ed — Bachelor of Science in Education',
                                                'B.A' => 'B.A — Bachelor of Arts',
                                                'B.A. Ed' => 'B.A. Ed — Bachelor of Arts in Education',
                                                'B.Tech' => 'B.Tech — Bachelor of Technology',
                                                'PGDE' => 'PGDE — Postgraduate Diploma in Education',
                                                'M.Ed' => 'M.Ed — Master of Education',
                                                'M.Sc' => 'M.Sc — Master of Science',
                                                'M.A' => 'M.A — Master of Arts',
                                                'MBA' => 'MBA — Master of Business Administration',
                                                'Ph.D' => 'Ph.D — Doctor of Philosophy',
                                                'Other' => 'Other',
                                            ] as $value => $label)
                                            <option value="{{ $value }}" {{ old('qualification') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('qualification')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Employment Date
                                        </span>
                                    </label>
                                    <input type="date" name="employment_date" value="{{ old('employment_date', date('Y-m-d')) }}"
                                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200">
                                    @error('employment_date')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Monthly Salary (Optional)
                                        </span>
                                    </label>
                                    <input type="number" name="salary" value="{{ old('salary') }}" step="0.01" 
                                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200 placeholder-gray-400"
                                           placeholder="0.00">
                                    @error('salary')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Employment Status
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <select name="status"
                                                class="w-full px-4 py-3.5 pr-12 rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 transition-all duration-200 appearance-none bg-white cursor-pointer">
                                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>✅ Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>⏸️ Inactive</option>
                                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>⛔ Suspended</option>
                                            <option value="resigned" {{ old('status') == 'resigned' ? 'selected' : '' }}>👋 Resigned</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('status')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Subject & Class Assignment Section --}}
                        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900">Subject & Class Assignments</h3>
                                        <p class="text-sm text-gray-500 mt-1">Assign teaching responsibilities</p>
                                    </div>
                                </div>
                                <button type="button" onclick="addSubjectAssignment()"
                                        class="px-5 py-2.5 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl hover:from-purple-600 hover:to-pink-700 transition-all duration-200 text-sm font-bold flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Assignment
                                </button>
                            </div>

                            <div id="subjectAssignments" class="space-y-4">
                                {{-- Subject assignment rows will be added here dynamically --}}
                            </div>

                            @if($classes->isEmpty() || $subjects->isEmpty())
                            <div class="mt-4 p-5 bg-amber-50 border-2 border-amber-200 rounded-2xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-bold text-amber-900">Missing Required Data</p>
                                        <p class="text-xs text-amber-700 mt-1">
                                            @if($classes->isEmpty() && $subjects->isEmpty())
                                                No classes or subjects have been created yet. Please create classes and subjects first.
                                            @elseif($classes->isEmpty())
                                                No classes have been created yet. Please create classes first.
                                            @else
                                                No subjects have been created yet. Please create subjects first.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($currentSession && $currentTerm)
                            <div class="mt-4 p-5 bg-blue-50 border-2 border-blue-200 rounded-2xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-blue-900">Current Academic Period</p>
                                        <div class="mt-2 space-y-1 text-xs text-blue-800">
                                            <p><span class="font-semibold">Session:</span> {{ $currentSession->name }}</p>
                                            <p><span class="font-semibold">Term:</span> {{ $currentTerm->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Profile Photo --}}
                        <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                            <h3 class="text-lg font-bold text-gray-900 mb-6">Profile Photo</h3>
                            <div class="text-center">
                                <div class="w-40 h-40 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden shadow-inner" id="photoPreview">
                                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" class="hidden">
                                <button type="button" onclick="document.getElementById('profilePhotoInput').click()"
                                        class="w-full px-5 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-bold shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Choose Photo
                                </button>
                                <p class="text-xs text-gray-500 mt-3">JPG, PNG (Max: 2MB)</p>
                                @error('profile_photo')
                                <p class="text-xs text-red-600 mt-2 flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                            <h3 class="text-lg font-bold text-gray-900 mb-6">Actions</h3>
                            <div class="space-y-3">
                                <button type="submit"
                                        class="w-full px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 font-bold shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Create Teacher Account
                                </button>
                                <a href="{{ route('teachers.index') }}"
                                   class="block w-full px-6 py-4 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200 font-bold text-center shadow hover:shadow-md">
                                    Cancel
                                </a>
                            </div>
                            
                            <div class="mt-6 p-5 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs font-bold text-blue-900">Default Login Credentials</p>
                                        <p class="text-xs text-blue-700 mt-2"><strong>Password:</strong> password123</p>
                                        <p class="text-xs text-blue-600 mt-1">Teacher must change password after first login</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
// Photo Preview with Animation
document.getElementById('profilePhotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').innerHTML = 
                '<img src="' + e.target.result + '" class="w-full h-full object-cover animate-fade-in">';
        };
        reader.readAsDataURL(file);
    }
});

// Subject Assignment Management with Enhanced Functionality
let assignmentIndex = 0;
const classes = @json($classes ?? []);
const subjects = @json($subjects ?? []);

function addSubjectAssignment() {
    if (classes.length === 0 || subjects.length === 0) {
        alert('Please create classes and subjects first before assigning them to teachers.');
        return;
    }

    const container = document.getElementById('subjectAssignments');
    const assignmentRow = document.createElement('div');
    assignmentRow.className = 'assignment-row p-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-purple-300 transition-all duration-300 transform hover:scale-[1.02] animate-slide-in';
    assignmentRow.id = 'assignment-' + assignmentIndex;
    
    const classOptions = classes.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
    const subjectOptions = subjects.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
    
    assignmentRow.innerHTML = `
        <div class="flex flex-col md:flex-row items-start gap-4">
            <div class="flex-1 grid sm:grid-cols-2 gap-4 w-full">
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Class <span class="text-red-500">*</span>
                    </label>
                    <select name="subject_assignments[${assignmentIndex}][class_id]" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 text-sm appearance-none bg-white cursor-pointer">
                        <option value="">Select Class</option>
                        ${classOptions}
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Subject <span class="text-red-500">*</span>
                    </label>
                    <select name="subject_assignments[${assignmentIndex}][subject_id]" required
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-300 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-200 text-sm appearance-none bg-white cursor-pointer">
                        <option value="">Select Subject</option>
                        ${subjectOptions}
                    </select>
                </div>
            </div>
            <button type="button" onclick="removeSubjectAssignment(${assignmentIndex})"
                    class="mt-0 md:mt-7 p-3 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all duration-200 shadow hover:shadow-md transform hover:scale-110"
                    title="Remove Assignment">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    `;
    
    container.appendChild(assignmentRow);
    assignmentIndex++;
    
    // Add smooth scroll to the new assignment
    setTimeout(() => {
        assignmentRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 100);
}

function removeSubjectAssignment(index) {
    const element = document.getElementById('assignment-' + index);
    if (element) {
        // Add fade-out animation
        element.style.opacity = '0';
        element.style.transform = 'scale(0.95)';
        setTimeout(() => {
            element.remove();
        }, 300);
    }
}

// Add one assignment by default if data is available
window.addEventListener('DOMContentLoaded', function() {
    if (classes.length > 0 && subjects.length > 0) {
        addSubjectAssignment();
    }
});

// Add smooth animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slide-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    
    .assignment-row {
        transition: all 0.3s ease;
    }
    
    /* Form Focus States */
    input:focus, select:focus, textarea:focus {
        outline: none;
    }
`;
document.head.appendChild(style);
</script>
@endsection
