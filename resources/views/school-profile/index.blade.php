@extends('layouts.app')
@section('title', 'School Profile')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">School Settings</h1>
                <p class="text-gray-500">View and update institutional information and contact profiles</p>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Details display --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <div class="text-center pb-6 border-b border-gray-150">
                        <div class="w-20 h-20 bg-primary/10 text-primary rounded-2xl mx-auto flex items-center justify-center font-bold text-3xl mb-4">
                            {{ substr($school->name, 0, 1) }}
                        </div>
                        <h3 class="text-lg font-bold text-dark leading-tight">{{ $school->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1 italic">"{{ $school->motto ?? 'Education is light' }}"</p>
                    </div>

                    <div class="pt-6 space-y-4 text-xs font-semibold text-gray-700">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Phone</span>
                            <span>{{ $school->phone ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Email</span>
                            <span class="lowercase">{{ $school->email ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Website</span>
                            <span class="lowercase text-primary">{{ $school->website ?? '—' }}</span>
                        </div>
                        <div class="flex flex-col gap-1 border-t border-gray-100 pt-3">
                            <span class="text-gray-400 font-bold uppercase tracking-wider text-[10px]">Address</span>
                            <span class="text-gray-600 font-normal leading-relaxed mt-0.5">{{ $school->address ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Update Form --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-6">Edit Profile Info</h3>
                    <form method="POST" action="{{ route('school-profile.update') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">School Name</label>
                                <input type="text" name="name" value="{{ $school->name }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Motto / Tagline</label>
                                <input type="text" name="motto" value="{{ $school->motto }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Telephone Number</label>
                                <input type="text" name="phone" value="{{ $school->phone }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Official Email</label>
                                <input type="email" name="email" value="{{ $school->email }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Website URL</label>
                                <input type="url" name="website" value="{{ $school->website }}" placeholder="https://example.com" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Address</label>
                                <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">{{ $school->address }}</textarea>
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
