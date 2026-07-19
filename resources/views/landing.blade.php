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
                        <a href="{{ route('dashboard') }}" class="group relative inline-flex items-center gap-2 px-6 py-2.5 font-semibold text-sm rounded-xl overflow-hidden transition-all hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(212,168,67,0.25)]" style="background: linear-gradient(135deg, #D4A843, #B8912E); color: #0a0a1a;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5L2 9l10 12L22 9l-3-6z"/></svg>
                            Dashboard
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500" style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent); animation: line-sweep 2s ease-in-out infinite;"></div>
                        </a>
                    @else
                        <a href="{{ route('apply') }}" class="px-5 py-2.5 text-sm font-semibold text-accent hover:text-white rounded-xl transition-all hover:bg-white/5">
                            Apply for Admission
                        </a>
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-medium text-white/80 hover:text-white rounded-xl transition-all diamond-border hover:bg-white/5">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="group relative inline-flex items-center gap-2 px-6 py-2.5 font-semibold text-sm rounded-xl overflow-hidden transition-all hover:-translate-y-0.5 hover:shadow-[0_8px_30px_rgba(212,168,67,0.25)]" style="background: linear-gradient(135deg, #D4A843, #B8912E); color: #0a0a1a;">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5L2 9l10 12L22 9l-3-6z"/></svg>
                            Get Started
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500" style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent); animation: line-sweep 2s ease-in-out infinite;"></div>
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
            <div class="border-t border-accent/10 pt-4 mt-4 space-y-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="block w-full text-center px-5 py-3 font-semibold text-sm rounded-xl transition-all hover:shadow-[0_8px_30px_rgba(212,168,67,0.2)]" style="background: linear-gradient(135deg, #D4A843, #B8912E); color: #0a0a1a;">Dashboard</a>
                @else
                    <a href="{{ route('apply') }}" class="block w-full text-center px-5 py-3 text-sm font-semibold text-accent hover:text-white rounded-xl hover:bg-white/5 transition-all">Apply for Admission</a>
                    <a href="{{ route('login') }}" class="block w-full text-center px-5 py-3 text-sm font-medium text-white/80 diamond-border rounded-xl hover:bg-white/5 transition-all">Log In</a>
                    <a href="{{ route('register') }}" class="block w-full text-center px-5 py-3 font-semibold text-sm rounded-xl transition-all" style="background: linear-gradient(135deg, #D4A843, #B8912E); color: #0a0a1a;">Get Started</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ========================================
     HERO SECTION — LUXURY DIAMOND EDITION
     ======================================== --}}
