@extends('layouts.app')

@section('title', 'Add New Teacher')

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-64">
        @include('partials.topbar')

        <div class="p-8">
            {{-- Page Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('teachers.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-dark">Add New Teacher</h1>
                        <p class="text-gray-500">Create a new teacher account and profile</p>
                    </div>
                </div>
            </div>

            {{-- Registration Form --}}
            <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid lg:grid-cols-3 gap-8">
                    {{-- Main Form --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- User Account Section --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-info/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-dark">Personal Information</h3>
                                    <p class="text-sm text-gray-500">Teacher's basic details</p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                    @error('first_name')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                    @error('last_name')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                    @error('email')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                    @error('phone')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gender *</label>
                                    <select name="gender" required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth *</label>
                                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                    @error('date_of_birth')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Employment Section --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-dark">Employment Information</h3>
                                    <p class="text-sm text-gray-500">Professional and work details</p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                                    <select name="department_id"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Qualification</label>
                                    <input type="text" name="qualification" value="{{ old('qualification') }}" placeholder="e.g., B.Ed, M.Sc"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                    @error('qualification')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Employment Date</label>
                                    <input type="date" name="employment_date" value="{{ old('employment_date', date('Y-m-d')) }}"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                    @error('employment_date')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Monthly Salary</label>
                                    <input type="number" name="salary" value="{{ old('salary') }}" step="0.01" placeholder="0.00"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                    @error('salary')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                    <select name="status"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                        <option value="resigned" {{ old('status') == 'resigned' ? 'selected' : '' }}>Resigned</option>
                                    </select>
                                    @error('status')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Subject Assignment Section --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-dark">Subject & Class Assignments</h3>
                                        <p class="text-sm text-gray-500">Assign subjects and classes to this teacher</p>
                                    </div>
                                </div>
                                <button type="button" onclick="addSubjectAssignment()"
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Assignment
                                </button>
                            </div>

                            <div id="subjectAssignments" class="space-y-4">
                                {{-- Subject assignment rows will be added here dynamically --}}
                            </div>

                            @if($currentSession && $currentTerm)
                            <div class="mt-4 p-4 bg-info/10 rounded-lg">
                                <p class="text-xs text-info">
                                    <strong>Current Session:</strong> {{ $currentSession->name }}<br>
                                    <strong>Current Term:</strong> {{ $currentTerm->name }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Profile Photo --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <h3 class="text-lg font-bold text-dark mb-4">Profile Photo</h3>
                            <div class="text-center">
                                <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden" id="photoPreview">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" class="hidden">
                                <button type="button" onclick="document.getElementById('profilePhotoInput').click()"
                                        class="px-4 py-2 bg-info text-white rounded-lg hover:bg-info-dark transition-colors text-sm font-semibold">
                                    Choose Photo
                                </button>
                                <p class="text-xs text-gray-500 mt-2">JPG, PNG (Max: 2MB)</p>
                                @error('profile_photo')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <h3 class="text-lg font-bold text-dark mb-4">Actions</h3>
                            <div class="space-y-3">
                                <button type="submit"
                                        class="w-full px-6 py-3 bg-info text-white rounded-xl hover:bg-info-dark transition-colors font-semibold">
                                    Create Teacher Account
                                </button>
                                <a href="{{ route('teachers.index') }}"
                                   class="block w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-center">
                                    Cancel
                                </a>
                            </div>
                            <div class="mt-4 p-4 bg-info/10 rounded-lg">
                                <p class="text-xs text-info">
                                    <strong>Default Password:</strong> password123<br>
                                    Teacher can change password after first login.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
// Photo Preview
document.getElementById('profilePhotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').innerHTML = 
                '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
        };
        reader.readAsDataURL(file);
    }
});

// Subject Assignment Management
let assignmentIndex = 0;
const classes = @json($classes);
const subjects = @json($subjects);

function addSubjectAssignment() {
    const container = document.getElementById('subjectAssignments');
    const assignmentRow = document.createElement('div');
    assignmentRow.className = 'p-4 bg-gray-50 rounded-xl border border-gray-200';
    assignmentRow.id = 'assignment-' + assignmentIndex;
    
    assignmentRow.innerHTML = `
        <div class="flex items-start gap-4">
            <div class="flex-1 grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Class *</label>
                    <select name="subject_assignments[${assignmentIndex}][class_id]" required
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm">
                        <option value="">Select Class</option>
                        ${classes.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Subject *</label>
                    <select name="subject_assignments[${assignmentIndex}][subject_id]" required
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm">
                        <option value="">Select Subject</option>
                        ${subjects.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
                    </select>
                </div>
            </div>
            <button type="button" onclick="removeSubjectAssignment(${assignmentIndex})"
                    class="mt-7 p-2 text-danger hover:bg-danger/10 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    `;
    
    container.appendChild(assignmentRow);
    assignmentIndex++;
}

function removeSubjectAssignment(index) {
    const element = document.getElementById('assignment-' + index);
    if (element) {
        element.remove();
    }
}

// Add one assignment by default
window.addEventListener('DOMContentLoaded', function() {
    addSubjectAssignment();
});
</script>
@endsection
