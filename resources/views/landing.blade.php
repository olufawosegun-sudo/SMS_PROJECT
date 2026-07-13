@extends('layouts.app')

@section('title', 'EduWest Africa — Empowering Education Across West Africa')

@section('body')
{{-- ========================================
     NAVIGATION
     ======================================== --}}
<nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-dark/70 backdrop-blur-xl border-b border-white/10" id="main-nav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-bold text-white">Edu<span class="text-accent">West</span></span>
                    <span class="block text-[10px] text-white/60 -mt-1 tracking-widest uppercase">Africa</span>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-medium text-white/80 hover:text-accent transition-colors">Features</a>
                <a href="#countries" class="text-sm font-medium text-white/80 hover:text-accent transition-colors">Coverage</a>
                <a href="#testimonials" class="text-sm font-medium text-white/80 hover:text-accent transition-colors">Testimonials</a>
            </div>

            {{-- Auth Buttons (Desktop) + Hamburger (Mobile) --}}
            <div class="flex items-center gap-3">
                {{-- Desktop auth buttons --}}
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-accent text-dark font-semibold text-sm rounded-lg hover:bg-accent-dark transition-all shadow-lg hover:shadow-xl">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-medium text-white/90 hover:text-white border border-white/20 rounded-lg hover:border-white/40 transition-all">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-accent text-dark font-semibold text-sm rounded-lg hover:bg-accent-dark transition-all shadow-lg hover:shadow-xl">
                            Get Started
                        </a>
                    @endauth
                </div>

                {{-- Mobile hamburger --}}
                <button class="md:hidden w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors" id="mobile-menu-btn" aria-label="Toggle menu">
                    <svg class="w-5 h-5 text-white" id="menu-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg class="w-5 h-5 text-white hidden" id="menu-icon-close" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Dropdown --}}
    <div class="md:hidden hidden bg-dark/95 backdrop-blur-xl border-t border-white/10" id="mobile-menu">
        <div class="px-4 py-6 space-y-3">
            <a href="#features" class="block px-4 py-3 text-sm font-medium text-white/80 hover:text-accent hover:bg-white/5 rounded-xl transition-all">Features</a>
            <a href="#countries" class="block px-4 py-3 text-sm font-medium text-white/80 hover:text-accent hover:bg-white/5 rounded-xl transition-all">Coverage</a>
            <a href="#testimonials" class="block px-4 py-3 text-sm font-medium text-white/80 hover:text-accent hover:bg-white/5 rounded-xl transition-all">Testimonials</a>
            <div class="border-t border-white/10 pt-4 mt-4 space-y-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="block w-full text-center px-5 py-3 bg-accent text-dark font-semibold text-sm rounded-xl">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center px-5 py-3 text-sm font-medium text-white border border-white/20 rounded-xl hover:bg-white/5 transition-all">Log In</a>
                    <a href="{{ route('register') }}" class="block w-full text-center px-5 py-3 bg-accent text-dark font-semibold text-sm rounded-xl">Get Started</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ========================================
     HERO SECTION
     ======================================== --}}
