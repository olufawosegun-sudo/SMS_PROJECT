@extends('layouts.app')
@section('title', 'Direct Messages')
@section('body')
@php $userRole = Auth::user()->role->name; @endphp
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar')
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">

            @if(in_array($userRole, ['Student', 'Guardian']))
            {{-- ===================== STUDENT VIEW ===================== --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">💬 Messages</h1>
                <p class="text-gray-500">View messages from your teachers and school administration</p>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-dark">Inbox</h3>
                </div>
                <div class="p-8 text-center text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <p class="text-sm font-semibold mb-1">No Messages Yet</p>
                    <p class="text-xs">Messages from your teachers and school administration will appear here.</p>
                </div>
            </div>

            @else
            {{-- ===================== ADMIN VIEW ===================== --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">Direct Messaging</h1>
                <p class="text-gray-500">Send secure internal messages to staff, students, and parent portals</p>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Compose --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Compose Message</h3>
                    <form method="POST" action="{{ route('messages.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Recipient Category</label>
                            <select name="recipient_type" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="all">All School Roles</option>
                                <option value="teacher">All Teachers</option>
                                <option value="student">All Students</option>
                                <option value="guardian">All Guardians</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Subject</label>
                            <input type="text" name="subject" required placeholder="Subject title" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Message Body</label>
                            <textarea name="body" required placeholder="Write message..." rows="5" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Send Message</button>
                    </form>
                </div>

                {{-- Message Logs --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Message History</h3></div>
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <p class="text-sm font-semibold mb-1">No Internal Messages</p>
                        <p class="text-xs">Once messages are sent, the full conversation logs and student replies will appear here.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection
