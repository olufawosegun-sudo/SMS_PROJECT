@extends('layouts.app')
@section('title', 'Register WAEC Candidate')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'principal'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
                    <a href="{{ route('principal.waec.candidates') }}" class="hover:text-primary">WAEC Candidates</a>
                    <span>/</span>
                    <span class="text-dark font-semibold">Register Candidate</span>
                </div>
                <h1 class="text-3xl font-bold text-dark mb-2">Register WAEC Candidate</h1>
                <p class="text-gray-500">Register a student for WAEC examination</p>
            </div>

            @if(session('error'))
            <div class="mb-6 p-4 bg-danger/10 border border-danger/20 rounded-xl">
                <p class="text-danger font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            <div class="max-w-3xl">
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <form method="POST" action="{{ route('principal.waec.candidates.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Select Student <span class="text-danger">*</span></label>
                            <select name="student_id" 
                                    required 
                                    id="student-select"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('student_id') border-danger @enderror">
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                <option value="{{ $student->id }}" 
                                        data-class="{{ $student->schoolClass->id ?? '' }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->first_name }} {{ $student->user->last_name }} 
                                    ({{ $student->admission_number }}) - 
                                    {{ $student->schoolClass->name ?? 'No Class' }}
                                </option>
                                @endforeach
                            </select>
                            @error('student_id')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Select the student to register for WAEC examination</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Academic Session <span class="text-danger">*</span></label>
                            <select name="session_id" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('session_id') border-danger @enderror">
                                <option value="">-- Select Session --</option>
                                @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ old('session_id') == $session->id ? 'selected' : '' }}>
                                    {{ $session->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('session_id')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Class <span class="text-danger">*</span></label>
                            <select name="class_id" 
                                    required 
                                    id="class-select"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('class_id') border-danger @enderror">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('class_id')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Class Arm</label>
                            <select name="arm_id" 
                                    id="arm-select"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('arm_id') border-danger @enderror">
                                <option value="">-- Select Arm (Optional) --</option>
                                @foreach($classes as $class)
                                    @foreach($class->arms as $arm)
                                    <option value="{{ $arm->id }}" 
                                            data-class="{{ $class->id }}"
                                            class="arm-option hidden"
                                            {{ old('arm_id') == $arm->id ? 'selected' : '' }}>
                                        {{ $arm->name }}
                                    </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('arm_id')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Notes</label>
                            <textarea name="notes" 
                                      rows="4" 
                                      placeholder="Add any additional notes about this candidate registration..."
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary @error('notes') border-danger @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold transition-colors duration-200">
                                Register Candidate
                            </button>
                            <a href="{{ route('principal.waec.candidates') }}" 
                               class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-dark rounded-xl font-semibold transition-colors duration-200">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
// Auto-populate class when student is selected
document.getElementById('student-select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const classId = selectedOption.getAttribute('data-class');
    if (classId) {
        document.getElementById('class-select').value = classId;
        filterArms();
    }
});

// Filter arms based on selected class
document.getElementById('class-select').addEventListener('change', filterArms);

function filterArms() {
    const classId = document.getElementById('class-select').value;
    const armSelect = document.getElementById('arm-select');
    const armOptions = armSelect.querySelectorAll('.arm-option');
    
    // Reset and show placeholder
    armSelect.value = '';
    
    // Show/hide arms based on selected class
    armOptions.forEach(option => {
        if (option.getAttribute('data-class') === classId) {
            option.classList.remove('hidden');
        } else {
            option.classList.add('hidden');
        }
    });
}

// Initialize on page load
filterArms();
</script>
@endpush
@endsection
