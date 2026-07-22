@extends('layouts.app')
@section('title', 'Exam Results')
@section('body')
<div class="flex min-h-screen bg-surface">
    @if(Auth::user()->role->name === 'Teacher')
        @include('partials.teacher_sidebar')
    @else
        @include('partials.sidebar', ['role' => strtolower(Auth::user()->role->name)])
    @endif

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Student Results</h1>
                    <p class="text-gray-500">Record, review, and approve subject scores</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-danger/10 border border-danger/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-danger font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Filter --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8 shadow-sm">
                <form method="GET" action="{{ route('results.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Filter by Class</label>
                        <select name="class_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="">All Classes</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Record Result (Teachers Only) --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit shadow-sm">
                    @if(Auth::user()->role->name === 'Teacher')
                    <h3 class="text-lg font-bold text-dark mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Record Score
                    </h3>
                    <form method="POST" action="{{ route('results.store') }}" class="space-y-4" id="recordScoreForm">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Class *</label>
                            <select name="class_id" id="class_select" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary" onchange="filterStudents()">
                                <option value="">Select Class</option>
                                @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Student *</label>
                            <select name="student_id" id="student_select" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary" disabled>
                                <option value="">Select class first</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Students will appear after selecting a class</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Subject *</label>
                            <select name="subject_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">CA Score (40)</label>
                                <input type="number" name="ca_score" required min="0" max="40" step="0.01" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="0-40">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Exam Score (60)</label>
                                <input type="number" name="exam_score" required min="0" max="60" step="0.01" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary" placeholder="0-60">
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-primary to-primary-dark text-white rounded-xl hover:shadow-lg transition-all font-semibold text-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Score
                        </button>
                    </form>
                    @else
                    {{-- Principal/Owner Mode Read-Only info --}}
                    <h3 class="text-lg font-bold text-dark mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Approvals Center
                    </h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">
                        Teachers record student CA and Exam results. As Principal or Owner, you can review results class-by-class and approve them.
                    </p>
                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-xs text-indigo-800 space-y-2">
                        <p class="font-semibold flex items-center gap-1">💡 Tips for approvals:</p>
                        <p>1. Filter results by class using the dropdown filter at the top.</p>
                        <p>2. Select multiple rows using the checkboxes and click the <b>Batch Approve</b> button to approve them all in one go.</p>
                        <p>3. Once a result is approved, it is locked so teachers can no longer modify it.</p>
                    </div>
                    @endif
                </div>

                {{-- Right: Results List --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-dark">Grades Sheet</h3>
                    </div>

                    <form id="batchApproveForm" method="POST" action="{{ route('results.batch-approve') }}">
                        @csrf
                        {{-- Batch Action Button --}}
                        @if(in_array(Auth::user()->role->name, ['Principal', 'Owner']))
                        <div class="px-6 py-3.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between gap-4">
                            <span class="text-xs font-bold text-gray-500" id="selectedCount">0 items selected</span>
                            <button type="submit" id="batchApproveBtn" disabled
                                class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-bold text-xs transition-all hover:from-green-700 hover:to-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                                ✅ Batch Approve Selected
                            </button>
                        </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        @if(in_array(Auth::user()->role->name, ['Principal', 'Owner']))
                                        <th class="px-6 py-4 text-left w-10">
                                            <input type="checkbox" id="selectAll" class="rounded text-primary focus:ring-primary/20 border-gray-200">
                                        </th>
                                        @endif
                                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Student</th>
                                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Class</th>
                                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Subject</th>
                                        <th class="text-center px-4 py-4 text-xs font-bold text-gray-500 uppercase">CA</th>
                                        <th class="text-center px-4 py-4 text-xs font-bold text-gray-500 uppercase">Exam</th>
                                        <th class="text-center px-4 py-4 text-xs font-bold text-gray-500 uppercase">Total</th>
                                        <th class="text-center px-4 py-4 text-xs font-bold text-gray-500 uppercase">Grade</th>
                                        <th class="text-center px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($results as $r)
                                    @php
                                        $isApproved = $r->approvals->where('status', 'approved')->isNotEmpty();
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        @if(in_array(Auth::user()->role->name, ['Principal', 'Owner']))
                                        <td class="px-6 py-4">
                                            @if(!$isApproved)
                                            <input type="checkbox" name="result_ids[]" value="{{ $r->id }}" class="result-checkbox rounded text-primary focus:ring-primary/20 border-gray-200">
                                            @else
                                            <input type="checkbox" disabled class="rounded bg-gray-100 border-gray-200 cursor-not-allowed text-gray-300">
                                            @endif
                                        </td>
                                        @endif
                                        <td class="px-6 py-4 text-sm font-semibold text-dark">
                                            {{ $r->student->user->first_name ?? 'N/A' }} {{ $r->student->user->last_name ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $r->schoolClass->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $r->subject->name ?? 'N/A' }}</td>
                                        <td class="text-center px-4 py-4 text-sm text-gray-500">{{ $r->ca_score }}</td>
                                        <td class="text-center px-4 py-4 text-sm text-gray-500">{{ $r->exam_score }}</td>
                                        <td class="text-center px-4 py-4 text-sm font-bold text-primary">{{ $r->total_score }}</td>
                                        <td class="text-center px-4 py-4">
                                            <span class="px-2 py-0.5 text-xs font-bold rounded bg-accent/10 text-accent-dark">{{ $r->grade }}</span>
                                        </td>
                                        <td class="text-center px-6 py-4">
                                            @if($isApproved)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-150">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                Approved
                                            </span>
                                            @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-500 border border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                Pending
                                            </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                @if(in_array(Auth::user()->role->name, ['Principal', 'Owner']) && !$isApproved)
                                                <button type="button" onclick="approveIndividual({{ $r->id }})" 
                                                    class="px-2.5 py-1.5 bg-green-50 border border-green-200 hover:bg-green-100 text-green-700 rounded-lg text-xs font-bold transition-colors">
                                                    Approve
                                                </button>
                                                @endif

                                                @if(!$isApproved)
                                                <button type="button" onclick="deleteIndividual({{ $r->id }})" 
                                                    class="text-xs text-danger font-semibold hover:underline">
                                                    Delete
                                                </button>
                                                @else
                                                <span class="text-xs text-gray-400 flex items-center gap-1 font-semibold select-none">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                    Locked
                                                </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ in_array(Auth::user()->role->name, ['Principal', 'Owner']) ? '10' : '9' }}" class="px-6 py-12 text-center text-gray-400">
                                            No score records found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Inline Actions Forms --}}
