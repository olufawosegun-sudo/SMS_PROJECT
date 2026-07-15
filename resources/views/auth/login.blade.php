@extends('layouts.app')

@section('title', 'Log In — EduWest Africa')

@section('body')
<div class="min-h-screen flex">
    {{-- Left Panel: Luxury Branding --}}
    <div class="hidden lg:flex lg:w-1/2 gradient-hero-luxury relative items-center justify-center p-12 overflow-hidden">
        {{-- Layered background effects --}}
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[400px] rounded-full blur-[140px] opacity-[0.08]" style="background: radial-gradient(circle, #D4A843, transparent 70%);"></div>
        <div class="absolute -bottom-20 -left-20 w-[500px] h-[400px] rounded-full blur-[120px] opacity-[0.1] animate-float" style="background: radial-gradient(ellipse, #1B6B3E, transparent 70%);"></div>
        <div class="absolute top-1/3 -right-10 w-[400px] h-[400px] rounded-full blur-[130px] opacity-[0.06] animate-float-delayed" style="background: radial-gradient(ellipse, #D4A843, transparent 70%);"></div>

        {{-- Diamond sparkle particles --}}
        @for($i = 0; $i < 12; $i++)
        <div class="absolute w-1 h-1 bg-accent/50 rotate-45" style="left: {{ rand(5, 95) }}%; top: {{ rand(5, 95) }}%; animation: diamond-sparkle {{ rand(25, 45) / 10 }}s ease-in-out infinite {{ rand(0, 30) / 10 }}s;"></div>
        @endfor

        {{-- Aurora wave bands --}}
        <div class="absolute top-[30%] left-0 w-full h-[1px] opacity-[0.05]" style="background: linear-gradient(90deg, transparent, #D4A843 30%, #1B6B3E 70%, transparent); filter: blur(2px);"></div>
        <div class="absolute top-[65%] left-0 w-full h-[1px] opacity-[0.04]" style="background: linear-gradient(90deg, transparent, #1B6B3E 40%, #D4A843 60%, transparent); filter: blur(2px);"></div>

        {{-- Noise texture --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.012] mix-blend-overlay" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 256 256%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22n%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.9%22 numOctaves=%224%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23n)%22/%3E%3C/svg%3E'); background-size: 256px 256px;"></div>

        {{-- Gold edge accents --}}
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-accent/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-accent/15 to-transparent"></div>
        <div class="absolute top-0 right-0 w-px h-full bg-gradient-to-b from-transparent via-accent/10 to-transparent"></div>

        {{-- Vignette --}}
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(ellipse 80% 70% at 50% 50%, transparent 30%, rgba(5,5,16,0.4) 100%);"></div>

        <div class="relative z-10 max-w-md text-center">
            {{-- Logo --}}
            <div class="flex items-center justify-center gap-3 mb-10">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-2xl" style="background: linear-gradient(135deg, #D4A843, #B8912E);">
                    <svg class="w-8 h-8 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <span class="text-3xl font-bold text-white">Edu<span class="text-accent">West</span></span>
                    <span class="block text-xs text-white/50 -mt-1 tracking-widest uppercase">Africa</span>
                </div>
            </div>

            <h1 class="text-3xl font-extrabold text-white mb-4 leading-tight">
                Welcome Back to Your <span class="text-gradient-gold">School Dashboard</span>
            </h1>
            <p class="text-white/50 leading-relaxed mb-10">
                Access student records, manage attendance, view grades, and keep your school running efficiently — all from one place.
            </p>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4">
                @foreach([
                    ['2,000+', 'Schools'],
                    ['500K+', 'Students'],
                    ['15+', 'Countries'],
                ] as $stat)
                <div class="glass-luxury rounded-xl p-4 text-center">
                    <p class="text-xl font-bold text-gradient-gold">{{ $stat[0] }}</p>
                    <p class="text-xs text-white/40">{{ $stat[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right Panel: Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-white">
        <div class="w-full max-w-md animate-fade-in-up">
            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-dark">Edu<span class="text-primary">West</span> Africa</span>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-dark mb-2">Log in to your account</h2>
                <p class="text-gray-500">Enter your credentials to access the dashboard.</p>
            </div>

            {{-- Error Messages --}}
            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center gap-2 text-red-700">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ $errors->first() }}</p>
                </div>
            </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5" id="login-form">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                               placeholder="admin@sms.com">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs font-medium text-primary hover:text-primary-dark transition-colors">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                               class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all"
                               placeholder="••••••••">
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" id="remember"
                               class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/30">
                        <span class="text-sm text-gray-600 group-hover:text-gray-800 transition-colors">Remember me</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" id="login-submit"
                        class="w-full py-3.5 bg-primary text-white font-bold text-base rounded-xl hover:bg-primary-dark transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>Sign In</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-400">Don't have an account?</span>
                </div>
            </div>

            {{-- Register Link --}}
            <a href="{{ route('register') }}" id="go-to-register"
               class="w-full py-3.5 border-2 border-primary/20 text-primary font-semibold text-base rounded-xl hover:bg-primary/5 transition-all flex items-center justify-center gap-2">
                <span>Create an Account</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </a>

            {{-- Back to landing --}}
            <p class="text-center mt-6">
                <a href="{{ route('landing') }}" class="text-sm text-gray-400 hover:text-primary transition-colors">← Back to homepage</a>
            </p>
        </div>
    </div>
</div>
@endsection