<section class="relative min-h-screen gradient-hero-luxury flex items-center overflow-hidden" id="hero-section">
    {{-- Canvas particle system --}}
    <canvas class="absolute inset-0 w-full h-full pointer-events-none" id="hero-canvas"></canvas>

    {{-- Layered radial light sources --}}
    <div class="absolute inset-0 pointer-events-none">
        {{-- Top-center gold glow —  like a spotlight from above --}}
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[900px] h-[500px] rounded-full blur-[160px] opacity-[0.07]" style="background: radial-gradient(circle, #D4A843, transparent 70%);"></div>
        {{-- Bottom-left green aurora --}}
        <div class="absolute -bottom-32 -left-32 w-[700px] h-[500px] rounded-full blur-[130px] opacity-[0.09] animate-float" style="background: radial-gradient(ellipse, #1B6B3E, transparent 70%);"></div>
        {{-- Right-center warm accent --}}
        <div class="absolute top-1/3 -right-20 w-[500px] h-[600px] rounded-full blur-[140px] opacity-[0.05] animate-float-delayed" style="background: radial-gradient(ellipse, #D4A843, transparent 70%);"></div>
        {{-- Center diffuse core --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] rounded-full blur-[200px] opacity-[0.04]" style="background: radial-gradient(circle, rgba(212,168,67,0.3), rgba(27,107,62,0.15) 40%, transparent 70%);"></div>
    </div>

    {{-- Aurora wave bands --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[20%] left-0 w-full h-[1px] opacity-[0.06]" style="background: linear-gradient(90deg, transparent 0%, #D4A843 20%, #1B6B3E 50%, #D4A843 80%, transparent 100%); filter: blur(2px);"></div>
        <div class="absolute top-[45%] left-0 w-full h-[1px] opacity-[0.04]" style="background: linear-gradient(90deg, transparent 0%, #1B6B3E 30%, #D4A843 60%, transparent 100%); filter: blur(3px);"></div>
        <div class="absolute top-[70%] left-0 w-full h-[1px] opacity-[0.05]" style="background: linear-gradient(90deg, transparent 10%, #D4A843 40%, #2D8F54 70%, transparent 100%); filter: blur(2px);"></div>
    </div>

    {{-- Diagonal gold streak --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-20 -left-20 w-[200%] h-[2px] rotate-[25deg] opacity-[0.06]" style="background: linear-gradient(90deg, transparent 0%, transparent 30%, #D4A843 48%, #F5E6B8 50%, #D4A843 52%, transparent 70%, transparent 100%);"></div>
    </div>

    {{-- Subtle noise texture overlay --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.015] mix-blend-overlay" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 256 256%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22n%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22/%3E%3C/svg%3E'); background-size: 256px 256px;"></div>

    {{-- Gold accent edge lines --}}
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-accent/30 to-transparent"></div>
    <div class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-accent/20 to-transparent"></div>

    {{-- Cinematic vignette --}}
    <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(ellipse 70% 60% at 50% 50%, transparent 40%, rgba(5,5,16,0.5) 100%);"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20 w-full relative z-10">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Left: Text Content --}}
            <div class="animate-fade-in-up">
                {{-- Award badge --}}
                <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full mb-8 glass-luxury animate-badge-entrance" style="animation-delay: 0.3s;">
                    <div class="relative">
                        <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center">
                            {{-- Trophy icon --}}
                            <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2z"/>
                            </svg>
                        </div>
                        <div class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-accent rounded-full animate-pulse"></div>
                    </div>
                    <div class="h-5 w-px bg-accent/30"></div>
                    <span class="text-sm font-semibold animate-gold-shimmer">Award-Winning Platform</span>
                    <div class="h-5 w-px bg-accent/30"></div>
                    <span class="text-xs text-white/60 font-medium">Trusted by 2,000+ Schools</span>
                </div>

                {{-- Main heading with luxury treatment --}}
                <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black text-white leading-[1.1] mb-6 tracking-tight">
                    <span class="block opacity-0" style="animation: fade-in-up 0.8s ease-out 0.4s forwards;">The</span>
                    <span class="block opacity-0" style="animation: fade-in-up 0.8s ease-out 0.6s forwards;">
                        <span class="text-gradient-gold">Diamond</span>
                    </span>
                    <span class="block opacity-0" style="animation: fade-in-up 0.8s ease-out 0.8s forwards;">
                        Standard in
                    </span>
                    <span class="block opacity-0" style="animation: fade-in-up 0.8s ease-out 1s forwards;">
                        <span class="text-gradient">Education</span>
                    </span>
                </h1>

                {{-- Gold separator line --}}
                <div class="relative w-24 h-0.5 mb-8 overflow-hidden rounded-full opacity-0" style="animation: fade-in-up 0.8s ease-out 1.1s forwards; background: linear-gradient(90deg, #D4A843, #F5E6B8, #D4A843);">
                    <div class="absolute inset-0 bg-white/40" style="animation: line-sweep 2s ease-in-out infinite;"></div>
                </div>

                <p class="text-lg text-white/60 leading-relaxed mb-10 max-w-lg opacity-0" style="animation: fade-in-up 0.8s ease-out 1.2s forwards;">
                    The <span class="text-accent font-semibold">premier</span> all-in-one school management platform crafted for West African excellence. 
                    Elevate your institution with world-class student records, analytics, grading, and communication.
                </p>

                {{-- Luxury CTA buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 opacity-0" style="animation: fade-in-up 0.8s ease-out 1.4s forwards;">
                    <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center gap-3 px-10 py-4.5 font-bold text-base rounded-2xl overflow-hidden transition-all hover:-translate-y-1 hover:shadow-[0_20px_60px_rgba(212,168,67,0.3)] animate-luxury-glow" style="background: linear-gradient(135deg, #D4A843 0%, #B8912E 50%, #D4A843 100%); color: #0a0a1a;">
                        {{-- Shimmer sweep --}}
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500" style="background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); animation: line-sweep 2s ease-in-out infinite;"></div>
                        {{-- Diamond icon --}}
                        <svg class="w-5 h-5 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5L2 9l10 12L22 9l-3-6zM9.62 8l1.5-3h1.76l1.5 3H9.62zM11 10v7.38L5.24 10H11zm2 0h5.76L13 17.38V10zm5.26-2h-2.65l-1.5-3h2.65l1.5 3zM7.24 5h2.65l-1.5 3H5.74l1.5-3z"/>
                        </svg>
                        <span class="relative z-10 tracking-wide">Start Free Trial</span>
                        <svg class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#features" class="group inline-flex items-center justify-center gap-3 px-10 py-4.5 font-semibold text-base rounded-2xl transition-all hover:-translate-y-1 diamond-border text-white hover:bg-white/5">
                        <div class="relative w-10 h-10 rounded-full border border-accent/40 flex items-center justify-center group-hover:border-accent transition-colors">
                            <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                            {{-- Pulse ring --}}
                            <div class="absolute inset-0 rounded-full border border-accent/30" style="animation: ring-pulse 2s ease-in-out infinite;"></div>
                        </div>
                        <span>Watch Demo</span>
                    </a>
                </div>

                {{-- Trust indicators --}}
                <div class="flex items-center gap-6 mt-10 opacity-0" style="animation: fade-in-up 0.8s ease-out 1.6s forwards;">
                    <div class="flex -space-x-2">
                        @foreach(['#D4A843', '#1B6B3E', '#3498DB', '#E74C3C'] as $color)
                        <div class="w-9 h-9 rounded-full border-2 border-dark flex items-center justify-center text-xs font-bold text-white" style="background: {{ $color }};">
                            {{ ['AB', 'KA', 'SO', 'AD'][$loop->index] }}
                        </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="flex gap-0.5 mb-0.5">
                            @for($i = 0; $i < 5; $i++)
                            <svg class="w-3.5 h-3.5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <p class="text-xs text-white/50"><span class="text-white/80 font-medium">4.9/5</span> from 500+ reviews</p>
                    </div>
                </div>
            </div>

            {{-- Right: Premium Hero Visual --}}
            <div class="hidden lg:block animate-slide-in-right">
                <div class="relative">
                    {{-- Outer glow ring --}}
                    <div class="absolute -inset-4 rounded-3xl opacity-50" style="background: linear-gradient(135deg, rgba(212,168,67,0.15), transparent, rgba(212,168,67,0.1)); filter: blur(20px);"></div>
                    
                    {{-- Main Dashboard Card --}}
                    <div class="relative rounded-3xl p-7 shadow-2xl overflow-hidden glass-luxury animate-luxury-glow">
                        {{-- Gold corner accents --}}
                        <div class="absolute top-0 left-0 w-16 h-16">
                            <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-accent/60 to-transparent"></div>
                            <div class="absolute top-0 left-0 h-full w-px bg-gradient-to-b from-accent/60 to-transparent"></div>
                        </div>
                        <div class="absolute top-0 right-0 w-16 h-16">
                            <div class="absolute top-0 right-0 w-full h-px bg-gradient-to-l from-accent/60 to-transparent"></div>
                            <div class="absolute top-0 right-0 h-full w-px bg-gradient-to-b from-accent/60 to-transparent"></div>
                        </div>
                        <div class="absolute bottom-0 left-0 w-16 h-16">
                            <div class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-accent/60 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 h-full w-px bg-gradient-to-t from-accent/60 to-transparent"></div>
                        </div>
                        <div class="absolute bottom-0 right-0 w-16 h-16">
                            <div class="absolute bottom-0 right-0 w-full h-px bg-gradient-to-l from-accent/60 to-transparent"></div>
                            <div class="absolute bottom-0 right-0 h-full w-px bg-gradient-to-t from-accent/60 to-transparent"></div>
                        </div>

                        {{-- Window controls --}}
                        <div class="flex items-center gap-3 mb-7">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-400/80"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400/80"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400/80"></div>
                            </div>
                            <div class="flex-1 h-6 bg-white/5 rounded-lg flex items-center justify-center">
                                <span class="text-[10px] text-white/30 tracking-wider uppercase font-medium">EduWest Africa — Dashboard</span>
                            </div>
                            <div class="w-6 h-6 rounded-md bg-accent/10 flex items-center justify-center">
                                <svg class="w-3 h-3 text-accent" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3H5L2 9l10 12L22 9l-3-6z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Stats grid with gold accents --}}
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            @foreach([
                                ['label' => 'Students', 'value' => '1,247', 'change' => '↑ 12%', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197', 'trend' => 'text-accent'],
                                ['label' => 'Teachers', 'value' => '86', 'change' => '+4 new', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'trend' => 'text-accent'],
                                ['label' => 'Attendance', 'value' => '94%', 'change' => 'Above target', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'trend' => 'text-green-400'],
                                ['label' => 'Fees Collected', 'value' => '₦12.4M', 'change' => '87% target', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'trend' => 'text-accent']
                            ] as $stat)
                            <div class="relative rounded-xl p-4 bg-white/5 border border-white/5 hover:border-accent/20 transition-all group">
                                <div class="flex items-start justify-between mb-2">
                                    <p class="text-white/40 text-xs font-medium uppercase tracking-wider">{{ $stat['label'] }}</p>
                                    <div class="w-6 h-6 rounded-md bg-accent/10 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-accent/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-xl font-bold text-white mb-1">{{ $stat['value'] }}</p>
                                <p class="{{ $stat['trend'] }} text-xs font-medium">{{ $stat['change'] }}</p>
                            </div>
                            @endforeach
                        </div>

                        {{-- Chart area with gold gradient --}}
                        <div class="rounded-xl p-4 bg-white/5 border border-white/5">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-sm text-white/70 font-medium">Performance Overview</p>
                                    <p class="text-xs text-white/30">Weekly metrics</p>
                                </div>
                                <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-accent/10">
                                    <div class="w-1.5 h-1.5 rounded-full bg-accent"></div>
                                    <p class="text-[10px] text-accent font-semibold">LIVE</p>
                                </div>
                            </div>
                            <div class="flex items-end gap-1.5 h-24">
                                @foreach([55, 72, 85, 65, 92, 78, 88] as $height)
                                <div class="flex-1 rounded-t-lg transition-all hover:opacity-100 opacity-80" 
                                     style="height: {{ $height }}%; background: linear-gradient(to top, rgba(212,168,67,0.8), rgba(212,168,67,0.3), rgba(27,107,62,0.5));">
                                </div>
                                @endforeach
                            </div>
                            <div class="flex justify-between mt-2">
                                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                                <span class="text-[9px] text-white/30 flex-1 text-center font-medium uppercase">{{ $day }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Floating Award Badge (top-right) --}}
                    <div class="absolute -top-6 -right-6 glass-luxury rounded-2xl px-5 py-4 shadow-2xl animate-crown-bob" style="animation-delay: 0.5s;">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #D4A843, #B8912E);">
                                {{-- Crown/award icon --}}
                                <svg class="w-5 h-5 text-dark" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-white font-bold">#1 Rated</p>
                                <p class="text-[10px] text-accent/80 font-medium">EdTech West Africa</p>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Diamond Badge (bottom-left) --}}
                    <div class="absolute -bottom-5 -left-5 glass-luxury rounded-2xl px-5 py-4 shadow-2xl animate-float-delayed">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-light" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                                    </svg>
                                </div>
                                <div class="absolute -top-1 -right-1 w-3 h-3 bg-accent rounded-full flex items-center justify-center">
                                    <svg class="w-2 h-2 text-dark" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-white font-bold">99.9% Uptime</p>
                                <p class="text-[10px] text-white/40 font-medium">Enterprise-grade</p>
                            </div>
                        </div>
                    </div>

                    {{-- Small floating diamond accent --}}
                    <div class="absolute top-1/2 -right-10 w-3 h-3 bg-accent/50 rotate-45 animate-diamond-sparkle" style="animation-delay: 1s;"></div>
                    <div class="absolute top-1/4 -left-8 w-2 h-2 bg-accent/40 rotate-45 animate-diamond-sparkle" style="animation-delay: 2s;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scroll indicator with gold accent --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce">
        <span class="text-[10px] text-accent/60 uppercase tracking-[0.2em] font-medium">Explore</span>
        <div class="w-5 h-8 rounded-full border border-accent/30 flex items-start justify-center pt-1.5">
            <div class="w-1 h-2 bg-accent/60 rounded-full" style="animation: float 2s ease-in-out infinite;"></div>
        </div>
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

    // ============================================
    // HERO CANVAS — Diamond Particle System
    // ============================================
    (function() {
        const canvas = document.getElementById('hero-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let W, H;
        let mouseX = -1000, mouseY = -1000;

        function resize() {
            const section = document.getElementById('hero-section');
            if (!section) return;
            W = canvas.width = section.offsetWidth;
            H = canvas.height = section.offsetHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        // Track mouse for parallax
        const heroSection = document.getElementById('hero-section');
        if (heroSection) {
            heroSection.addEventListener('mousemove', (e) => {
                const rect = heroSection.getBoundingClientRect();
                mouseX = e.clientX - rect.left;
                mouseY = e.clientY - rect.top;
            });
            heroSection.addEventListener('mouseleave', () => {
                mouseX = -1000;
                mouseY = -1000;
            });
        }

        // Color palette
        const GOLD = { r: 212, g: 168, b: 67 };
        const GREEN = { r: 27, g: 107, b: 62 };
        const WHITE = { r: 255, g: 255, b: 255 };
        const colors = [GOLD, GOLD, GOLD, GREEN, GREEN, WHITE];

        // Particle class
        class Particle {
            constructor() { this.reset(true); }
            reset(initial) {
                const col = colors[Math.floor(Math.random() * colors.length)];
                this.r = col.r; this.g = col.g; this.b = col.b;
                this.x = Math.random() * W;
                this.y = initial ? Math.random() * H : H + 20;
                this.size = Math.random() * 2.5 + 0.5;
                this.speedX = (Math.random() - 0.5) * 0.3;
                this.speedY = -(Math.random() * 0.4 + 0.1);
                this.opacity = Math.random() * 0.5 + 0.1;
                this.maxOpacity = this.opacity;
                this.life = 0;
                this.maxLife = Math.random() * 600 + 400;
                this.isDiamond = Math.random() > 0.5;
                this.rotation = Math.random() * Math.PI * 2;
                this.rotSpeed = (Math.random() - 0.5) * 0.02;
                this.wobbleAmp = Math.random() * 20 + 5;
                this.wobbleSpeed = Math.random() * 0.02 + 0.005;
                this.wobbleOffset = Math.random() * Math.PI * 2;
                this.pulseSpeed = Math.random() * 0.03 + 0.01;
            }
            update() {
                this.life++;
                // Fade in / out
                const fadeIn = Math.min(this.life / 60, 1);
                const fadeOut = Math.max((this.maxLife - this.life) / 60, 0);
                this.opacity = this.maxOpacity * Math.min(fadeIn, fadeOut);
                // Pulse
                this.opacity *= 0.7 + 0.3 * Math.sin(this.life * this.pulseSpeed);
                // Move
                this.x += this.speedX + Math.sin(this.life * this.wobbleSpeed + this.wobbleOffset) * 0.3;
                this.y += this.speedY;
                this.rotation += this.rotSpeed;
                // Mouse interaction — gentle repel
                const dx = this.x - mouseX;
                const dy = this.y - mouseY;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 150 && dist > 0) {
                    const force = (150 - dist) / 150 * 0.5;
                    this.x += (dx / dist) * force;
                    this.y += (dy / dist) * force;
                }
                if (this.life >= this.maxLife || this.y < -20 || this.x < -20 || this.x > W + 20) {
                    this.reset(false);
                }
            }
            draw() {
                ctx.save();
                ctx.globalAlpha = this.opacity;
                ctx.translate(this.x, this.y);
                if (this.isDiamond) {
                    ctx.rotate(this.rotation);
                    ctx.fillStyle = `rgba(${this.r},${this.g},${this.b},1)`;
                    ctx.beginPath();
                    const s = this.size;
                    ctx.moveTo(0, -s);
                    ctx.lineTo(s, 0);
                    ctx.lineTo(0, s);
                    ctx.lineTo(-s, 0);
                    ctx.closePath();
                    ctx.fill();
                    // Glow
                    ctx.shadowColor = `rgba(${this.r},${this.g},${this.b},0.6)`;
                    ctx.shadowBlur = this.size * 4;
                    ctx.fill();
                } else {
                    // Circle with glow
                    ctx.fillStyle = `rgba(${this.r},${this.g},${this.b},1)`;
                    ctx.shadowColor = `rgba(${this.r},${this.g},${this.b},0.5)`;
                    ctx.shadowBlur = this.size * 6;
                    ctx.beginPath();
                    ctx.arc(0, 0, this.size, 0, Math.PI * 2);
                    ctx.fill();
                }
                ctx.restore();
            }
        }

        // Create particles
        const particles = [];
        const PARTICLE_COUNT = 45;
        for (let i = 0; i < PARTICLE_COUNT; i++) {
            particles.push(new Particle());
        }

        // Draw subtle connection lines between close particles
        function drawConnections() {
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 120) {
                        const alpha = (1 - dist / 120) * 0.04 * Math.min(particles[i].opacity, particles[j].opacity) / 0.3;
                        ctx.strokeStyle = `rgba(212,168,67,${alpha})`;
                        ctx.lineWidth = 0.5;
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }
        }

        // Animation loop
        function animate() {
            ctx.clearRect(0, 0, W, H);
            drawConnections();
            for (const p of particles) {
                p.update();
                p.draw();
            }
            requestAnimationFrame(animate);
        }
        animate();
    })();
</script>
@endpush
@endsection