<section class="relative min-h-screen gradient-hero hero-pattern flex items-center overflow-hidden">
    {{-- Floating decorative elements --}}
    <div class="absolute top-20 left-10 w-64 h-64 bg-accent/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-20 right-10 w-80 h-80 bg-primary-light/10 rounded-full blur-3xl animate-float-delayed"></div>
    <div class="absolute top-40 right-1/4 w-4 h-4 bg-accent rounded-full opacity-60 animate-float"></div>
    <div class="absolute bottom-40 left-1/4 w-3 h-3 bg-accent-light rounded-full opacity-40 animate-float-delayed"></div>
    <div class="absolute top-1/3 left-1/2 w-2 h-2 bg-white rounded-full opacity-30 animate-float"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Left: Text Content --}}
            <div class="animate-fade-in-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 rounded-full border border-white/20 mb-8">
                    <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                    <span class="text-sm text-white/80 font-medium">Trusted by 2,000+ Schools Across West Africa</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                    Empowering <br>
                    <span class="text-gradient">Education</span> <br>
                    Across West Africa
                </h1>

                <p class="text-lg text-white/70 leading-relaxed mb-10 max-w-lg">
                    The all-in-one school management platform designed for West African secondary schools. 
                    Streamline student records, attendance, grading, fees, and communication — all from a single dashboard.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-accent text-dark font-bold text-base rounded-xl hover:bg-accent-dark transition-all shadow-xl hover:shadow-2xl hover:-translate-y-0.5 animate-pulse-glow">
                        <span>Start Free Trial</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#features" class="inline-flex items-center justify-center gap-2 px-8 py-4 border-2 border-white/30 text-white font-semibold text-base rounded-xl hover:bg-white/10 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Watch Demo</span>
                    </a>
                </div>
            </div>

            {{-- Right: Hero Visual --}}
            <div class="hidden lg:block animate-slide-in-right">
                <div class="relative">
                    {{-- Mock Dashboard Card --}}
                    <div class="glass rounded-2xl p-6 shadow-2xl">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            <span class="text-xs text-white/50 ml-2">EduWest Dashboard</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-white/10 rounded-xl p-4">
                                <p class="text-white/60 text-xs mb-1">Students</p>
                                <p class="text-2xl font-bold text-white">1,247</p>
                                <p class="text-accent text-xs mt-1">↑ 12% this term</p>
                            </div>
                            <div class="bg-white/10 rounded-xl p-4">
                                <p class="text-white/60 text-xs mb-1">Teachers</p>
                                <p class="text-2xl font-bold text-white">86</p>
                                <p class="text-accent text-xs mt-1">↑ 4 new hires</p>
                            </div>
                            <div class="bg-white/10 rounded-xl p-4">
                                <p class="text-white/60 text-xs mb-1">Attendance</p>
                                <p class="text-2xl font-bold text-white">94%</p>
                                <p class="text-green-400 text-xs mt-1">Above target</p>
                            </div>
                            <div class="bg-white/10 rounded-xl p-4">
                                <p class="text-white/60 text-xs mb-1">Fees Collected</p>
                                <p class="text-2xl font-bold text-white">87%</p>
                                <p class="text-accent text-xs mt-1">₦12.4M total</p>
                            </div>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="flex justify-between items-center mb-3">
                                <p class="text-sm text-white/70">Weekly Attendance</p>
                                <p class="text-xs text-accent">This Week</p>
                            </div>
                            <div class="flex items-end gap-2 h-20">
                                @foreach([65, 80, 90, 75, 95, 85, 70] as $height)
                                <div class="flex-1 rounded-t-md transition-all hover:bg-accent" style="height: {{ $height }}%; background: linear-gradient(to top, rgba(212,168,67,0.6), rgba(27,107,62,0.8));">
                                </div>
                                @endforeach
                            </div>
                            <div class="flex justify-between mt-2">
                                @foreach(['M','T','W','T','F','S','S'] as $day)
                                <span class="text-[10px] text-white/40 flex-1 text-center">{{ $day }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Floating badge --}}
                    <div class="absolute -top-4 -right-4 glass rounded-xl px-4 py-3 shadow-xl animate-float">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-accent/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-white font-semibold">99.9% Uptime</p>
                                <p class="text-[10px] text-white/50">Cloud-powered</p>
                            </div>
                        </div>
                    </div>

                    {{-- Floating notification --}}
                    <div class="absolute -bottom-6 -left-6 glass rounded-xl px-4 py-3 shadow-xl animate-float-delayed">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-white font-semibold">+23 Enrolled</p>
                                <p class="text-[10px] text-white/50">Today</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</section>

{{-- ========================================
     STATS BAR
     ======================================== --}}
<section class="relative -mt-12 z-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 grid grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach([
                ['value' => '15+', 'label' => 'Countries', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['value' => '2,000+', 'label' => 'Schools', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['value' => '500K+', 'label' => 'Students', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['value' => '10K+', 'label' => 'Teachers', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z']
            ] as $index => $stat)
            <div class="text-center group" style="animation: fade-in-up 0.6s ease-out {{ $index * 0.15 }}s both;">
                <div class="w-12 h-12 mx-auto mb-3 bg-primary/10 rounded-xl flex items-center justify-center group-hover:bg-primary group-hover:scale-110 transition-all duration-300">
                    <svg class="w-6 h-6 text-primary group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
                <p class="text-3xl font-extrabold text-dark mb-1" data-counter="{{ $stat['value'] }}">{{ $stat['value'] }}</p>
                <p class="text-sm text-gray-500 font-medium">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================
     FEATURES SECTION
     ======================================== --}}
