@extends('layouts.app')

@section('title', 'Add New Student')

@push('scripts')
<script src="{{ asset('js/dependent-dropdowns.js') }}"></script>
@endpush

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            {{-- Page Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('students.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-dark">Add New Student</h1>
                        <p class="text-gray-500">Create a new student account and profile</p>
                    </div>
                </div>
            </div>

            {{-- Registration Form --}}
            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid lg:grid-cols-3 gap-8">
                    {{-- Main Form --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- User Account Section --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-dark">Personal Information</h3>
                                    <p class="text-sm text-gray-500">Student's basic details</p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    @error('first_name')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    @error('last_name')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email (Optional)</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    @error('email')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    @error('phone')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gender *</label>
                                    <select name="gender" required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
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
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    @error('date_of_birth')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Academic Section --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-dark">Academic Information</h3>
                                    <p class="text-sm text-gray-500">Class and admission details</p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Class *</label>
                                    <select name="class_id" id="class_id" required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Class Arm</label>
                                    <select name="arm_id" id="arm_id" data-selected="{{ old('arm_id') }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                        <option value="">Select Arm</option>
                                        @foreach($arms as $arm)
                                        <option value="{{ $arm->id }}" data-class-id="{{ $arm->class_id }}" {{ old('arm_id') == $arm->id ? 'selected' : '' }}>
                                            {{ $arm->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('arm_id')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Admission Date</label>
                                    <input type="date" name="admission_date" value="{{ old('admission_date', date('Y-m-d')) }}"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    @error('admission_date')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                    <select name="status"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                                        <option value="transferred" {{ old('status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                                    </select>
                                    @error('status')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
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
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-semibold">
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
                                        class="w-full px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold">
                                    Create Student Account
                                </button>
                                <a href="{{ route('students.index') }}"
                                   class="block w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-center">
                                    Cancel
                                </a>
                            </div>
                            <div class="mt-4 p-4 bg-info/10 rounded-lg">
                                <p class="text-xs text-info">
                                    <strong>Default Password:</strong> password123<br>
                                    Student can change password after first login.
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
</script>

@push('scripts')
<script>
// Initialize dependent dropdowns for Class -> Arm
initializeClassArmDropdown('class_id', 'arm_id');
</script>
@endpush
@endsection
