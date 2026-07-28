@extends('layouts.app')

@section('title', 'Guardian Dashboard — ' . ($school->name ?? 'SMS Project'))

@section('body')
<div class="flex min-h-screen bg-gray-50/50">
    {{-- ======================================== SIDEBAR ======================================== --}}
    @include('partials.guardian_sidebar')

    {{-- ======================================== MAIN CONTENT ======================================== --}}
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        {{-- Top Bar --}}
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8 max-w-7xl mx-auto space-y-6">
            {{-- Security / Password Reminder --}}
            @if(Hash::check('password123', Auth::user()->password))
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-xl p-4 shadow-sm" id="passwordReminder">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-amber-500/10 rounded-lg text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-amber-900">Security Recommendation</h3>
                            <p class="text-xs text-amber-700 mt-0.5">You are logged in with a default password. Please update your account password for account security.</p>
                            <div class="mt-2.5 flex items-center gap-3">
                                <a href="{{ route('password.request') }}" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                                    Change Password
                                </a>
                                <button onclick="document.getElementById('passwordReminder').remove()" class="text-xs font-medium text-amber-700 hover:text-amber-900">
                                    Dismiss
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Welcome Banner --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-green-800 via-emerald-700 to-teal-800 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-green-900/10">
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-semibold text-green-100 border border-white/10">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            Parent Portal • {{ $school->name ?? 'School Management System' }}
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Welcome, {{ Auth::user()->first_name }}! 👋</h1>
                        <p class="text-green-100/80 text-sm max-w-xl">Track your child's academic performance, check report cards, monitor attendance, and communicate with the school management.</p>
                    </div>

                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/15">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-lg font-bold text-white shadow-inner">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-green-200">Account Type</p>
                            <p class="text-sm font-bold text-white">Parent / Guardian</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Links & Modules --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span>⚡ Parent Tools</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="{{ route('results.index') }}" class="group p-4 bg-gray-50 hover:bg-green-50/60 rounded-2xl border border-gray-100 hover:border-green-200 transition-all text-left block">
                        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-700 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900 group-hover:text-green-800">Ward Results</p>
                        <p class="text-xs text-gray-500 mt-0.5">Terminal scores & subject grades</p>
                    </a>

                    <a href="{{ route('report-cards.index') }}" class="group p-4 bg-gray-50 hover:bg-blue-50/60 rounded-2xl border border-gray-100 hover:border-blue-200 transition-all text-left block">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900 group-hover:text-blue-800">Report Cards</p>
                        <p class="text-xs text-gray-500 mt-0.5">Approved terminal report cards</p>
                    </a>

                    <a href="{{ route('messages.index') }}" class="group p-4 bg-gray-50 hover:bg-purple-50/60 rounded-2xl border border-gray-100 hover:border-purple-200 transition-all text-left block">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-gray-900 group-hover:text-purple-800">School Messages</p>
                        <p class="text-xs text-gray-500 mt-0.5">Communicate with teachers & Principal</p>
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
