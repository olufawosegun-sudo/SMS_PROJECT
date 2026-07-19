@extends('layouts.app')

@section('title', 'Student Documents - ' . ($school->name ?? 'EduWest Africa'))

@section('body')
<div class="flex min-h-screen bg-surface">
    {{-- Sidebar --}}
    @include('partials.sidebar', ['role' => $role])

    {{-- Main Content --}}
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        {{-- Top Bar --}}
        @include('partials.topbar')

        {{-- Page Content --}}
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Page Header --}}
            <div class="mb-6 md:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-dark mb-2">Student Documents</h1>
                        <p class="text-gray-500">Manage and monitor all student documentation</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('students.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            View Students
                        </a>
                    </div>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
                @foreach([
                    ['label' => 'Total Documents', 'value' => $stats['total_documents'], 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'info', 'description' => 'Uploaded'],
                    ['label' => 'Missing Birth Certificates', 'value' => $stats['missing_birth_certificates'], 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => 'warning', 'description' => 'Students'],
                    ['label' => 'Expiring Soon', 'value' => $stats['expiring_soon'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'accent', 'description' => 'In 30 Days'],
                    ['label' => 'Expired Documents', 'value' => $stats['expired'], 'icon' => 'M6 18L18 6M6 6l12 12', 'color' => 'danger', 'description' => 'Needs Renewal'],
                ] as $card)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-{{ $card['color'] }}/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-{{ $card['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-{{ $card['color'] }} bg-{{ $card['color'] }}/10 px-2.5 py-1 rounded-full uppercase">{{ $card['description'] }}</span>
                    </div>
                    <p class="text-3xl font-extrabold text-dark mb-1">{{ $card['value'] }}</p>
                    <p class="text-sm text-gray-400">{{ $card['label'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Filter and Search --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-6 md:mb-8">
                <form method="GET" action="{{ route('student-documents.all') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Student</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Admission No..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Document Type</label>
                        <select name="document_type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="">All Types</option>
                            <option value="birth_certificate" {{ request('document_type') == 'birth_certificate' ? 'selected' : '' }}>Birth Certificate</option>
                            <option value="passport_photo" {{ request('document_type') == 'passport_photo' ? 'selected' : '' }}>Passport Photo</option>
                            <option value="previous_school_record" {{ request('document_type') == 'previous_school_record' ? 'selected' : '' }}>Previous School Record</option>
                            <option value="medical_record" {{ request('document_type') == 'medical_record' ? 'selected' : '' }}>Medical Record</option>
                            <option value="immunization_card" {{ request('document_type') == 'immunization_card' ? 'selected' : '' }}>Immunization Card</option>
                            <option value="transfer_certificate" {{ request('document_type') == 'transfer_certificate' ? 'selected' : '' }}>Transfer Certificate</option>
                            <option value="parent_id" {{ request('document_type') == 'parent_id' ? 'selected' : '' }}>Parent ID</option>
                            <option value="residence_permit" {{ request('document_type') == 'residence_permit' ? 'selected' : '' }}>Residence Permit</option>
                            <option value="other" {{ request('document_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="expiring" {{ request('status') == 'expiring' ? 'selected' : '' }}>Expiring Soon</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-primary-dark transition-all font-medium">
                            Filter
                        </button>
                        <a href="{{ route('student-documents.all') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-medium">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Documents Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-dark">All Student Documents ({{ $documents->total() }})</h3>
                        <span class="text-sm text-gray-500">Showing {{ $documents->firstItem() ?? 0 }} - {{ $documents->lastItem() ?? 0 }} of {{ $documents->total() }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr class="border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Document Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">File Size</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Uploaded</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($documents as $document)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                            {{ substr($document->student->user->first_name ?? 'S', 0, 1) }}{{ substr($document->student->user->last_name ?? 'T', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-dark">{{ $document->student->user->first_name ?? 'N/A' }} {{ $document->student->user->last_name ?? '' }}</p>
                                            <p class="text-xs text-gray-500">{{ $document->student->admission_no ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700">{{ $document->student->schoolClass->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-700">{{ $document->getDocumentTypeLabel() }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">{{ $document->getFormattedFileSize() }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm text-gray-700">{{ $document->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $document->created_at->format('h:i A') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($document->isExpired())
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-danger bg-danger/10 px-2.5 py-1 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                            Expired
                                        </span>
                                    @elseif($document->isExpiringSoon())
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-warning bg-warning/10 px-2.5 py-1 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                            Expiring Soon
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-success bg-success/10 px-2.5 py-1 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('student-documents.view', $document->id) }}" class="p-2 text-info hover:bg-info/10 rounded-lg transition-colors" title="View Document">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('student-documents.download', $document->id) }}" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Download">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                        @if(Auth::user()->role->name === 'Owner')
                                        <form method="POST" action="{{ route('student-documents.destroy', $document->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-danger hover:bg-danger/10 rounded-lg transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-lg font-semibold text-gray-600 mb-1">No documents found</p>
                                        <p class="text-sm text-gray-400">Documents will appear here when guardians upload them during admission</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($documents->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $documents->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
