@extends('layouts.app')

@section('title', 'Applicant: ' . $application->full_name)

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name ?? 'owner')])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <a href="{{ route('job-applications.index') }}" class="text-xs font-semibold text-gray-500 hover:text-primary transition-colors flex items-center gap-1 mb-2">
                        &larr; Back to Applications List
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-bold text-dark">{{ $application->full_name }}</h1>
                    <p class="text-sm text-gray-500">Applied for <strong class="text-dark">{{ $application->position_applied }}</strong> &bull; Submitted {{ $application->created_at->format('M d, Y h:i A') }}</p>
                </div>

                <div class="flex items-center gap-3">
                    @if($application->resume_cv)
                        <a href="{{ route('job-applications.download-resume', $application->id) }}" class="px-4 py-2.5 bg-primary text-white rounded-xl text-xs font-bold shadow-sm hover:bg-primary-dark transition-all flex items-center gap-2">
                            <span>Download Resume (CV)</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold text-sm">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Application Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Profile card -->
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-6">
                        <h3 class="font-bold text-dark text-base pb-3 border-b border-gray-100">Candidate Information</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-gray-400 font-semibold block mb-1">EMAIL ADDRESS</span>
                                <span class="text-dark font-medium text-sm">{{ $application->email }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 font-semibold block mb-1">PHONE NUMBER</span>
                                <span class="text-dark font-medium text-sm">{{ $application->phone }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 font-semibold block mb-1">GENDER</span>
                                <span class="text-dark font-medium text-sm">{{ ucfirst($application->gender ?? 'Not specified') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 font-semibold block mb-1">DATE OF BIRTH</span>
                                <span class="text-dark font-medium text-sm">{{ $application->date_of_birth ? $application->date_of_birth->format('M d, Y') : 'Not specified' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Background -->
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-6">
                        <h3 class="font-bold text-dark text-base pb-3 border-b border-gray-100">Professional Background</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-gray-400 font-semibold block mb-1">QUALIFICATION</span>
                                <span class="text-dark font-medium text-sm">{{ $application->qualification }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 font-semibold block mb-1">SPECIALIZATION / SUBJECT</span>
                                <span class="text-dark font-medium text-sm">{{ $application->specialization ?? 'General' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400 font-semibold block mb-1">EXPERIENCE</span>
                                <span class="text-dark font-medium text-sm">{{ $application->years_of_experience }} Year(s)</span>
                            </div>
                            <div>
                                <span class="text-gray-400 font-semibold block mb-1">PREVIOUS EMPLOYER</span>
                                <span class="text-dark font-medium text-sm">{{ $application->previous_employer ?? 'None' }}</span>
                            </div>
                            @if($application->expected_salary)
                            <div>
                                <span class="text-gray-400 font-semibold block mb-1">EXPECTED SALARY</span>
                                <span class="text-dark font-medium text-sm">{{ number_format($application->expected_salary, 2) }}</span>
                            </div>
                            @endif
                        </div>

                        @if($application->cover_letter)
                            <div class="pt-4 border-t border-gray-100">
                                <span class="text-gray-400 font-semibold text-xs block mb-2">COVER LETTER / STATEMENT</span>
                                <div class="bg-gray-50 p-4 rounded-xl text-xs text-gray-700 leading-relaxed">
                                    {!! nl2br(e($application->cover_letter)) !!}
                                </div>
                            </div>
                        @endif

                        @if($application->certificates)
                            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-dark text-xs block">Attached Certificates / Credentials</span>
                                    <span class="text-[11px] text-gray-400">Additional uploaded document</span>
                                </div>
                                <a href="{{ route('job-applications.download-certificates', $application->id) }}" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold">
                                    Download Certificates
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Status Update Card -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-6">
                        <h3 class="font-bold text-dark text-base pb-3 border-b border-gray-100">Application Status</h3>

                        @php
                            $badges = [
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'shortlisted' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'interviewed' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                            ];
                        @endphp
                        <div>
                            <span class="text-xs text-gray-400 block mb-1">Current Status:</span>
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold border {{ $badges[$application->status] ?? 'bg-gray-50 text-gray-600' }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>

                        @if($application->approvedByUser)
                            <p class="text-xs text-gray-500">
                                Approved by: <strong>{{ $application->approvedByUser->name }}</strong> on {{ $application->approved_at?->format('M d, Y') }}
                            </p>
                        @endif

                        <form action="{{ route('job-applications.status', $application->id) }}" method="POST" class="space-y-4 pt-2">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Change Status</label>
                                <select name="status" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                    <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted for Interview</option>
                                    <option value="interviewed" {{ $application->status === 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                                    <option value="approved" {{ $application->status === 'approved' ? 'selected' : '' }}>Approve / Hire Candidate</option>
                                    <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Reject Application</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Reviewer Notes / Rejection Reason</label>
                                <textarea name="rejection_reason" rows="3" placeholder="Optional notes or feedback..." class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary">{{ $application->rejection_reason }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-primary text-white rounded-xl text-xs font-bold hover:bg-primary-dark transition-colors shadow-sm">
                                Update Status
                            </button>
                        </form>
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-3">
                        <h4 class="font-bold text-dark text-xs uppercase tracking-wider">Actions</h4>
                        <form action="{{ route('job-applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this application?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-2.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-100 transition-colors">
                                Delete Application
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
