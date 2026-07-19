@extends('layouts.app')

@section('title', 'Offer Response')

@section('body')
<div class="min-h-screen bg-gradient-to-br from-primary/5 via-white to-success/5 flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
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

        {{-- Response Card --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            @if(isset($alreadyResponded) && $alreadyResponded)
                {{-- Already Responded --}}
                <div class="bg-blue-50 border-b border-blue-200 p-8 text-center">
                    <svg class="w-20 h-20 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="text-2xl font-bold text-blue-900 mb-2">Already Responded</h2>
                    <p class="text-gray-600 mb-4">You have already responded to this admission offer.</p>
                    <div class="inline-block px-6 py-3 rounded-xl font-bold" style="background-color: {{ $offer->status === 'accepted' ? '#d4edda' : '#f8d7da' }}; color: {{ $offer->status === 'accepted' ? '#155724' : '#721c24' }};">
                        Status: {{ ucfirst($offer->status) }}
                    </div>
                </div>
            @elseif(isset($accepted) && $accepted)
                {{-- Acceptance Confirmation --}}
                <div class="bg-gradient-to-r from-success to-success-dark p-8 text-center text-white">
                    <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="text-3xl font-bold mb-2">🎉 Congratulations!</h2>
                    <p class="text-white/90 text-lg">Offer Accepted Successfully</p>
                </div>

                <div class="p-8">
                    <div class="bg-success/10 border border-success/30 rounded-2xl p-6 mb-6">
                        <p class="text-gray-700 leading-relaxed">
                            Thank you for accepting the admission offer for <strong class="text-success">{{ $application->first_name }} {{ $application->last_name }}</strong> 
                            into <strong>{{ $offer->offeredClass->name }}</strong>.
                        </p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <h3 class="text-lg font-bold text-dark">Next Steps:</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="text-primary font-bold">1</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-dark">Check Your Email</p>
                                    <p class="text-sm text-gray-600">You will receive a confirmation email with detailed enrollment instructions.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="text-primary font-bold">2</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-dark">Complete Payment</p>
                                    <p class="text-sm text-gray-600">Pay the admission and first term fees as outlined in the fee structure.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="text-primary font-bold">3</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-dark">Submit Documents</p>
                                    <p class="text-sm text-gray-600">Bring original copies of all required documents to the school.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="text-primary font-bold">4</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-dark">Attend Orientation</p>
                                    <p class="text-sm text-gray-600">Details about the orientation program will be sent to you shortly.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-6">
                        <p class="text-sm text-yellow-800">
                            <strong>Important:</strong> The school admissions office will contact you within 2-3 business days 
                            with specific details about payment procedures and enrollment completion.
                        </p>
                    </div>
                </div>
            @else
                {{-- Decline Confirmation --}}
                <div class="bg-gray-100 p-8 text-center">
                    <svg class="w-20 h-20 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-700 mb-2">Offer Declined</h2>
                    <p class="text-gray-600">You have declined the admission offer</p>
                </div>

                <div class="p-8">
                    <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                        <p class="text-gray-700 leading-relaxed">
                            Thank you for considering <strong>{{ $school->name }}</strong> for <strong>{{ $application->first_name }} {{ $application->last_name }}</strong>'s education. 
                            We respect your decision and wish you all the best in finding the right educational institution.
                        </p>
                    </div>

                    <p class="text-sm text-gray-600">
                        If you declined by mistake or would like to reconsider, please contact our admissions office immediately 
                        at <strong class="text-primary">{{ $school->email }}</strong> or <strong class="text-primary">{{ $school->phone }}</strong>.
                    </p>
                </div>
            @endif

            {{-- Contact Section --}}
            <div class="bg-gray-50 p-6 border-t border-gray-200">
                <div class="text-center text-sm text-gray-600">
                    <p class="mb-2"><strong>Need Help?</strong></p>
                    <p>Email: <a href="mailto:{{ $school->email }}" class="text-primary hover:underline">{{ $school->email }}</a></p>
                    <p>Phone: <a href="tel:{{ $school->phone }}" class="text-primary hover:underline">{{ $school->phone }}</a></p>
                </div>
            </div>
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