<section id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary/10 text-primary text-sm font-semibold rounded-full mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Powerful Features
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-dark mb-4">
                Everything You Need to <span class="text-primary">Manage Your School</span>
            </h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">
                From enrollment to graduation, our platform covers every aspect of school administration.
            </p>
        </div>

        {{-- Features Grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach([
                [
                    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                    'title' => 'Student Records',
                    'desc' => 'Complete digital profiles for every student — bio-data, enrollment history, academic records, and medical information all in one place.',
                    'color' => 'primary',
                ],
                [
                    'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                    'title' => 'Attendance Tracking',
                    'desc' => 'Real-time attendance monitoring with biometric support. Get alerts on absenteeism and generate attendance reports instantly.',
                    'color' => 'info',
                ],
                [
                    'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                    'title' => 'Grades & Report Cards',
                    'desc' => 'Automated grading with customizable scales (WAEC, NECO compatible). Generate beautiful PDF report cards with a click.',
                    'color' => 'accent',
                ],
                [
                    'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
                    'title' => 'Fee Management',
                    'desc' => 'Track payments, generate invoices, send reminders. Supports multiple currencies (NGN, GHS, XOF, XAF) with mobile money integration.',
                    'color' => 'success',
                ],
                [
                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'title' => 'Timetable Scheduling',
                    'desc' => 'Smart timetable generation with conflict detection. Manage class schedules, exam dates, and school calendars effortlessly.',
                    'color' => 'warning',
                ],
                [
                    'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                    'title' => 'Parent Portal',
                    'desc' => 'Direct communication channel between school and parents. Real-time updates on grades, attendance, and school announcements via SMS & app.',
                    'color' => 'danger',
                ],
            ] as $index => $feature)
            <div class="group relative bg-white border border-gray-100 rounded-2xl p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300" style="animation: fade-in-up 0.6s ease-out {{ $index * 0.1 }}s both;">
                <div class="w-14 h-14 rounded-2xl bg-{{ $feature['color'] }}/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-{{ $feature['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-dark mb-3">{{ $feature['title'] }}</h3>
                <p class="text-gray-500 leading-relaxed">{{ $feature['desc'] }}</p>
                <div class="mt-5">
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-{{ $feature['color'] }} group-hover:gap-2 transition-all">
                        Learn more
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================
     COUNTRIES SECTION
     ======================================== --}}
