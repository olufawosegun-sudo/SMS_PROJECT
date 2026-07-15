@extends('layouts.app')

@section('title', 'Edit Department — ' . ($school->name ?? 'EduWest Africa'))

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8 max-w-3xl">
            {{-- Page Header --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('departments.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-dark">Edit Department</h1>
                        <p class="text-gray-500">Update the department details</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('departments.update', $department) }}" method="POST" class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                        Department Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $department->name) }}" required
                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-200 placeholder-gray-400"
                           placeholder="e.g., Science, Humanities, Arts">
                    @error('name')
                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                        Description (Optional)
                    </label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-200 placeholder-gray-400"
                              placeholder="Describe the department scope, subjects, or notes...">{{ old('description', $department->description) }}</textarea>
                    @error('description')
                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="block text-sm font-bold text-gray-800 mb-2.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="status" required
                                class="w-full px-4 py-3.5 pr-12 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-200 appearance-none bg-white cursor-pointer">
                            <option value="active" {{ old('status', $department->status) === 'active' ? 'selected' : '' }}>✅ Active</option>
                            <option value="inactive" {{ old('status', $department->status) === 'inactive' ? 'selected' : '' }}>⏸️ Inactive</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('status')
                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center gap-3">
                    <button type="submit"
                            class="px-6 py-3.5 bg-primary text-white rounded-xl hover:bg-primary-dark transition-all duration-200 font-bold shadow-lg hover:shadow-xl flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Update Department
                    </button>
                    <a href="{{ route('departments.index') }}"
                       class="px-6 py-3.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200 font-bold text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
