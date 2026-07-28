@extends('layouts.app')
@section('title', 'Report Card - ' . $reportCard->student->user->first_name . ' ' . $reportCard->student->user->last_name)

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        .print-area { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 100%; 
            padding: 20mm;
        }
        .no-print { display: none !important; }
        .page-break { page-break-before: always; }
    }

    .report-card-container {
        max-width: 210mm;
        margin: 0 auto;
        background: white;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .grade-a { background: #10b981; color: white; }
    .grade-b { background: #3b82f6; color: white; }
    .grade-c { background: #f59e0b; color: white; }
    .grade-d { background: #ef4444; color: white; }
    .grade-f { background: #991b1b; color: white; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            setTimeout(() => {
                window.print();
            }, 500);
        }
    });
</script>
@endpush

@section('body')
<div class="flex min-h-screen bg-gray-100">
    @if(Auth::user()->role->name === 'Student')
        @include('partials.student_sidebar')
    @elseif(Auth::user()->role->name === 'Teacher')
        @include('partials.teacher_sidebar')
    @elseif(Auth::user()->role->name === 'Guardian')
        @include('partials.guardian_sidebar')
    @else
        @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name)])
    @endif
    
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Action Bar - No Print --}}
            <div class="no-print mb-6 flex items-center justify-between flex-wrap gap-4">
                <a href="{{ route('report-cards.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Report Cards
                </a>
                
                <div class="flex gap-3">
                    @if(!in_array(Auth::user()->role->name ?? '', ['Student', 'Guardian']))
                    <a href="{{ route('report-cards.edit', $reportCard->id) }}" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-semibold text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    @endif
                    <button onclick="window.print()" 
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-semibold text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print Report
                    </button>
                </div>
            </div>

            {{-- Report Card - Printable Area --}}
            <div class="print-area report-card-container">
                {{-- School Header --}}
                <div class="bg-gradient-to-r from-primary to-primary-dark text-white p-8 text-center border-b-4 border-accent">
                    <div class="flex items-center justify-center gap-6 mb-4">
                        @if($school->logo)
                        <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="w-20 h-20 object-contain bg-white rounded-full p-2">
                        @else
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-primary text-3xl font-bold">
                            {{ substr($school->name, 0, 1) }}
                        </div>
                        @endif
                        <div class="text-left">
                            <h1 class="text-3xl font-bold mb-1">{{ $school->name }}</h1>
                            <p class="text-sm text-white/90">{{ $school->address }}</p>
                            <p class="text-sm text-white/90">{{ $school->phone }} | {{ $school->email }}</p>
                            @if($school->motto)
                            <p class="text-xs text-accent-light italic mt-1">"{{ $school->motto }}"</p>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-2 inline-block">
                        <h2 class="text-xl font-bold">TERMINAL REPORT CARD</h2>
                    </div>
                </div>

                {{-- Student Information --}}
                <div class="p-8 bg-gray-50 border-b-2 border-gray-200">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="font-bold text-gray-700 w-40">Student Name:</span>
                                <span class="text-gray-900 uppercase font-semibold">{{ $reportCard->student->user->first_name }} {{ $reportCard->student->user->last_name }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-bold text-gray-700 w-40">Admission Number:</span>
                                <span class="text-gray-900">{{ $reportCard->student->admission_number }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-bold text-gray-700 w-40">Class:</span>
                                <span class="text-gray-900 font-semibold">{{ $reportCard->schoolClass->name }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-bold text-gray-700 w-40">Gender:</span>
                                <span class="text-gray-900">{{ ucfirst($reportCard->student->gender ?? 'N/A') }}</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="font-bold text-gray-700 w-40">Session:</span>
                                <span class="text-gray-900 font-semibold">{{ $reportCard->session->name }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-bold text-gray-700 w-40">Term:</span>
                                <span class="text-gray-900 font-semibold">{{ $reportCard->term->name }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-bold text-gray-700 w-40">No. in Class:</span>
                                <span class="text-gray-900">{{ $classStats['total_students'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex">
                                <span class="font-bold text-gray-700 w-40">Attendance:</span>
                                <span class="text-gray-900">{{ $reportCard->attendance ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Academic Performance Table --}}
                <div class="p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b-2 border-gray-300 pb-2">ACADEMIC PERFORMANCE</h3>
                    
                    <table class="w-full border-collapse border-2 border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-gray-300 px-3 py-2 text-left font-bold">SUBJECT</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-bold w-20">CA (40)</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-bold w-20">EXAM (60)</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-bold w-20">TOTAL (100)</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-bold w-16">GRADE</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-bold w-20">TEACHER</th>
                                <th class="border border-gray-300 px-3 py-2 text-center font-bold">REMARKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalScore = 0;
                                $totalSubjects = 0;
                            @endphp
                            @forelse($results as $result)
                            @php
                                $totalScore += $result->total_score;
                                $totalSubjects++;
                                $gradeClass = 'grade-' . strtolower($result->grade);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 px-3 py-2 font-semibold">{{ $result->subject->name ?? 'N/A' }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">{{ number_format($result->ca_score, 0) }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">{{ number_format($result->exam_score, 0) }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-center font-bold">{{ number_format($result->total_score, 0) }}</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    <span class="px-2 py-1 rounded font-bold {{ $gradeClass }}">{{ $result->grade }}</span>
                                </td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    @if($result->recordedBy)
                                    <div class="flex flex-col items-center">
                                        <span class="font-bold text-primary text-lg">{{ $result->getTeacherSignature() }}</span>
                                        <span class="text-xs text-gray-500">{{ $result->recordedBy->first_name ?? '' }}</span>
                                    </div>
                                    @else
                                    <span class="text-gray-400 text-xs">N/A</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-3 py-2 text-center text-xs">
                                    @if($result->grade == 'A') Excellent
                                    @elseif($result->grade == 'B') Very Good
                                    @elseif($result->grade == 'C') Good
                                    @elseif($result->grade == 'D') Fair
                                    @else Poor
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="border border-gray-300 px-3 py-4 text-center text-gray-500 italic">
                                    No results recorded for this term
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($totalSubjects > 0)
                        <tfoot>
                            <tr class="bg-gray-100 font-bold">
                                <td class="border border-gray-300 px-3 py-2 text-right" colspan="3">TOTAL / AVERAGE:</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">{{ number_format($reportCard->average, 2) }}%</td>
                                <td class="border border-gray-300 px-3 py-2 text-center" colspan="3">
                                    <span class="text-primary">{{ $reportCard->getGradeLabel() }}</span>
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>

                    {{-- Grading Key --}}
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h4 class="font-bold text-sm text-gray-700 mb-2">GRADING SYSTEM:</h4>
                        <div class="grid grid-cols-5 gap-3 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded font-bold grade-a">A</span>
                                <span class="text-gray-600">70-100 (Excellent)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded font-bold grade-b">B</span>
                                <span class="text-gray-600">60-69 (Very Good)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded font-bold grade-c">C</span>
                                <span class="text-gray-600">50-59 (Good)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded font-bold grade-d">D</span>
                                <span class="text-gray-600">40-49 (Fair)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded font-bold grade-f">F</span>
                                <span class="text-gray-600">0-39 (Poor)</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Performance Summary --}}
                <div class="px-8 pb-6">
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-blue-600 font-semibold mb-1">OVERALL AVERAGE</p>
                            <p class="text-3xl font-bold text-blue-700">{{ number_format($reportCard->average, 1) }}%</p>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-amber-600 font-semibold mb-1">POSITION IN CLASS</p>
                            <p class="text-3xl font-bold text-amber-700">{{ $reportCard->getPositionSuffix() }}</p>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-green-600 font-semibold mb-1">CLASS AVERAGE</p>
                            <p class="text-3xl font-bold text-green-700">{{ number_format($classStats['class_average'] ?? 0, 1) }}%</p>
                        </div>
                    </div>
                </div>

                {{-- Comments Section --}}
                <div class="px-8 pb-6">
                    <div class="grid grid-cols-1 gap-4">
                        {{-- Teacher's Comment --}}
                        <div class="border-2 border-gray-300 rounded-lg p-4">
                            <h4 class="font-bold text-sm text-gray-700 mb-2">CLASS TEACHER'S COMMENT:</h4>
                            <p class="text-gray-800 text-sm leading-relaxed">
                                {{ $reportCard->teacher_comment ?? 'No comment provided.' }}
                            </p>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="border-t-2 border-gray-800 pt-2 text-xs font-semibold">
                                    {{ $reportCard->generatedBy->first_name ?? '' }} {{ $reportCard->generatedBy->last_name ?? '' }}
                                </div>
                                <div class="text-xs text-gray-600">
                                    Date: {{ $reportCard->generated_at ? $reportCard->generated_at->format('d/m/Y') : date('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        {{-- Principal's Comment --}}
                        <div class="border-2 border-gray-300 rounded-lg p-4">
                            <h4 class="font-bold text-sm text-gray-700 mb-2">PRINCIPAL'S COMMENT:</h4>
                            <p class="text-gray-800 text-sm leading-relaxed">
                                {{ $reportCard->principal_comment ?? 'Pending principal\'s review.' }}
                            </p>
                            <div class="mt-4 border-t-2 border-gray-800 pt-2 inline-block">
                                <p class="text-xs font-semibold">Principal's Signature & Stamp</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Next Term Information --}}
                <div class="px-8 pb-6">
                    <div class="bg-gray-100 border border-gray-300 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-bold text-gray-700">Next Term Begins:</span>
                                <span class="text-gray-900 ml-2">{{ $reportCard->term->resumption_date ? date('F j, Y', strtotime($reportCard->term->resumption_date)) : 'To be announced' }}</span>
                            </div>
                            <div>
                                <span class="font-bold text-gray-700">School Fees Status:</span>
                                <span class="text-green-600 ml-2 font-semibold">Paid</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="bg-gray-800 text-white text-center py-4 text-xs">
                    <p>This is an official document from {{ $school->name }}</p>
                    <p class="mt-1">Generated on {{ now()->format('F j, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