<form id="individualApproveForm" method="POST" action="" class="hidden">
    @csrf
</form>

<form id="individualDeleteForm" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
// Filter student scripts
const allStudents = @json($students);

function filterStudents() {
    const classId = document.getElementById('class_select').value;
    const studentSelect = document.getElementById('student_select');
    
    studentSelect.innerHTML = '<option value="">Select Student</option>';
    
    if (!classId) {
        studentSelect.disabled = true;
        studentSelect.innerHTML = '<option value="">Select class first</option>';
        return;
    }
    
    const filteredStudents = allStudents.filter(student => student.class_id == classId);
    
    if (filteredStudents.length === 0) {
        studentSelect.disabled = true;
        studentSelect.innerHTML = '<option value="">No students in this class</option>';
        return;
    }
    
    studentSelect.disabled = false;
    
    filteredStudents.forEach(student => {
        const option = document.createElement('option');
        option.value = student.id;
        option.textContent = `${student.user.first_name} ${student.user.last_name} - ${student.admission_number || 'N/A'}`;
        studentSelect.appendChild(option);
    });
}

// Select All & Batch action triggers
const selectAll = document.getElementById('selectAll');
const checkboxes = document.querySelectorAll('.result-checkbox');
const selectedCount = document.getElementById('selectedCount');
const batchApproveBtn = document.getElementById('batchApproveBtn');

if (selectAll) {
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => {
            if (!cb.disabled) {
                cb.checked = selectAll.checked;
            }
        });
        updateBatchButton();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBatchButton);
    });
}

function updateBatchButton() {
    const checkedCount = document.querySelectorAll('.result-checkbox:checked').length;
    if (selectedCount) {
        selectedCount.textContent = `${checkedCount} items selected`;
    }
    if (batchApproveBtn) {
        batchApproveBtn.disabled = checkedCount === 0;
    }
}

// Inline trigger helpers
function approveIndividual(resultId) {
    if (confirm('Approve this result score?')) {
        const form = document.getElementById('individualApproveForm');
        form.action = `{{ url('results') }}/${resultId}/approve`;
        form.submit();
    }
}

function deleteIndividual(resultId) {
    if (confirm('Delete this result score?')) {
        const form = document.getElementById('individualDeleteForm');
        form.action = `{{ url('results') }}/${resultId}`;
        form.submit();
    }
}
</script>
@endsection
