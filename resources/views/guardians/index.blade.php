@extends('layouts.app')

@section('title', 'Parents/Guardians Management')

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-64">
        @include('partials.topbar')

        <div class="p-8">
            {{-- Page Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Parents/Guardians Management</h1>
                    <p class="text-gray-500">Manage all guardian accounts and their linked students</p>
                </div>
                <a href="{{ route('guardians.create') }}"
                   class="px-6 py-3 bg-warning text-white rounded-xl hover:bg-warning-dark transition-colors font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Guardian
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
                    ['label' => 'Total Guardians', 'value' => $guardians->total(), 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'warning'],
                    ['label' => 'Active', 'value' => $stats['active'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'success'],
                    ['label' => 'Inactive', 'value' => $stats['inactive'], 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'danger'],
                    ['label' => 'Linked Students', 'value' => $stats['total_students'], 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'primary'],
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

            {{-- Guardians Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h3 class="text-lg font-bold text-dark">All Guardians ({{ $guardians->total() }})</h3>
                        
                        {{-- Search & Filter --}}
                        <div class="flex gap-3">
                            <form action="{{ route('guardians.index') }}" method="GET" class="flex gap-3">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search guardians..."
                                       class="px-4 py-2 border border-gray-200 rounded-lg focus:border-warning focus:ring-2 focus:ring-warning/20 transition-all">
                                <button type="submit"
                                        class="px-4 py-2 bg-warning text-white rounded-lg hover:bg-warning-dark transition-colors font-semibold">
                                    Search
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Guardian</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Relationship</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Occupation</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Linked Students</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($guardians as $guardian)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-warning/10 flex items-center justify-center mr-3">
                                            @if($guardian->user->profile_photo)
                                            <img src="{{ asset('storage/' . $guardian->user->profile_photo) }}" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                            <span class="text-sm font-bold text-warning">{{ substr($guardian->user->first_name, 0, 1) }}{{ substr($guardian->user->last_name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-dark">{{ $guardian->user->first_name }} {{ $guardian->user->last_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $guardian->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $guardian->relationship ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $guardian->occupation ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($guardian->students as $student)
                                        <span class="px-2 py-1 text-xs font-semibold bg-primary/10 text-primary rounded">
                                            {{ $student->user->first_name }}
                                        </span>
                                        @empty
                                        <span class="text-xs text-gray-400">No students linked</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'active' => 'success',
                                            'inactive' => 'danger'
                                        ];
                                        $color = $statusColors[$guardian->status] ?? 'gray';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-bold bg-{{ $color }}/10 text-{{ $color }} rounded-full capitalize">
                                        {{ $guardian->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('guardians.show', $guardian) }}" 
                                           class="p-2 text-info hover:bg-info/10 rounded-lg transition-colors" title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('guardians.edit', $guardian) }}" 
                                           class="p-2 text-warning hover:bg-warning/10 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('guardians.destroy', $guardian) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this guardian?');">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <h3 class="text-lg font-bold text-gray-600 mb-2">No Guardians Found</h3>
                                        <p class="text-gray-500 mb-4">Get started by creating your first guardian account</p>
                                        <a href="{{ route('guardians.create') }}"
                                           class="px-6 py-3 bg-warning text-white rounded-xl hover:bg-warning-dark transition-colors font-semibold">
                                            Add New Guardian
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($guardians->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $guardians->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
