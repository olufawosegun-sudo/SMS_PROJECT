@extends('layouts.app')

@section('title', 'Edit Principal')

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            {{-- Page Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('principals.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-dark">Edit Principal</h1>
                        <p class="text-gray-500">Update principal account information</p>
                    </div>
                </div>
            </div>

            {{-- Edit Form --}}
            <form action="{{ route('principals.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid lg:grid-cols-3 gap-8">
                    {{-- Main Form --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- User Account Section --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-dark">Personal Information</h3>
                                    <p class="text-sm text-gray-500">Principal's basic details</p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $staff->user->first_name) }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('first_name')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $staff->user->last_name) }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('last_name')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                                    <input type="email" name="email" value="{{ old('email', $staff->user->email) }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('email')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                    <input type="text" name="phone" value="{{ old('phone', $staff->user->phone) }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('phone')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gender *</label>
                                    <select name="gender" required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $staff->user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $staff->user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('gender')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth *</label>
                                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $staff->user->date_of_birth) }}" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('date_of_birth')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Role Type *</label>
                                    <select name="role_type" required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                        <option value="">Select Role</option>
                                        <option value="Principal" {{ old('role_type', $staff->staff_type) == 'Principal' ? 'selected' : '' }}>Principal</option>
                                        <option value="Vice Principal" {{ old('role_type', $staff->staff_type) == 'Vice Principal' ? 'selected' : '' }}>Vice Principal</option>
                                        <option value="Assistant Principal" {{ old('role_type', $staff->staff_type) == 'Assistant Principal' ? 'selected' : '' }}>Assistant Principal</option>
                                    </select>
                                    @error('role_type')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Professional Information --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-dark">Professional Details</h3>
                                    <p class="text-sm text-gray-500">Educational & employment information</p>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Qualification</label>
                                    <input type="text" name="qualification" value="{{ old('qualification', $staff->qualification) }}"
                                           placeholder="e.g., PhD in Education, M.Ed"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('qualification')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Specialization</label>
                                    <input type="text" name="specialization" value="{{ old('specialization', $staff->specialization) }}"
                                           placeholder="e.g., Educational Leadership"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('specialization')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Employment Date</label>
                                    <input type="date" name="employment_date" value="{{ old('employment_date', $staff->employment_date) }}"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('employment_date')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Years of Experience</label>
                                    <input type="number" name="years_of_experience" value="{{ old('years_of_experience', $staff->years_of_experience) }}" min="0"
                                           placeholder="Total years"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('years_of_experience')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Office Location</label>
                                    <input type="text" name="office_location" value="{{ old('office_location', $staff->office_location) }}"
                                           placeholder="e.g., Admin Block, Room 101"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('office_location')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Salary (Optional)</label>
                                    <input type="number" name="salary" value="{{ old('salary', $staff->salary) }}" min="0" step="0.01"
                                           placeholder="Monthly salary"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('salary')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Previous School</label>
                                    <input type="text" name="previous_school" value="{{ old('previous_school', $staff->previous_school) }}"
                                           placeholder="Name of previous school (if any)"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('previous_school')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Emergency Contact</label>
                                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $staff->emergency_contact) }}"
                                           placeholder="Emergency phone number"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('emergency_contact')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Emergency Contact Relationship</label>
                                    <input type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $staff->emergency_contact_relationship) }}"
                                           placeholder="e.g., Spouse, Sibling"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                    @error('emergency_contact_relationship')
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
                                    @if($staff->user->photo)
                                        <img src="{{ Storage::url($staff->user->photo) }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    @endif
                                </div>
                                <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" class="hidden">
                                <button type="button" onclick="document.getElementById('profilePhotoInput').click()"
                                        class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors text-sm font-semibold">
                                    Change Photo
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
                                        class="w-full px-6 py-3 bg-accent text-dark rounded-xl hover:bg-accent-dark transition-colors font-semibold">
                                    Update Principal
                                </button>
                                <a href="{{ route('principals.index') }}"
                                   class="block w-full px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-center">
                                    Cancel
                                </a>
                            </div>
                        </div>

                        {{-- Account Status --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100">
                            <h3 class="text-lg font-bold text-dark mb-4">Account Status</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Status</span>
                                    <span class="text-sm font-semibold text-{{ $staff->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($staff->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Staff No</span>
                                    <span class="text-sm font-semibold text-dark">{{ $staff->staff_no }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Joined</span>
                                    <span class="text-sm font-semibold text-dark">{{ $staff->created_at->format('M d, Y') }}</span>
                                </div>
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
@endsection
