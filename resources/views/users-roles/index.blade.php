@extends('layouts.app')
@section('title', 'Users & Roles')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">Users & Role Access Control</h1>
                <p class="text-gray-500">Monitor school administration accounts, role scopes, and user rosters</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- Left: Role List Roster --}}
                <div class="space-y-4 h-fit">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400">Available Roles</h3>
                    @foreach($roles as $role)
                    @php
                        $count = isset($usersByRole[$role->name]) ? $usersByRole[$role->name]->count() : 0;
                    @endphp
                    <div class="bg-white p-4 rounded-xl border border-gray-150 flex items-center justify-between shadow-sm">
                        <div>
                            <p class="font-bold text-dark text-sm">{{ $role->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Description: Scope default</p>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-primary/10 text-primary font-mono">{{ $count }} users</span>
                    </div>
                    @endforeach
                </div>

                {{-- Right: Users Table --}}
                <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Staff & Portal Accounts ({{ $users->count() }})</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">User Name</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Email Address</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Assigned Role</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Registered Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($users as $u)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-dark">{{ $u->first_name }} {{ $u->last_name }}</p>
                                        <p class="text-[10px] text-gray-400 font-mono">User ID: {{ $u->id }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 lowercase font-medium">{{ $u->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full bg-accent/10 text-accent-dark uppercase">
                                            {{ $u->role->name ?? 'Default' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $u->created_at?->format('M d, Y') ?? '—' }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">No users found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