<section id="countries" class="py-24 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-accent/10 text-accent-dark text-sm font-semibold rounded-full mb-4">
                🌍 West African Coverage
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-dark mb-4">
                Serving Schools Across <span class="text-primary">15+ Countries</span>
            </h2>
            <p class="text-gray-500 text-lg max-w-2xl mx-auto">
                Designed with the unique needs of West African education systems in mind — supporting WAEC, NECO, BECE, and local curricula.
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach([
                ['🇳🇬', 'Nigeria'],
                ['🇬🇭', 'Ghana'],
                ['🇸🇳', 'Senegal'],
                ['🇨🇮', "Côte d'Ivoire"],
                ['🇨🇲', 'Cameroon'],
                ['🇲🇱', 'Mali'],
                ['🇧🇫', 'Burkina Faso'],
                ['🇳🇪', 'Niger'],
                ['🇬🇳', 'Guinea'],
                ['🇸🇱', 'Sierra Leone'],
                ['🇱🇷', 'Liberia'],
                ['🇹🇬', 'Togo'],
                ['🇧🇯', 'Benin'],
                ['🇬🇲', 'Gambia'],
                ['🇬🇼', 'Guinea-Bissau'],
            ] as $index => $country)
            <div class="bg-white rounded-xl p-5 text-center border border-gray-100 hover:border-primary/30 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group" style="animation: fade-in-up 0.5s ease-out {{ $index * 0.05 }}s both;">
                <span class="text-3xl block mb-2 group-hover:scale-125 transition-transform">{{ $country[0] }}</span>
                <p class="text-sm font-semibold text-gray-700">{{ $country[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================
     TESTIMONIALS SECTION
     ======================================== --}}
<section id="testimonials" class="py-24 gradient-primary relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full hero-pattern opacity-30"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 text-white/80 text-sm font-semibold rounded-full mb-4">
                ⭐ What Educators Say
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">
                Trusted by School Leaders <span class="text-gradient">Everywhere</span>
            </h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach([
                [
                    'quote' => 'EduWest transformed how we manage our school. From manual record-keeping to digital excellence — our efficiency improved by 300%.',
                    'name' => 'Mrs. Adunni Bakare',
                    'role' => 'Principal, Lagos Model Secondary School',
                    'country' => '🇳🇬',
                    'initials' => 'AB',
                ],
                [
                    'quote' => 'The fee management module alone saved us countless hours. Parents can now pay via mobile money, and we track everything in real-time.',
                    'name' => 'Mr. Kwesi Asante',
                    'role' => 'Administrator, Accra International School',
                    'country' => '🇬🇭',
                    'initials' => 'KA',
                ],
                [
                    'quote' => 'Report card generation used to take us 2 weeks. With EduWest, we do it in 2 hours. The WAEC integration is a game-changer.',
                    'name' => 'Mme. Aminata Diop',
                    'role' => 'Director, Lycée Mariama Bâ, Dakar',
                    'country' => '🇸🇳',
                    'initials' => 'AD',
                ],
            ] as $index => $testimonial)
            <div class="glass rounded-2xl p-8 hover:-translate-y-2 transition-all duration-300" style="animation: fade-in-up 0.6s ease-out {{ $index * 0.15 }}s both;">
                <div class="flex gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <p class="text-white/90 leading-relaxed mb-6 italic">"{{ $testimonial['quote'] }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-accent/30 flex items-center justify-center text-sm font-bold text-white">
                        {{ $testimonial['initials'] }}
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">{{ $testimonial['name'] }} {{ $testimonial['country'] }}</p>
                        <p class="text-white/50 text-xs">{{ $testimonial['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================
     CTA SECTION
     ======================================== --}}
<section class="py-24 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-gradient-to-br from-primary/5 to-accent/5 rounded-3xl p-12 sm:p-16 border border-primary/10">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-dark mb-4">
                Ready to Transform Your School?
            </h2>
            <p class="text-gray-500 text-lg mb-8 max-w-xl mx-auto">
                Join thousands of West African schools already using EduWest to streamline their operations.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-primary text-white font-bold text-base rounded-xl hover:bg-primary-dark transition-all shadow-xl hover:shadow-2xl hover:-translate-y-0.5">
                    <span>Start Free Trial</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-4 border-2 border-primary/30 text-primary font-semibold text-base rounded-xl hover:bg-primary/5 transition-all">
                    Contact Sales
                </a>
            </div>
            <p class="mt-6 text-sm text-gray-400">No credit card required • Free 30-day trial • Cancel anytime</p>
        </div>
    </div>
</section>

{{-- ========================================
     FOOTER
     ======================================== --}}
<footer class="bg-dark text-white/70 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-12 mb-12">
            {{-- Brand --}}
            <div class="md:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-white">Edu<span class="text-accent">West</span></span>
                        <span class="block text-[10px] text-white/40 -mt-1 tracking-widest uppercase">Africa</span>
                    </div>
                </div>
                <p class="text-sm leading-relaxed">Empowering secondary education across West Africa with modern school management technology.</p>
            </div>

            {{-- Product --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Product</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#features" class="hover:text-accent transition-colors">Features</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Pricing</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Integrations</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">API Docs</a></li>
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Company</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-accent transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Careers</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Blog</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Contact</a></li>
                </ul>
            </div>

            {{-- Support --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Support</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-accent transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Documentation</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-accent transition-colors">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm">&copy; {{ date('Y') }} EduWest Africa. All rights reserved.</p>
            <div class="flex items-center gap-4">
                {{-- Social icons --}}
                @foreach(['M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z', 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z', 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'] as $icon)
                <a href="#" class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center hover:bg-accent/20 transition-colors">
                    <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                    </svg>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</footer>

@push('scripts')
<script>
    // Navbar background on scroll
    const nav = document.getElementById('main-nav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            nav.classList.remove('bg-dark/70', 'border-white/10');
            nav.classList.add('bg-dark/95', 'shadow-lg', 'border-transparent');
        } else {
            nav.classList.add('bg-dark/70', 'border-white/10');
            nav.classList.remove('bg-dark/95', 'shadow-lg', 'border-transparent');
        }
    });

    // Mobile menu toggle
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen = document.getElementById('menu-icon-open');
    const iconClose = document.getElementById('menu-icon-close');

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    });

    // Close mobile menu on link click
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>
@endpush
@endsection
