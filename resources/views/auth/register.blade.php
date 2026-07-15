@extends('layouts.app')

@section('title', 'School Registration — EduWest Africa')

@section('body')
<div class="min-h-screen flex flex-col lg:flex-row">
    {{-- Left Panel: Luxury Branding & Features --}}
    <div class="hidden lg:flex lg:w-5/12 gradient-hero-luxury relative items-center justify-center p-12 overflow-hidden">
        {{-- Layered background effects --}}
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[400px] rounded-full blur-[140px] opacity-[0.08]" style="background: radial-gradient(circle, #D4A843, transparent 70%);"></div>
        <div class="absolute -bottom-20 -left-20 w-[500px] h-[400px] rounded-full blur-[120px] opacity-[0.1] animate-float" style="background: radial-gradient(ellipse, #1B6B3E, transparent 70%);"></div>
        <div class="absolute top-1/3 -right-10 w-[400px] h-[400px] rounded-full blur-[130px] opacity-[0.06] animate-float-delayed" style="background: radial-gradient(ellipse, #D4A843, transparent 70%);"></div>

        {{-- Diamond sparkle particles --}}
        @for($i = 0; $i < 12; $i++)
        <div class="absolute w-1 h-1 bg-accent/50 rotate-45" style="left: {{ rand(5, 95) }}%; top: {{ rand(5, 95) }}%; animation: diamond-sparkle {{ rand(25, 45) / 10 }}s ease-in-out infinite {{ rand(0, 30) / 10 }}s;"></div>
        @endfor

        {{-- Aurora wave bands --}}
        <div class="absolute top-[25%] left-0 w-full h-[1px] opacity-[0.05]" style="background: linear-gradient(90deg, transparent, #D4A843 30%, #1B6B3E 70%, transparent); filter: blur(2px);"></div>
        <div class="absolute top-[60%] left-0 w-full h-[1px] opacity-[0.04]" style="background: linear-gradient(90deg, transparent, #1B6B3E 40%, #D4A843 60%, transparent); filter: blur(2px);"></div>

        {{-- Noise texture --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.012] mix-blend-overlay" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 256 256%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22n%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22/%3E%3C/svg%3E'); background-size: 256px 256px;"></div>

        {{-- Gold edge accents --}}
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-accent/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-accent/15 to-transparent"></div>
        <div class="absolute top-0 right-0 w-px h-full bg-gradient-to-b from-transparent via-accent/10 to-transparent"></div>

        {{-- Vignette --}}
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(ellipse 80% 70% at 50% 50%, transparent 30%, rgba(5,5,16,0.4) 100%);"></div>

        <div class="relative z-10 max-w-md w-full">
            {{-- Logo --}}
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 mb-12">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-2xl" style="background: linear-gradient(135deg, #D4A843, #B8912E);">
                    <svg class="w-8 h-8 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="text-left">
                    <span class="text-3xl font-bold text-white">Edu<span class="text-accent">West</span></span>
                    <span class="block text-xs text-white/50 -mt-1 tracking-widest uppercase">Africa</span>
                </div>
            </a>

            <h1 class="text-3xl font-extrabold text-white mb-6 leading-tight">
                Establish Your School's <span class="text-gradient-gold">Digital Presence</span>
            </h1>
            <p class="text-white/50 leading-relaxed mb-10">
                Join primary and secondary schools across West Africa in standardizing administration, academics, payments, and communications.
            </p>

            {{-- Feature benefits --}}
            <div class="space-y-4">
                @foreach([
                    ['title' => 'Pan-African Standardization', 'desc' => 'Supports WAEC, NECO, and national grading structures.'],
                    ['title' => 'Robust Financial Control', 'desc' => 'Multi-currency invoicing with mobile money & bank transfers.'],
                    ['title' => 'Intelligent Grading', 'desc' => 'Instant terminal report cards & position generation.'],
                ] as $item)
                <div class="glass-luxury rounded-2xl p-4 flex gap-4 items-start">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background: linear-gradient(135deg, rgba(212,168,67,0.2), rgba(212,168,67,0.05));">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h4 class="text-sm font-bold text-white mb-0.5">{{ $item['title'] }}</h4>
                        <p class="text-xs text-white/40 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right Panel: Multi-Step Form --}}
    <div class="w-full lg:w-7/12 flex flex-col justify-center p-6 sm:p-12 md:p-16 bg-white min-h-screen">
        <div class="w-full max-w-xl mx-auto">
            {{-- Mobile Logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-dark">Edu<span class="text-primary">West</span> Africa</span>
            </div>

            {{-- Stepper Progress Bar --}}
            <div class="mb-10">
                <div class="flex items-center justify-between text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">
                    <span class="text-primary" id="step-indicator-text">Step 1: School Registration</span>
                    <span id="progress-text">33% Complete</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-primary transition-all duration-500 w-1/3" id="step-progress-bar"></div>
                </div>
                <div class="flex justify-between mt-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-primary" id="step-badge-1">
                        <span class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center text-xs">1</span>
                        <span>School Info</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-400" id="step-badge-2">
                        <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs">2</span>
                        <span>Owner Account</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-400" id="step-badge-3">
                        <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs">3</span>
                        <span>Additional Users</span>
                    </div>
                </div>
            </div>

            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-left">
                <div class="flex items-start gap-2 text-red-700">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm font-medium">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('register') }}" method="POST" id="multistep-form" class="space-y-6 text-left" enctype="multipart/form-data">
                @csrf

                {{-- STEP 1: SCHOOL INFO --}}
                <div id="step-1-container" class="space-y-5 transition-all duration-300">
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-dark mb-1">Create Your School Account</h2>
                        <p class="text-gray-500 text-sm">Register your school on EduWest Africa's management platform.</p>
                    </div>

                    {{-- School Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">School Name</label>
                        <input type="text" name="school_name" id="school_name" value="{{ old('school_name') }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                               placeholder="e.g. West African Excellence Academy">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- School Code --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">School Code / Abbr.</label>
                            <input type="text" name="school_code" id="school_code" value="{{ old('school_code') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="e.g. WAEA">
                        </div>

                        {{-- Country --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                            <select name="school_country" id="school_country" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="">Select country</option>
                                <option value="Nigeria" {{ old('school_country') == 'Nigeria' ? 'selected' : '' }}>Nigeria 🇳🇬</option>
                                <option value="Ghana" {{ old('school_country') == 'Ghana' ? 'selected' : '' }}>Ghana 🇬🇭</option>
                                <option value="Senegal" {{ old('school_country') == 'Senegal' ? 'selected' : '' }}>Senegal 🇸🇳</option>
                                <option value="Côte d'Ivoire" {{ old('school_country') == "Côte d'Ivoire" ? 'selected' : '' }}>Côte d'Ivoire 🇨🇮</option>
                                <option value="Liberia" {{ old('school_country') == 'Liberia' ? 'selected' : '' }}>Liberia 🇱🇷</option>
                                <option value="Sierra Leone" {{ old('school_country') == 'Sierra Leone' ? 'selected' : '' }}>Sierra Leone 🇸🇱</option>
                                <option value="Gambia" {{ old('school_country') == 'Gambia' ? 'selected' : '' }}>Gambia 🇬🇲</option>
                                <option value="Guinea" {{ old('school_country') == 'Guinea' ? 'selected' : '' }}>Guinea 🇬🇳</option>
                            </select>
                        </div>
                    </div>

                    {{-- School Email & Phone --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">School Email (Optional)</label>
                            <input type="email" name="school_email" id="school_email" value="{{ old('school_email') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="e.g. info@yourschool.edu">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">School Phone (Optional)</label>
                            <input type="text" name="school_phone" id="school_phone" value="{{ old('school_phone') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="e.g. +234 801 234 5678">
                        </div>
                    </div>

                    {{-- School Website --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">School Website (Optional)</label>
                        <input type="text" name="school_website" id="school_website" value="{{ old('school_website') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                               placeholder="e.g. https://www.yourschool.edu">
                    </div>

                    {{-- School Address --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">School Address (Optional)</label>
                        <input type="text" name="school_address" id="school_address" value="{{ old('school_address') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                               placeholder="e.g. 123 Education Avenue">
                    </div>

                    {{-- City & State --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">City (Optional)</label>
                            <input type="text" name="school_city" id="school_city" value="{{ old('school_city') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="e.g. Lagos">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">State/Region (Optional)</label>
                            <input type="text" name="school_state" id="school_state" value="{{ old('school_state') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="e.g. Lagos State">
                        </div>
                    </div>

                    {{-- School Motto --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">School Motto / Tagline (Optional)</label>
                        <input type="text" name="school_motto" id="school_motto" value="{{ old('school_motto') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                               placeholder="e.g. Discipline and Dedication">
                    </div>

                    {{-- School Logo --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">School Logo (Optional)</label>
                        <div class="flex items-center gap-4">
                            <label class="flex-1 cursor-pointer">
                                <div class="w-full px-4 py-3 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl hover:border-primary/50 transition-all text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-600">Click to upload logo</p>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF (Max 2MB)</p>
                                </div>
                                <input type="file" name="school_logo" id="school_logo" accept="image/*" class="hidden" onchange="previewLogo(this)">
                            </label>
                            <div id="logo-preview" class="hidden w-24 h-24 rounded-xl border-2 border-gray-200 overflow-hidden">
                                <img src="" alt="Logo preview" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    {{-- Next Step Button --}}
                    <button type="button" id="next-step-btn"
                            class="w-full py-4 bg-primary text-white font-bold text-base rounded-xl hover:bg-primary-dark transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span>Continue to Owner Account</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

                {{-- STEP 2: OWNER ACCOUNT --}}
                <div id="step-2-container" class="space-y-5 hidden transition-all duration-300">
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-dark mb-1">Owner Account Details</h2>
                        <p class="text-gray-500 text-sm">Create the main administrator account with full access rights.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- First Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                            <input type="text" name="owner_first_name" id="owner_first_name" value="{{ old('owner_first_name') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="e.g. Adebayo">
                        </div>
                        {{-- Last Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="owner_last_name" id="owner_last_name" value="{{ old('owner_last_name') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="e.g. Oluwaseun">
                        </div>
                    </div>

                    {{-- Admin Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Administrator Email</label>
                        <input type="email" name="owner_email" id="owner_email" value="{{ old('owner_email') }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                               placeholder="e.g. admin@school.edu">
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number (Optional)</label>
                        <input type="text" name="owner_phone" id="owner_phone" value="{{ old('owner_phone') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                               placeholder="e.g. +234 800 000 0000">
                    </div>

                    {{-- Gender & Date of Birth --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gender (Optional)</label>
                            <select name="owner_gender" id="owner_gender"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="">Select gender</option>
                                <option value="male" {{ old('owner_gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('owner_gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth (Optional)</label>
                            <input type="date" name="owner_dob" id="owner_dob" value="{{ old('owner_dob') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                    </div>

                    {{-- Profile Photo --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Profile Photo (Optional)</label>
                        <div class="flex items-center gap-4">
                            <label class="flex-1 cursor-pointer">
                                <div class="w-full px-4 py-3 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl hover:border-primary/50 transition-all text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <p class="text-sm text-gray-600">Click to upload profile photo</p>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF (Max 2MB)</p>
                                </div>
                                <input type="file" name="owner_profile_photo" id="owner_profile_photo" accept="image/*" class="hidden" onchange="previewProfilePhoto(this)">
                            </label>
                            <div id="profile-photo-preview" class="hidden w-24 h-24 rounded-full border-2 border-gray-200 overflow-hidden">
                                <img src="" alt="Profile preview" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <input type="password" name="owner_password" id="owner_password" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="Min. 8 characters">
                        </div>
                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="owner_password_confirmation" id="owner_password_confirmation" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="Repeat password">
                        </div>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="flex gap-4">
                        <button type="button" id="prev-step-btn-1"
                                class="w-1/3 py-4 border-2 border-gray-200 text-gray-500 font-semibold rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Back</span>
                        </button>
                        <button type="button" id="next-step-btn-2"
                                class="w-2/3 py-4 bg-primary text-white font-bold text-base rounded-xl hover:bg-primary-dark transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>Continue to Additional Users</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- STEP 3: ADDITIONAL USERS (Optional) --}}
                <div id="step-3-container" class="space-y-5 hidden transition-all duration-300">
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-dark mb-1">Add Users (Optional)</h2>
                        <p class="text-gray-500 text-sm">Add Principal, Teachers, Parents, or Students now, or skip and add them later from your dashboard.</p>
                    </div>

                    {{-- Optional Additional Accounts --}}
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <h3 class="text-sm font-bold text-blue-900 mb-2">💡 You can skip this step!</h3>
                        <p class="text-xs text-blue-700">These accounts are completely optional. You can add them now for a quick start, or add them later from your dashboard.</p>
                    </div>

                    {{-- Principal Section (Collapsible) --}}
                    <details class="group bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <summary class="px-4 py-3 cursor-pointer font-semibold text-gray-700 hover:bg-gray-100 transition-colors list-none flex items-center justify-between">
                            <span>👤 Add Principal Account (Optional)</span>
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="p-4 space-y-4 bg-white">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                                    <input type="text" name="principal_first_name" value="{{ old('principal_first_name') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="e.g. Chioma">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                                    <input type="text" name="principal_last_name" value="{{ old('principal_last_name') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="e.g. Nwankwo">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <input type="email" name="principal_email" value="{{ old('principal_email') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="principal@school.edu">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                                    <input type="text" name="principal_phone" value="{{ old('principal_phone') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="+234 800 000 0000">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Default password: <strong>password123</strong> (they can change it after first login)</p>
                        </div>
                    </details>

                    {{-- Teacher Section (Collapsible) --}}
                    <details class="group bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <summary class="px-4 py-3 cursor-pointer font-semibold text-gray-700 hover:bg-gray-100 transition-colors list-none flex items-center justify-between">
                            <span>👨‍🏫 Add Teacher Account (Optional)</span>
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="p-4 space-y-4 bg-white">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                                    <input type="text" name="teacher_first_name" value="{{ old('teacher_first_name') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="e.g. Kwame">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                                    <input type="text" name="teacher_last_name" value="{{ old('teacher_last_name') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="e.g. Mensah">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <input type="email" name="teacher_email" value="{{ old('teacher_email') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="teacher@school.edu">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Qualification</label>
                                    <input type="text" name="teacher_qualification" value="{{ old('teacher_qualification') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="e.g. B.Ed Mathematics">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Default password: <strong>password123</strong> (they can change it after first login)</p>
                        </div>
                    </details>

                    {{-- Parent/Guardian Section (Collapsible) --}}
                    <details class="group bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <summary class="px-4 py-3 cursor-pointer font-semibold text-gray-700 hover:bg-gray-100 transition-colors list-none flex items-center justify-between">
                            <span>👨‍👩‍👧 Add Parent/Guardian Account (Optional)</span>
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="p-4 space-y-4 bg-white">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                                    <input type="text" name="parent_first_name" value="{{ old('parent_first_name') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="e.g. Amina">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                                    <input type="text" name="parent_last_name" value="{{ old('parent_last_name') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="e.g. Ibrahim">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <input type="email" name="parent_email" value="{{ old('parent_email') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="parent@example.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                                    <input type="text" name="parent_phone" value="{{ old('parent_phone') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="+234 800 000 0000">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Occupation</label>
                                <input type="text" name="parent_occupation" value="{{ old('parent_occupation') }}"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                       placeholder="e.g. Business Owner">
                            </div>
                            <p class="text-xs text-gray-500">Default password: <strong>password123</strong> (they can change it after first login)</p>
                        </div>
                    </details>

                    {{-- Student Section (Collapsible) --}}
                    <details class="group bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                        <summary class="px-4 py-3 cursor-pointer font-semibold text-gray-700 hover:bg-gray-100 transition-colors list-none flex items-center justify-between">
                            <span>🎓 Add Student Account (Optional)</span>
                            <svg class="w-5 h-5 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </summary>
                        <div class="p-4 space-y-4 bg-white">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                                    <input type="text" name="student_first_name" value="{{ old('student_first_name') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="e.g. Kofi">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                                    <input type="text" name="student_last_name" value="{{ old('student_last_name') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="e.g. Asante">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email (Optional)</label>
                                    <input type="email" name="student_email" value="{{ old('student_email') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="student@example.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Admission Number (Optional)</label>
                                    <input type="text" name="student_admission_no" value="{{ old('student_admission_no') }}"
                                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                           placeholder="e.g. STU2026001">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Default password: <strong>password123</strong> (they can change it after first login)</p>
                        </div>
                    </details>

                    {{-- Navigation Buttons --}}
                    <div class="flex gap-4">
                        <button type="button" id="prev-step-btn"
                                class="w-1/3 py-4 border-2 border-gray-200 text-gray-500 font-semibold rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            <span>Back</span>
                        </button>
                        <button type="submit" id="submit-btn"
                                class="w-2/3 py-4 bg-primary text-white font-bold text-base rounded-xl hover:bg-primary-dark transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2 animate-pulse-glow">
                            <span>Complete Registration</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>

            {{-- Login Redirect link --}}
            <p class="text-center mt-8 text-sm text-gray-500">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-semibold text-primary hover:text-primary-dark transition-colors">Sign in here</a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Logo preview function
    function previewLogo(input) {
        const preview = document.getElementById('logo-preview');
        const previewImg = preview.querySelector('img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Profile photo preview function
    function previewProfilePhoto(input) {
        const preview = document.getElementById('profile-photo-preview');
        const previewImg = preview.querySelector('img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    const step1Container = document.getElementById('step-1-container');
    const step2Container = document.getElementById('step-2-container');
    const step3Container = document.getElementById('step-3-container');
    const nextBtn = document.getElementById('next-step-btn');
    const nextBtn2 = document.getElementById('next-step-btn-2');
    const prevBtn1 = document.getElementById('prev-step-btn-1');
    const prevBtn = document.getElementById('prev-step-btn');
    const submitBtn = document.getElementById('submit-btn');

    const progressBar = document.getElementById('step-progress-bar');
    const progressText = document.getElementById('progress-text');
    const textIndicator = document.getElementById('step-indicator-text');
    const badge1 = document.getElementById('step-badge-1');
    const badge2 = document.getElementById('step-badge-2');
    const badge3 = document.getElementById('step-badge-3');

    // Validation fields for Step 1
    const schoolName = document.getElementById('school_name');
    const schoolCode = document.getElementById('school_code');
    const schoolCountry = document.getElementById('school_country');

    // Validation fields for Step 2
    const ownerFirstName = document.getElementById('owner_first_name');
    const ownerLastName = document.getElementById('owner_last_name');
    const ownerEmail = document.getElementById('owner_email');
    const ownerPassword = document.getElementById('owner_password');
    const ownerPasswordConfirm = document.getElementById('owner_password_confirmation');

    // Step 1 -> Step 2
    nextBtn.addEventListener('click', () => {
        // Basic validation check before moving to step 2
        if (!schoolName.value.trim() || !schoolCode.value.trim() || !schoolCountry.value) {
            alert('Please fill out all required fields (School Name, School Code, Country) before proceeding.');
            return;
        }

        // Hide Step 1, Show Step 2
        step1Container.classList.add('hidden');
        step2Container.classList.remove('hidden');

        // Update Stepper Progress
        progressBar.style.width = '66%';
        progressText.textContent = '66% Complete';
        textIndicator.textContent = 'Step 2: Owner Account';
        
        updateBadgeActive(badge2);
        updateBadgeInactive(badge1);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Step 2 -> Step 3
    nextBtn2.addEventListener('click', () => {
        // Validate owner details
        if (!ownerFirstName.value.trim() || !ownerLastName.value.trim() || !ownerEmail.value.trim()) {
            alert('Please fill out all required Owner account fields before proceeding.');
            return;
        }

        if (!ownerPassword.value || ownerPassword.value !== ownerPasswordConfirm.value) {
            alert('Please enter matching passwords.');
            return;
        }

        if (ownerPassword.value.length < 8) {
            alert('Password must be at least 8 characters long.');
            return;
        }

        // Hide Step 2, Show Step 3
        step2Container.classList.add('hidden');
        step3Container.classList.remove('hidden');

        // Update Stepper Progress
        progressBar.style.width = '100%';
        progressText.textContent = '100% Complete';
        textIndicator.textContent = 'Step 3: Additional Users (Optional)';

        updateBadgeActive(badge3);
        updateBadgeInactive(badge2);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Step 2 -> Step 1 (Back)
    prevBtn1.addEventListener('click', () => {
        // Hide Step 2, Show Step 1
        step2Container.classList.add('hidden');
        step1Container.classList.remove('hidden');

        // Update Stepper Progress
        progressBar.style.width = '33%';
        progressText.textContent = '33% Complete';
        textIndicator.textContent = 'Step 1: School Registration';

        updateBadgeActive(badge1);
        updateBadgeInactive(badge2);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Step 3 -> Step 2 (Back)
    prevBtn.addEventListener('click', () => {
        // Hide Step 3, Show Step 2
        step3Container.classList.add('hidden');
        step2Container.classList.remove('hidden');

        // Update Stepper Progress
        progressBar.style.width = '66%';
        progressText.textContent = '66% Complete';
        textIndicator.textContent = 'Step 2: Owner Account';

        updateBadgeActive(badge2);
        updateBadgeInactive(badge3);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    function updateBadgeActive(badge) {
        badge.classList.add('text-primary');
        badge.classList.remove('text-gray-400');
        badge.querySelector('span').classList.add('bg-primary/10');
        badge.querySelector('span').classList.remove('bg-gray-100');
    }

    function updateBadgeInactive(badge) {
        badge.classList.remove('text-primary');
        badge.classList.add('text-gray-400');
        badge.querySelector('span').classList.remove('bg-primary/10');
        badge.querySelector('span').classList.add('bg-gray-100');
    }
</script>
@endpush
@endsection
