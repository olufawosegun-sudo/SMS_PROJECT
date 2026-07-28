@extends('layouts.app')

@section('title', 'Principals Management')

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])

    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')

        <div class="p-4 md:p-6 lg:p-8">
            {{-- Page Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Principals Management</h1>
                    <p class="text-gray-500">Manage principal and vice principal accounts</p>
                </div>
                <a href="{{ route('principals.create') }}"
                   class="px-6 py-3 bg-accent text-dark rounded-xl hover:bg-accent-dark transition-colors font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Principal
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @foreach([
                    ['label' => 'Total Principals', 'value' => $principals->total(), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'accent'],
                    ['label' => 'Principal', 'value' => $stats['principal'], 'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'color' => 'primary'],
                    ['label' => 'Vice Principal', 'value' => $stats['vice_principal'], 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'color' => 'info'],
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

            {{-- Principals Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <h3 class="text-lg font-bold text-dark">All Principals ({{ $principals->total() }})</h3>
                        
                        {{-- Search --}}
                        <div class="flex gap-3">
                            <form action="{{ route('principals.index') }}" method="GET" class="flex gap-3">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search principals..."
                                       class="px-4 py-2 border border-gray-200 rounded-lg focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                                <button type="submit"
                                        class="px-4 py-2 bg-accent text-dark rounded-lg hover:bg-accent-dark transition-colors font-semibold">
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
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Principal</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Campus/Branch</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Gender</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($principals as $principal)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center mr-3">
                                            @if($principal->user->profile_photo)
                                            <img src="{{ asset('storage/' . $principal->user->profile_photo) }}" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                            <span class="text-sm font-bold text-accent">{{ substr($principal->user->first_name, 0, 1) }}{{ substr($principal->user->last_name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-dark">{{ $principal->user->first_name }} {{ $principal->user->last_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $principal->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColor = str_contains($principal->staff_type, 'Vice') ? 'info' : 'primary';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-bold bg-{{ $roleColor }}/10 text-{{ $roleColor }} rounded-full">
                                        {{ $principal->staff_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-gray-100 text-gray-700">
                                        {{ $principal->schoolBranch->name ?? ($principal->user->schoolBranch->name ?? 'Main Campus') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $principal->user->phone ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700 capitalize">{{ $principal->user->gender ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('principals.show', $principal->id) }}" 
                                           class="p-2 text-info hover:bg-info/10 rounded-lg transition-colors" title="View">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('principals.edit', $principal->id) }}" 
                                           class="p-2 text-warning hover:bg-warning/10 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('principals.destroy', $principal->id) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Are you sure you want to delete this principal?');">
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
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <h3 class="text-lg font-bold text-gray-600 mb-2">No Principals Found</h3>
                                        <p class="text-gray-500 mb-4">Get started by creating your first principal account</p>
                                        <a href="{{ route('principals.create') }}"
                                           class="px-6 py-3 bg-accent text-dark rounded-xl hover:bg-accent-dark transition-colors font-semibold">
                                            Add New Principal
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($principals->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $principals->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
