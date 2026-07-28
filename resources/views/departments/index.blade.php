@extends('layouts.app')

@section('title', 'Departments Management — ' . ($school->name ?? 'EduWest Africa'))

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Departments Management</h1>
                    <p class="text-gray-500">Organize and manage school departments</p>
                </div>
                <a href="{{ route('departments.create') }}"
                   class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold flex items-center justify-center gap-2 self-start sm:self-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Department
                </a>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl flex items-center gap-3">
                <svg class="w-6 h-6 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Departments Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($departments as $dept)
                <div class="bg-white rounded-2xl border border-gray-100 p-6 flex flex-col justify-between hover:shadow-lg transition-all duration-300 group">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $dept->status === 'active' ? 'bg-success/10 text-success' : 'bg-gray-100 text-gray-500' }}">
                                    {{ ucfirst($dept->status) }}
                                </span>
                                @if($dept->schoolBranch)
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-primary/10 text-primary">
                                    {{ $dept->schoolBranch->name }}
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('departments.edit', $dept) }}" class="p-1.5 text-warning hover:bg-warning/10 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('departments.destroy', $dept) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this department? All assigned teachers will be unassigned.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-danger hover:bg-danger/10 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-dark mb-2 group-hover:text-primary transition-colors">{{ $dept->name }}</h3>
                        <p class="text-gray-500 text-sm mb-6 line-clamp-3">{{ $dept->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex items-center justify-between text-sm">
                        <span class="text-gray-400">Assigned Teachers</span>
                        <span class="font-bold text-primary bg-primary/10 px-2.5 py-1 rounded-lg">{{ $dept->teachers_count }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white rounded-2xl border border-gray-100 py-16 text-center">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-dark mb-2">No Departments Found</h3>
                    <p class="text-gray-500 mb-6 max-w-sm mx-auto">Get started by creating your first school department to group teachers and classes.</p>
                    <a href="{{ route('departments.create') }}" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Department
                    </a>
                </div>
                @endforelse
            </div>

            @if($departments->hasPages())
            <div class="mt-8">
                {{ $departments->links() }}
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
