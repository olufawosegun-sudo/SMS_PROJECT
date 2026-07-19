@extends('layouts.app')

@section('title', 'Admission Offer - ' . $school->name)

@section('body')
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-white to-success/5 flex items-center justify-center p-4">
    <div class="max-w-3xl w-full">
        {{-- School Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-dark mb-2">{{ $school->name }}</h1>
            <p class="text-gray-500">{{ $school->city }}, {{ $school->state }}</p>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            @if($isExpired)
                {{-- Expired Offer --}}
                <div class="bg-danger/10 border-b border-danger/20 p-6 text-center">
                    <svg class="w-16 h-16 text-danger mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="text-2xl font-bold text-danger mb-2">Offer Expired</h2>
                    <p class="text-gray-600">This admission offer expired on {{ $expiryDate->format('F d, Y') }}</p>
                    <p class="text-sm text-gray-500 mt-2">Please contact the school admissions office for assistance.</p>
                </div>
            @else
                {{-- Success Header --}}
                <div class="bg-gradient-to-r from-success to-success-dark p-8 text-center text-white">
                    <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="text-3xl font-bold mb-2">🎓 Congratulations!</h2>
                    <p class="text-white/90 text-lg">Admission Offer</p>
                </div>

                {{-- Offer Details --}}
                <div class="p-8">
                    <div class="mb-6">
                        <p class="text-gray-600 mb-4">Dear <strong class="text-dark">{{ $application->guardian_name }}</strong>,</p>
                        <p class="text-gray-600 leading-relaxed">
                            We are delighted to inform you that <strong class="text-primary">{{ $application->first_name }} {{ $application->last_name }}</strong> 
                            has been offered admission into <strong>{{ $offeredClass->name }}</strong> at {{ $school->name }}.
                        </p>
                    </div>

                    {{-- Student Details Box --}}
                    <div class="bg-primary/5 rounded-2xl p-6 mb-6 border-l-4 border-primary">
                        <h3 class="text-lg font-bold text-dark mb-4">Student Details</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase mb-1">Application Number</p>
                                <p class="font-semibold text-primary">{{ $application->application_no }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase mb-1">Student Name</p>
                                <p class="font-semibold text-dark">{{ $application->first_name }} {{ $application->last_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase mb-1">Class Offered</p>
                                <p class="font-semibold text-success">{{ $offeredClass->name }} ({{ $offeredClass->level }})</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase mb-1">Offer Date</p>
                                <p class="font-semibold text-dark">{{ $offer->offer_date->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Deadline Notice --}}
                    <div class="bg-warning/10 border border-warning/30 rounded-2xl p-6 mb-6 text-center">
                        <p class="text-sm text-gray-600 mb-2">Response Deadline</p>
                        <p class="text-2xl font-bold text-warning mb-2">{{ $expiryDate->format('F d, Y') }}</p>
                        <p class="text-xs text-gray-500">
                            {{ now()->diffInDays($expiryDate) }} days remaining to accept this offer
                        </p>
                    </div>

                    {{-- Acceptance Form --}}
                    <form action="{{ route('offer.respond', $offer->id) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="bg-gray-50 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-dark mb-4">Your Response</h3>
                            
                            <div class="space-y-4">
                                <label class="flex items-start p-4 border-2 border-success rounded-xl cursor-pointer hover:bg-success/5 transition-all">
                                    <input type="radio" name="response" value="accept" required class="mt-1 mr-3">
                                    <div class="flex-1">
                                        <span class="font-semibold text-success text-lg">✓ I Accept This Offer</span>
                                        <p class="text-sm text-gray-600 mt-1">I confirm my acceptance of the admission offer and agree to complete the enrollment process.</p>
                                    </div>
                                </label>

                                <label class="flex items-start p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 transition-all">
                                    <input type="radio" name="response" value="decline" required class="mt-1 mr-3">
                                    <div class="flex-1">
                                        <span class="font-semibold text-gray-700 text-lg">✗ I Decline This Offer</span>
                                        <p class="text-sm text-gray-600 mt-1">I respectfully decline the admission offer at this time.</p>
                                    </div>
                                </label>
                            </div>

                            @error('response')
                                <p class="text-danger text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirmation Checkbox --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                            <label class="flex items-start cursor-pointer">
                                <input type="checkbox" name="guardian_confirmation" required class="mt-1 mr-3">
                                <div class="flex-1">
                                    <span class="font-semibold text-dark">I confirm that I am the parent/guardian</span>
                                    <p class="text-sm text-gray-600 mt-1">
                                        I, <strong>{{ $application->guardian_name }}</strong>, confirm that I am the parent or legal guardian of 
                                        <strong>{{ $application->first_name }} {{ $application->last_name }}</strong> and have the authority to make this decision.
                                    </p>
                                </div>
                            </label>
                            @error('guardian_confirmation')
                                <p class="text-danger text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit" class="w-full py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2">
                            <span>Submit Response</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>

                    {{-- Contact Info --}}
                    <div class="mt-8 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
                        <p>Questions? Contact us at <strong class="text-primary">{{ $school->email }}</strong> or <strong class="text-primary">{{ $school->phone }}</strong></p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Back to Home --}}
        <div class="text-center mt-6">
            <a href="{{ route('landing') }}" class="text-gray-600 hover:text-primary transition-colors text-sm font-semibold">
                ← Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
