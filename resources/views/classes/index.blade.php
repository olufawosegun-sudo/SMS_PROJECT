@extends('layouts.app')
@section('title', 'Classes Management')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Classes Management</h1>
                    <p class="text-gray-500">Configure your school's class structure, arms, and assign class teachers</p>
                </div>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-danger/10 border border-danger/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-danger flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-danger font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Create Class --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Create Class</h3>
                    <form method="POST" action="{{ route('classes.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Class Name *</label>
                            <input type="text" name="name" required placeholder="e.g. JSS 1, SSS 2 Science" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm" value="{{ old('name') }}">
                            <p class="text-xs text-gray-400 mt-1">Enter your class name as used in your school</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Class Level *</label>
                            <input type="text" name="level" required placeholder="e.g. JSS1, SSS2, Year 10" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm" value="{{ old('level') }}" list="level_suggestions">
                            <datalist id="level_suggestions">
                                <option value="JSS1">
                                <option value="JSS2">
                                <option value="JSS3">
                                <option value="SSS1">
                                <option value="SSS2">
                                <option value="SSS3">
                                <option value="JHS1">
                                <option value="JHS2">
                                <option value="JHS3">
                                <option value="SHS1">
                                <option value="SHS2">
                                <option value="SHS3">
                            </datalist>
                            <p class="text-xs text-gray-400 mt-1">Type any level — suggestions shown but you can enter your own</p>
                        </div>
                        @if(isset($branches) && $branches->count() > 0)
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Campus / Branch</label>
                            <select name="school_branch_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                                <option value="">All Branches / Main Campus</option>
                                @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                            <textarea name="description" placeholder="Brief details about class level" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">{{ old('description') }}</textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Class</button>
                    </form>
                </div>

                {{-- Right: Classes Grid --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($classes as $c)
                        <div class="bg-white rounded-2xl p-5 border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                            <div>
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-[9px] font-bold text-accent-dark bg-accent-light/50 px-2 py-0.5 rounded-md uppercase tracking-wider">{{ $c->level }}</span>
                                            @if($c->schoolBranch)
                                            <span class="text-[9px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded-md uppercase tracking-wider">{{ $c->schoolBranch->name }}</span>
                                            @endif
                                        </div>
                                        <h4 class="text-base font-bold text-dark mt-1.5">{{ $c->name }}</h4>
                                    </div>
                                    <div class="flex gap-1">
                                        <button onclick="openEditClassModal({{ $c->id }}, '{{ addslashes($c->name) }}', '{{ addslashes($c->level) }}', '{{ addslashes($c->description ?? '') }}', '{{ $c->school_branch_id ?? '' }}')" class="text-gray-400 hover:text-primary transition-colors p-1" title="Edit Class">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('classes.destroy', $c->id) }}" onsubmit="return confirm('Delete this class and all its arms?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-danger transition-colors p-1" title="Delete Class">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 mb-4">{{ $c->description ?? 'No description.' }}</p>
                            </div>

                            <div class="border-t border-gray-100 pt-3 space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 font-medium">Arms (Total)</span>
                                    <span class="font-bold text-gray-700 bg-gray-50 px-2 py-0.5 rounded">{{ $c->arms_count }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 font-medium">Students (Total)</span>
                                    <span class="font-bold text-primary bg-primary/10 px-2 py-0.5 rounded">{{ $c->students_count }}</span>
                                </div>

                                {{-- Add Arm Button --}}
                                <button onclick="openArmModal({{ $c->id }}, '{{ addslashes($c->name) }}')" class="w-full mt-3 py-2 bg-success/10 text-success hover:bg-success/20 rounded-lg transition-colors font-semibold text-xs flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add Arm
                                </button>

                                @if($c->arms->count() > 0)
                                <div class="mt-3 bg-gray-50/50 p-2.5 rounded-lg space-y-1.5 text-xs">
                                    <p class="font-bold text-[10px] uppercase tracking-wider text-gray-400 mb-1.5">Class Arms</p>
                                    @foreach($c->arms as $arm)
                                    <div class="flex items-center justify-between text-[11px] py-1 hover:bg-white px-2 rounded transition-colors group">
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-600 font-semibold">{{ $arm->name }}</span>
                                            <span class="text-gray-400">({{ $arm->students_count }}/{{ $arm->capacity }})</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-gray-400 italic">{{ $arm->teacher->user->first_name ?? 'No Teacher' }}</span>
                                            <form method="POST" action="{{ route('class-arms.destroy', $arm->id) }}" onsubmit="return confirm('Delete this arm?')" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-danger hover:underline">×</button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 bg-white rounded-2xl p-8 border border-gray-100 text-center text-gray-400">
                            No classes created yet. Use the form on the left to add your school's class structure.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Add Arm Modal --}}
<div id="armModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-dark" id="modalTitle">Add Class Arm</h3>
            <button onclick="closeArmModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form method="POST" action="{{ route('class-arms.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="class_id" id="modal_class_id">
            
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Arm Name *</label>
                <input type="text" name="name" required placeholder="e.g. A, B, C or Science, Arts" maxlength="50" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                <p class="text-xs text-gray-400 mt-1">Usually: A, B, C, D or Science, Arts, Commercial</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Capacity (Max Students) *</label>
                <input type="number" name="capacity" required min="1" max="200" value="40" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                <p class="text-xs text-gray-400 mt-1">Recommended: 35-45 students per arm</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Class Teacher (Optional)</label>
                <select name="teacher_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    <option value="">No Teacher Assigned</option>
                    @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->user->first_name }} {{ $teacher->user->last_name }}</option>
                    @endforeach
                </select>
            </div>

            @if(isset($branches) && $branches->count() > 0)
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Campus / Branch</label>
                <select name="school_branch_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    <option value="">Inherit from Class / Main Campus</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeArmModal()" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-sm">Cancel</button>
                <button type="submit" class="flex-1 py-3 bg-success text-white rounded-xl hover:bg-success/90 transition-colors font-semibold text-sm">Add Arm</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Class Modal --}}
<div id="editClassModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-dark">Edit Class</h3>
            <button onclick="closeEditClassModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="POST" id="editClassForm" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Class Name *</label>
                <input type="text" name="name" id="edit_class_name" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Class Level *</label>
                <input type="text" name="level" id="edit_class_level" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm" list="level_suggestions">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                <textarea name="description" id="edit_class_description" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm"></textarea>
            </div>
            @if(isset($branches) && $branches->count() > 0)
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Campus / Branch</label>
                <select name="school_branch_id" id="edit_class_branch" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm">
                    <option value="">All Branches / Main Campus</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditClassModal()" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-semibold text-sm">Cancel</button>
                <button type="submit" class="flex-1 py-3 bg-primary text-white rounded-xl hover:bg-primary/90 transition-colors font-semibold text-sm">Update Class</button>
            </div>
        </form>
    </div>
</div>

<script>
function openArmModal(classId, className) {
    document.getElementById('modal_class_id').value = classId;
    document.getElementById('modalTitle').textContent = `Add Arm to ${className}`;
    document.getElementById('armModal').classList.remove('hidden');
}

function closeArmModal() {
    document.getElementById('armModal').classList.add('hidden');
}

function openEditClassModal(id, name, level, description, branchId) {
    document.getElementById('editClassForm').action = `/classes/${id}`;
    document.getElementById('edit_class_name').value = name;
    document.getElementById('edit_class_level').value = level;
    document.getElementById('edit_class_description').value = description;
    const branchSelect = document.getElementById('edit_class_branch');
    if (branchSelect) {
        branchSelect.value = branchId || '';
    }
    document.getElementById('editClassModal').classList.remove('hidden');
}

function closeEditClassModal() {
    document.getElementById('editClassModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('armModal').addEventListener('click', function(e) {
    if (e.target === this) closeArmModal();
});
document.getElementById('editClassModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditClassModal();
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeArmModal();
        closeEditClassModal();
    }
});
</script>
@endsection
