@extends('layouts.app')
@section('title', 'Admissions Management')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Admissions Management</h1>
                    <p class="text-gray-500">Review and manage student admission applications</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                @foreach([
                    ['label' => 'Total Applications', 'value' => $applications->count(), 'color' => 'primary', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['label' => 'Pending Review', 'value' => $applications->where('status', 'submitted')->count(), 'color' => 'warning', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Offers Sent', 'value' => $applications->where('status', 'offered')->count(), 'color' => 'success', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Accepted', 'value' => $applications->filter(fn($app) => $app->offer && $app->offer->status === 'accepted')->count(), 'color' => 'info', 'icon' => 'M5 13l4 4L19 7'],
                    ['label' => 'Rejected', 'value' => $applications->where('status', 'rejected')->count(), 'color' => 'danger', 'icon' => 'M6 18L18 6M6 6l12 12'],
                ] as $card)
                <div class="bg-white rounded-2xl p-5 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-{{ $card['color'] }}/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-{{ $card['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-extrabold text-{{ $card['color'] }} mb-1">{{ $card['value'] }}</p>
                    <p class="text-xs text-gray-500 font-medium">{{ $card['label'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Offer Insights --}}
            @php
                $totalOffers = $applications->filter(fn($app) => $app->offer !== null)->count();
                $acceptedOffers = $applications->filter(fn($app) => $app->offer && $app->offer->status === 'accepted')->count();
                $declinedOffers = $applications->filter(fn($app) => $app->offer && $app->offer->status === 'declined')->count();
                $pendingOffers = $applications->filter(fn($app) => $app->offer && $app->offer->status === 'pending')->count();
                $acceptanceRate = $totalOffers > 0 ? round(($acceptedOffers / $totalOffers) * 100, 1) : 0;
            @endphp

            @if($totalOffers > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-gradient-to-br from-success/10 to-success/5 rounded-2xl p-6 border border-success/20">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-success">Acceptance Rate</span>
                        <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <p class="text-4xl font-bold text-success mb-1">{{ $acceptanceRate }}%</p>
                    <p class="text-xs text-gray-600">{{ $acceptedOffers }} out of {{ $totalOffers }} offers accepted</p>
                </div>

                <div class="bg-gradient-to-br from-warning/10 to-warning/5 rounded-2xl p-6 border border-warning/20">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-warning">Pending Response</span>
                        <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-4xl font-bold text-warning mb-1">{{ $pendingOffers }}</p>
                    <p class="text-xs text-gray-600">Awaiting guardian decision</p>
                </div>

                <div class="bg-gradient-to-br from-danger/10 to-danger/5 rounded-2xl p-6 border border-danger/20">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-danger">Declined Offers</span>
                        <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <p class="text-4xl font-bold text-danger mb-1">{{ $declinedOffers }}</p>
                    <p class="text-xs text-gray-600">{{ $totalOffers > 0 ? round(($declinedOffers / $totalOffers) * 100, 1) : 0 }}% decline rate</p>
                </div>
            </div>
            @endif

            {{-- Applications Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Reference</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Student Name</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Guardian</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Class Applied</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Documents</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                                <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($applications as $app)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-mono font-bold text-primary">{{ $app->application_no }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-dark">{{ $app->first_name }} {{ $app->last_name }}</p>
                                    <p class="text-xs text-gray-400">DOB: {{ $app->dob?->format('M d, Y') ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700">{{ $app->guardian_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $app->guardian_phone }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $app->appliedClass->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    @if($app->documents->count() > 0)
                                    <button onclick="showDocuments({{ $app->id }})" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        {{ $app->documents->count() }} Files
                                    </button>
                                    {{-- Hidden documents data --}}
                                    <div id="docs-{{ $app->id }}" class="hidden">
                                        @foreach($app->documents as $doc)
                                        <div class="doc-item" data-name="{{ $doc->document_name }}" data-file="{{ asset('storage/' . $doc->file) }}" data-date="{{ $doc->uploaded_at?->format('M d, Y h:i A') }}"></div>
                                        @endforeach
                                    </div>
                                    @else
                                    <span class="text-xs text-gray-400 italic">No documents</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $app->submitted_at?->format('M d, Y') ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = ['submitted' => 'warning', 'under_review' => 'info', 'offered' => 'success', 'rejected' => 'danger', 'enrolled' => 'primary'];
                                        $color = $statusColors[$app->status] ?? 'gray';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-{{ $color }}/10 text-{{ $color }} uppercase">{{ str_replace('_', ' ', $app->status) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if($app->status === 'submitted')
                                        <form method="POST" action="{{ route('admissions.send-offer', $app->id) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-success/10 text-success rounded-lg hover:bg-success/20 transition-colors">
                                                Send Offer
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admissions.update', $app->id) }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold bg-danger/10 text-danger rounded-lg hover:bg-danger/20 transition-colors">
                                                Reject
                                            </button>
                                        </form>
                                        @elseif($app->status === 'offered' && $app->offer)
                                        <a href="{{ route('admissions.download-offer', $app->id) }}" class="px-3 py-1.5 text-xs font-semibold bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors inline-flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Download
                                        </a>
                                        <span class="px-3 py-1.5 text-xs font-semibold rounded-lg" style="background-color: {{ $app->offer->status === 'accepted' ? '#d4edda' : ($app->offer->status === 'declined' ? '#f8d7da' : '#fff3cd') }}; color: {{ $app->offer->status === 'accepted' ? '#155724' : ($app->offer->status === 'declined' ? '#721c24' : '#856404') }};">
                                            {{ ucfirst($app->offer->status) }}
                                        </span>
                                        {{-- Show Enroll button when offer is accepted --}}
                                        @if($app->offer->status === 'accepted')
                                        <button onclick="openEnrollModal({{ $app->id }}, '{{ addslashes($app->first_name . ' ' . $app->last_name) }}', {{ $app->applied_class_id }})" class="px-3 py-1.5 text-xs font-bold bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                            Enroll
                                        </button>
                                        @endif
                                        @elseif($app->status === 'enrolled')
                                        <span class="px-3 py-1.5 text-xs font-semibold bg-primary/10 text-primary rounded-lg">✅ Enrolled</span>
                                        @else
                                        <span class="text-xs text-gray-400 italic">Processed</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="px-6 py-12 text-center text-gray-400">No admission applications found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Enroll Student Modal --}}
<div id="enrollModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <div>
                <h3 class="text-xl font-bold text-dark">Enroll Student</h3>
                <p id="enrollStudentName" class="text-sm text-gray-500 mt-0.5"></p>
            </div>
            <button onclick="closeEnrollModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="enrollForm" method="POST" action="">
            @csrf
            <div class="p-6 space-y-4">
                <div class="bg-primary/5 border border-primary/20 rounded-xl p-4 text-sm text-primary">
                    ℹ️ Assigning a class arm will officially register this student and increase your student count.
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Class <span class="text-danger">*</span></label>
                    <select id="enrollClassId" name="class_id" required class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all" onchange="loadArmsForEnroll(this.value)">
                        <option value="">Loading classes…</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Class Arm <span class="text-gray-400">(Optional)</span></label>
                    <select id="enrollArmId" name="arm_id" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 text-sm transition-all">
                        <option value="">Select Class first</option>
                    </select>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" onclick="closeEnrollModal()" class="flex-1 py-3 px-4 border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all">Cancel</button>
                <button type="submit" class="flex-1 py-3 px-4 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Enroll Student
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Document Viewer Modal --}}
<div id="documentModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-dark">Application Documents</h3>
            <button onclick="closeDocumentModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto p-6">
            <div id="documentList" class="space-y-3">
                <!-- Documents will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function showDocuments(applicationId) {
    const docsContainer = document.getElementById('docs-' + applicationId);
    const docItems = docsContainer.querySelectorAll('.doc-item');
    const documentList = document.getElementById('documentList');
    
    // Clear previous content
    documentList.innerHTML = '';
    
    if (docItems.length === 0) {
        documentList.innerHTML = '<p class="text-gray-400 text-center py-8">No documents uploaded</p>';
    } else {
        docItems.forEach(item => {
            const docName = item.getAttribute('data-name');
            const docFile = item.getAttribute('data-file');
            const docDate = item.getAttribute('data-date');
            
            const docCard = `
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-dark">${docName}</p>
                            <p class="text-xs text-gray-400">Uploaded: ${docDate}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="${docFile}" target="_blank" class="px-3 py-1.5 text-xs font-semibold bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors">
                            View
                        </a>
                        <a href="${docFile}" download class="px-3 py-1.5 text-xs font-semibold bg-success/10 text-success rounded-lg hover:bg-success/20 transition-colors">
                            Download
                        </a>
                    </div>
                </div>
            `;
            
            documentList.innerHTML += docCard;
        });
    }
    
    document.getElementById('documentModal').classList.remove('hidden');
}

function closeDocumentModal() {
    document.getElementById('documentModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('documentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDocumentModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDocumentModal();
        closeEnrollModal();
    }
});

// ---- Enroll Modal ----
// All classes and arms pre-loaded from server (no AJAX needed)
const enrollClasses = @json($classes->map(fn($c) => ['id' => $c->id, 'name' => $c->name]));
const enrollArms = @json($arms->map(fn($a) => ['id' => $a->id, 'name' => $a->name, 'class_id' => $a->class_id]));
const baseUrl = '{{ url('/') }}';

function openEnrollModal(appId, studentName, defaultClassId) {
    document.getElementById('enrollForm').action = baseUrl + '/admissions/' + appId + '/enroll';
    document.getElementById('enrollStudentName').textContent = 'Enrolling: ' + studentName;

    // Populate class dropdown
    const classSelect = document.getElementById('enrollClassId');
    classSelect.innerHTML = '<option value="">-- Select Class --</option>';
    enrollClasses.forEach(cls => {
        const opt = document.createElement('option');
        opt.value = cls.id;
        opt.textContent = cls.name;
        if (parseInt(cls.id) === parseInt(defaultClassId)) opt.selected = true;
        classSelect.appendChild(opt);
    });

    // Load arms for the default class
    if (defaultClassId) loadArmsForEnroll(defaultClassId);

    document.getElementById('enrollModal').classList.remove('hidden');
}

function closeEnrollModal() {
    document.getElementById('enrollModal').classList.add('hidden');
}

function loadArmsForEnroll(classId) {
    const armSelect = document.getElementById('enrollArmId');
    const filtered = enrollArms.filter(a => parseInt(a.class_id) === parseInt(classId));
    armSelect.innerHTML = '<option value="">-- No Arm (Assign Later) --</option>';
    filtered.forEach(arm => {
        const opt = document.createElement('option');
        opt.value = arm.id;
        opt.textContent = arm.name;
        armSelect.appendChild(opt);
    });
    if (filtered.length === 0) {
        armSelect.innerHTML = '<option value="">-- No Arms for this Class --</option>';
    }
}

document.getElementById('enrollModal').addEventListener('click', function(e) {
    if (e.target === this) closeEnrollModal();
});
</script>
@endsection
