@extends('layouts.app')
@section('title', 'Announcements')
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
                <h1 class="text-3xl font-bold text-dark mb-2">📢 Announcements</h1>
                <p class="text-gray-500">View school notices and important announcements</p>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="space-y-6">
                @forelse($announcements as $ann)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
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
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $ann->content }}</p>
                </div>
                @empty
                <div class="bg-white rounded-2xl p-12 border border-gray-100 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <p class="text-sm font-semibold text-gray-500 mb-1">No Announcements Yet</p>
                    <p class="text-xs text-gray-400">School announcements will appear here when published by the administration.</p>
                </div>
                @endforelse
            </div>

            @else
            {{-- ===================== ADMIN VIEW ===================== --}}
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
                        @if(isset($branches) && $branches->count() > 0)
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Campus / Branch</label>
                            <select name="school_branch_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="">All Branches / Main Campus</option>
                                @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
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
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-lg font-bold text-dark leading-tight">{{ $ann->title }}</h4>
                                        @if($ann->schoolBranch)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-primary/10 text-primary">{{ $ann->schoolBranch->name }}</span>
                                        @endif
                                    </div>
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
            @endif
        </div>
    </main>
</div>
@endsection
