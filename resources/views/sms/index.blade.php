@extends('layouts.app')
@section('title', 'SMS Broadcast')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">SMS Notifications</h1>
                <p class="text-gray-500">Send bulk SMS text messages to parent and staff phone numbers</p>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Compose --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Send SMS</h3>
                    <form method="POST" action="{{ route('sms.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Audience Category</label>
                            <select name="recipient_type" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="guardian">All Parents & Guardians</option>
                                <option value="teacher">All Teachers</option>
                                <option value="custom">Custom Phone Number</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Phone Number (If Custom)</label>
                            <input type="text" name="phone_number" placeholder="e.g. +2348030000000" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">SMS Message (160 characters max)</label>
                            <textarea name="message" required placeholder="Write text message..." maxlength="160" rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Send Broadcast</button>
                    </form>
                </div>

                {{-- Logs --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">SMS Broadcast History</h3></div>
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <p class="text-sm font-semibold mb-1">Gateway Ready</p>
                        <p class="text-xs">Once configured, text deliveries will show status indicators (Delivered, Pending, Failed).</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
