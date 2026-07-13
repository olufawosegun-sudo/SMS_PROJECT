@extends('layouts.app')

@section('title', 'Get Started — EduWest Africa')

@section('body')
<div class="min-h-screen flex flex-col lg:flex-row">
    {{-- Left Panel: Branding & Features --}}
    <div class="hidden lg:flex lg:w-5/12 gradient-hero hero-pattern relative items-center justify-center p-12 overflow-hidden">
        {{-- Floating decorative items --}}
        <div class="absolute top-20 left-10 w-64 h-64 bg-accent/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 bg-primary-light/10 rounded-full blur-3xl animate-float-delayed"></div>

        <div class="relative z-10 max-w-md w-full">
            {{-- Logo --}}
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 mb-12">
                <div class="w-14 h-14 rounded-2xl gradient-primary flex items-center justify-center shadow-2xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="text-left">
                    <span class="text-3xl font-bold text-white">Edu<span class="text-accent">West</span></span>
                    <span class="block text-xs text-white/50 -mt-1 tracking-widest uppercase">Africa</span>
                </div>
            </a>

            <h1 class="text-3xl font-extrabold text-white mb-6 leading-tight">
                Establish Your School's <span class="text-gradient">Digital Presence</span>
            </h1>
            <p class="text-white/70 leading-relaxed mb-10">
                Join primary and secondary schools across West Africa in standardizing administration, academics, payments, and communications.
            </p>

            {{-- Feature benefits --}}
            <div class="space-y-4">
                @foreach([
                    ['title' => 'Pan-African Standardization', 'desc' => 'Supports WAEC, NECO, and national grading structures.'],
                    ['title' => 'Robust Financial Control', 'desc' => 'Multi-currency invoicing with mobile money & bank transfers.'],
                    ['title' => 'Intelligent Grading', 'desc' => 'Instant terminal report cards & position generation.'],
                ] as $item)
                <div class="glass rounded-2xl p-4 flex gap-4 items-start">
                    <div class="w-8 h-8 rounded-lg bg-accent/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h4 class="text-sm font-bold text-white mb-0.5">{{ $item['title'] }}</h4>
                        <p class="text-xs text-white/60 leading-relaxed">{{ $item['desc'] }}</p>
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
                    <span class="text-primary" id="step-indicator-text">Step 1: School Profile</span>
                    <span>50% Complete</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-primary transition-all duration-500 w-1/2" id="step-progress-bar"></div>
                </div>
                <div class="flex justify-between mt-4">
                    <div class="flex items-center gap-2 text-sm font-semibold text-primary" id="step-badge-1">
                        <span class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center text-xs">1</span>
                        <span>School Info</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-400" id="step-badge-2">
                        <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs">2</span>
                        <span>Administrator</span>
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
            <form action="{{ route('register') }}" method="POST" id="multistep-form" class="space-y-6 text-left">
                @csrf

                {{-- STEP 1: SCHOOL INFO --}}
                <div id="step-1-container" class="space-y-5 transition-all duration-300">
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-dark mb-1">Create School Profile</h2>
                        <p class="text-gray-500 text-sm">Enter the official credentials of your secondary school.</p>
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

                    {{-- School Motto --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">School Motto / Tagline (Optional)</label>
                        <input type="text" name="school_motto" id="school_motto" value="{{ old('school_motto') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                               placeholder="e.g. Discipline and Dedication">
                    </div>

                    {{-- Next Step Button --}}
                    <button type="button" id="next-step-btn"
                            class="w-full py-4 bg-primary text-white font-bold text-base rounded-xl hover:bg-primary-dark transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span>Continue to Owner Details</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

                {{-- STEP 2: ADMINISTRATOR / OWNER INFO --}}
                <div id="step-2-container" class="space-y-5 hidden transition-all duration-300">
                    <div class="mb-6">
                        <h2 class="text-2xl font-extrabold text-dark mb-1">Owner Account Details</h2>
                        <p class="text-gray-500 text-sm">Create the administrator/owner account for school dashboard access.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- First Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="e.g. Adebayo">
                        </div>
                        {{-- Last Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="e.g. Oluwaseun">
                        </div>
                    </div>

                    {{-- Admin Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Administrator Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                               placeholder="e.g. admin@school.edu">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="Min. 8 characters">
                        </div>
                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                   placeholder="Repeat password">
                        </div>
                    </div>

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
                            <span>Register & Onboard</span>
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
    const step1Container = document.getElementById('step-1-container');
    const step2Container = document.getElementById('step-2-container');
    const nextBtn = document.getElementById('next-step-btn');
    const prevBtn = document.getElementById('prev-step-btn');
    const submitBtn = document.getElementById('submit-btn');

    const progressBar = document.getElementById('step-progress-bar');
    const textIndicator = document.getElementById('step-indicator-text');
    const badge1 = document.getElementById('step-badge-1');
    const badge2 = document.getElementById('step-badge-2');

    // Validation fields for Step 1
    const schoolName = document.getElementById('school_name');
    const schoolCode = document.getElementById('school_code');
    const schoolCountry = document.getElementById('school_country');

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
        progressBar.style.width = '100%';
        textIndicator.textContent = 'Step 2: Administrator Profile';
        
        badge1.classList.remove('text-primary');
        badge1.classList.add('text-gray-400');
        badge1.querySelector('span').classList.remove('bg-primary/10');
        badge1.querySelector('span').classList.add('bg-gray-100');

        badge2.classList.add('text-primary');
        badge2.classList.remove('text-gray-400');
        badge2.querySelector('span').classList.add('bg-primary/10');
        badge2.querySelector('span').classList.remove('bg-gray-100');
    });

    prevBtn.addEventListener('click', () => {
        // Hide Step 2, Show Step 1
        step2Container.classList.add('hidden');
        step1Container.classList.remove('hidden');

        // Update Stepper Progress
        progressBar.style.width = '50%';
        textIndicator.textContent = 'Step 1: School Profile';

        badge2.classList.remove('text-primary');
        badge2.classList.add('text-gray-400');
        badge2.querySelector('span').classList.remove('bg-primary/10');
        badge2.querySelector('span').classList.add('bg-gray-100');

        badge1.classList.add('text-primary');
        badge1.classList.remove('text-gray-400');
        badge1.querySelector('span').classList.add('bg-primary/10');
        badge1.querySelector('span').classList.remove('bg-gray-100');
    });
</script>
@endpush
@endsection
