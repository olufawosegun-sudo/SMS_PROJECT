@extends('layouts.app')

@section('title', 'Job Applications & Recruitment — ' . $school->name)

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name ?? 'owner')])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            <!-- Header & Careers Portal Link -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-dark mb-1">Staff Recruitment & Job Applications</h1>
                    <p class="text-sm text-gray-500">Review, shortlist, and hire candidates from your school careers portal</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="bg-white px-4 py-2 rounded-xl border border-gray-200 text-xs flex items-center gap-2 shadow-sm">
                        <span class="text-gray-400 font-semibold">Public Careers URL:</span>
                        <span class="font-mono text-primary font-bold truncate max-w-[200px] sm:max-w-xs">{{ $school->careers_url }}</span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $school->careers_url }}'); alert('Careers portal URL copied to clipboard!');" class="p-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors" title="Copy Careers URL">
                            📋
                        </button>
                    </div>

                    <a href="{{ $school->careers_url }}" target="_blank" class="px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors text-xs font-bold flex items-center gap-1.5 shadow-sm">
                        <span>Visit Careers Page</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold text-sm">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-danger/10 border border-danger/20 rounded-xl flex items-center gap-3">
                <p class="text-danger font-semibold text-sm">{{ session('error') }}</p>
            </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-100 shadow-sm">
                    <span class="text-xs text-gray-400 font-semibold block mb-1">Total Applicants</span>
                    <span class="text-2xl font-bold text-dark">{{ $stats['total'] }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-amber-100 shadow-sm">
                    <span class="text-xs text-amber-600 font-semibold block mb-1">Pending Review</span>
                    <span class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-blue-100 shadow-sm">
                    <span class="text-xs text-blue-600 font-semibold block mb-1">Shortlisted</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $stats['shortlisted'] }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-emerald-100 shadow-sm">
                    <span class="text-xs text-emerald-600 font-semibold block mb-1">Approved / Hired</span>
                    <span class="text-2xl font-bold text-emerald-600">{{ $stats['approved'] }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-rose-100 shadow-sm">
                    <span class="text-xs text-rose-600 font-semibold block mb-1">Rejected</span>
                    <span class="text-2xl font-bold text-rose-600">{{ $stats['rejected'] }}</span>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white rounded-2xl p-4 mb-6 border border-gray-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <form method="GET" action="{{ route('job-applications.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                    <div class="relative flex-1 min-w-[240px]">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by applicant name, email, or role..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <select name="status" onchange="this.form.submit()" class="px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                        <option value="interviewed" {{ request('status') === 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>

                    <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-primary-dark transition-colors">
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('job-applications.index') }}" class="px-3.5 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-sm hover:bg-gray-200 transition-colors">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Applications Table -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                @if($applications->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50/80 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <tr>
                                    <th class="py-3.5 px-6">Applicant Name</th>
                                    <th class="py-3.5 px-6">Position Applied</th>
                                    <th class="py-3.5 px-6">Qualification & Exp.</th>
                                    <th class="py-3.5 px-6">Submitted</th>
                                    <th class="py-3.5 px-6">Status</th>
                                    <th class="py-3.5 px-6 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($applications as $app)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-dark">{{ $app->full_name }}</div>
                                            <div class="text-xs text-gray-400">{{ $app->email }} &bull; {{ $app->phone }}</div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="font-semibold text-dark">{{ $app->position_applied }}</span>
                                            @if($app->specialization)
                                                <div class="text-xs text-gray-400">{{ $app->specialization }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-xs font-semibold text-gray-700">{{ $app->qualification }}</div>
                                            <div class="text-xs text-gray-400">{{ $app->years_of_experience }} years exp.</div>
                                        </td>
                                        <td class="py-4 px-6 text-xs text-gray-500">
                                            {{ $app->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @php
                                                $badges = [
                                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'shortlisted' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                    'interviewed' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $badges[$app->status] ?? 'bg-gray-50 text-gray-600' }}">
                                                {{ ucfirst($app->status) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                @if($app->resume_cv)
                                                    <a href="{{ route('job-applications.download-resume', $app->id) }}" class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition-colors" title="Download CV">
                                                        📄 CV
                                                    </a>
                                                @endif
                                                <a href="{{ route('job-applications.show', $app->id) }}" class="px-3.5 py-1.5 bg-primary text-white rounded-xl text-xs font-semibold hover:bg-primary-dark transition-colors">
                                                    View Details
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-gray-100">
                        {{ $applications->links() }}
                    </div>
                @else
                    <div class="text-center py-12 p-6">
                        <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-2xl mb-3">
                            📋
                        </div>
                        <h3 class="font-bold text-dark text-base">No Job Applications Found</h3>
                        <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
                            When applicants apply on your school careers portal, their profiles and resumes will appear here.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
