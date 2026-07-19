@extends('layouts.app')
@section('title', 'Announcements')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">School Announcements</h1>
                    <p class="text-gray-500">Publish notices to student portals, teachers, and guardians</p>
                </div>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Publish Announcement --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Publish Notice</h3>
                    <form method="POST" action="{{ route('announcements.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Notice Title</label>
                            <input type="text" name="title" required placeholder="e.g. End of Term Examination Notice" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Audience Target</label>
                            <select name="audience" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="all">Everyone (All users)</option>
                                <option value="teachers">Staff & Teachers Only</option>
                                <option value="students">Students Only</option>
                                <option value="guardians">Parents & Guardians Only</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Priority Level</label>
                            <select name="priority" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="low">Low</option>
                                <option value="normal" selected>Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Announcement Message</label>
                            <textarea name="content" required placeholder="Write details here..." rows="4" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Publish Notice</button>
                    </form>
                </div>

                {{-- Right: Announcements Feed --}}
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="text-lg font-bold text-dark mb-4">Published Bulletin Board</h3>
                    @forelse($announcements as $ann)
                    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                        <div>
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="text-lg font-bold text-dark leading-tight">{{ $ann->title }}</h4>
                                    <p class="text-xs text-gray-400 mt-1">Published: {{ $ann->published_at?->format('M d, Y h:i A') ?? $ann->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="flex gap-2">
                                    @php
                                        $priorColors = ['low' => 'bg-gray-100 text-gray-700', 'normal' => 'bg-info/10 text-info', 'high' => 'bg-warning/10 text-warning', 'urgent' => 'bg-danger/10 text-danger'];
                                        $class = $priorColors[$ann->priority] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $class }}">{{ $ann->priority }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-accent/10 text-accent-dark">To: {{ $ann->audience }}</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 whitespace-pre-line mb-4">{{ $ann->content }}</p>
                        </div>

                        <div class="border-t border-gray-100 pt-3 flex justify-end">
                            <form method="POST" action="{{ route('announcements.destroy', $ann->id) }}" onsubmit="return confirm('Remove announcement notice?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-danger font-semibold hover:underline">Delete Notice</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-2xl p-8 border border-gray-100 text-center text-gray-400">
                        No announcements posted. Use the panel on the left to write a school-wide notice.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
