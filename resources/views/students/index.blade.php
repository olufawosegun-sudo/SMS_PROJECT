@extends('layouts.app')

@section('title', 'Students Management')

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            {{-- Page Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Students Management</h1>
                    <p class="text-gray-500">Manage all student accounts and profiles</p>
                </div>
                <a href="{{ route('students.create') }}"
                   class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Student
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
                    ['label' => 'Total Students', 'value' => $students->total(), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'primary'],
                    ['label' => 'Active', 'value' => $stats['active'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'success'],
                    ['label' => 'Inactive', 'value' => $stats['inactive'], 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'warning'],
                    ['label' => 'Male Students', 'value' => $stats['male'], 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'color' => 'info'],
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

            {{-- Students Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h3 class="text-lg font-bold text-dark">All Students ({{ $students->total() }})</h3>
                        
                        {{-- Search & Filter --}}
                        <div class="flex gap-3">
                            <form action="{{ route('students.index') }}" method="GET" class="flex gap-3">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search students..."
                                       class="px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                <select name="status"
                                        class="px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Graduated</option>
                                </select>
                                <button type="submit"
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-semibold">
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
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Admission No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Campus/Branch</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Gender</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $student)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mr-3">
                                            @if($student->user->profile_photo)
                                            <img src="{{ asset('storage/' . $student->user->profile_photo) }}" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                            <span class="text-sm font-bold text-primary">{{ substr($student->user->first_name, 0, 1) }}{{ substr($student->user->last_name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-dark">{{ $student->user->first_name }} {{ $student->user->last_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $student->user->email ?? 'No email' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">{{ $student->admission_no }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $student->schoolClass->name ?? 'N/A' }} {{ $student->arm ? '('.$student->arm->name.')' : '' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-gray-100 text-gray-700">
                                        {{ $student->schoolBranch->name ?? ($student->user->schoolBranch->name ?? 'Main Campus') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700 capitalize">{{ $student->user->gender }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'active' => 'success',
                                            'inactive' => 'warning',
                                            'graduated' => 'info',
                                            'transferred' => 'danger'
                                        ];
                                        $color = $statusColors[$student->status] ?? 'gray';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-bold bg-{{ $color }}/10 text-{{ $color }} rounded-full capitalize">
                                        {{ $student->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('students.show', $student) }}" 
                                           class="p-2 text-info hover:bg-info/10 rounded-lg transition-colors" title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('students.edit', $student) }}" 
                                           class="p-2 text-warning hover:bg-warning/10 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this student?');">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <h3 class="text-lg font-bold text-gray-600 mb-2">No Students Found</h3>
                                        <p class="text-gray-500 mb-4">Get started by creating your first student account</p>
                                        <a href="{{ route('students.create') }}"
                                           class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold">
                                            Add New Student
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($students->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $students->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
