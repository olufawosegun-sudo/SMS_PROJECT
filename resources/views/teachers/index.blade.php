@extends('layouts.app')

@section('title', 'Teachers Management')

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            {{-- Page Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Teachers Management</h1>
                    <p class="text-gray-500">Manage all teacher accounts and profiles</p>
                </div>
                <a href="{{ route('teachers.create') }}"
                   class="px-6 py-3 bg-info text-white rounded-xl hover:bg-info-dark transition-colors font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Teacher
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

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                @foreach([
                    ['label' => 'Total Teachers', 'value' => $teachers->total(), 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'info'],
                    ['label' => 'Active', 'value' => $stats['active'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'success'],
                    ['label' => 'Inactive', 'value' => $stats['inactive'], 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'warning'],
                    ['label' => 'Departments', 'value' => $stats['departments'], 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'color' => 'primary'],
                ] as $card)
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-{{ $card['color'] }}/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-{{ $card['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-extrabold text-dark mb-1">{{ $card['value'] }}</p>
                    <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Teachers Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h3 class="text-lg font-bold text-dark">All Teachers ({{ $teachers->total() }})</h3>
                        
                        {{-- Search & Filter --}}
                        <div class="flex gap-3">
                            <form action="{{ route('teachers.index') }}" method="GET" class="flex gap-3">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search teachers..."
                                       class="px-4 py-2 border border-gray-200 rounded-lg focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                <select name="status"
                                        class="px-4 py-2 border border-gray-200 rounded-lg focus:border-info focus:ring-2 focus:ring-info/20 transition-all">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                <button type="submit"
                                        class="px-4 py-2 bg-info text-white rounded-lg hover:bg-info-dark transition-colors font-semibold">
                                    Filter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Teacher</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Staff No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Subjects/Classes</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($teachers as $teacher)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-info/10 flex items-center justify-center mr-3">
                                            @if($teacher->user->profile_photo)
                                            <img src="{{ asset('storage/' . $teacher->user->profile_photo) }}" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                            <span class="text-sm font-bold text-info">{{ substr($teacher->user->first_name, 0, 1) }}{{ substr($teacher->user->last_name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-dark">{{ $teacher->user->first_name }} {{ $teacher->user->last_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $teacher->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">{{ $teacher->staff_no }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $teacher->department->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($teacher->teacherSubjects->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($teacher->teacherSubjects->take(3) as $assignment)
                                        <span class="px-2 py-1 text-xs font-semibold bg-primary/10 text-primary rounded" title="{{ $assignment->schoolClass->name }} - {{ $assignment->subject->name }}">
                                            {{ $assignment->subject->name }}
                                        </span>
                                        @endforeach
                                        @if($teacher->teacherSubjects->count() > 3)
                                        <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-600 rounded">
                                            +{{ $teacher->teacherSubjects->count() - 3 }} more
                                        </span>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-xs text-gray-400">No assignments</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'active' => 'success',
                                            'inactive' => 'warning',
                                            'suspended' => 'danger',
                                            'resigned' => 'gray'
                                        ];
                                        $color = $statusColors[$teacher->status] ?? 'gray';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-bold bg-{{ $color }}/10 text-{{ $color }} rounded-full capitalize">
                                        {{ $teacher->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('teachers.show', $teacher->id) }}" 
                                           class="p-2 text-info hover:bg-info/10 rounded-lg transition-colors" title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('teachers.edit', $teacher->id) }}" 
                                           class="p-2 text-warning hover:bg-warning/10 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-danger hover:bg-danger/10 rounded-lg transition-colors" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <h3 class="text-lg font-bold text-gray-600 mb-2">No Teachers Found</h3>
                                        <p class="text-gray-500 mb-4">Get started by creating your first teacher account</p>
                                        <a href="{{ route('teachers.create') }}"
                                           class="px-6 py-3 bg-info text-white rounded-xl hover:bg-info-dark transition-colors font-semibold">
                                            Add New Teacher
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($teachers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $teachers->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
