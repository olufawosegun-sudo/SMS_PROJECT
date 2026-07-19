@extends('layouts.app')
@section('title', 'Report Cards')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">Student Report Cards</h1>
                <p class="text-gray-500">Review cumulative term reports and academic profiles</p>
            </div>

            {{-- Filter --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-8">
                <form method="GET" action="{{ route('report-cards.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Class Level</label>
                        <select name="class_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <option value="">All Classes</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Filter</button>
                </form>
            </div>

            {{-- Report Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($students as $student)
                @php
                    $stats = $studentResults[$student->id] ?? ['total_subjects' => 0, 'average' => 0, 'highest' => 0, 'lowest' => 0, 'results' => collect()];
                @endphp
                <div class="bg-white rounded-2xl p-5 border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-dark text-lg leading-tight">{{ $student->user->first_name }} {{ $student->user->last_name }}</h3>
                                <p class="text-xs text-gray-400 mt-1">Class: {{ $student->schoolClass->name ?? 'N/A' }}</p>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $stats['average'] >= 50 ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                                Avg: {{ $stats['average'] }}%
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-2 py-3 bg-gray-50 rounded-xl text-center text-xs font-semibold text-gray-600 mb-4">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Subjects</p>
                                <p class="text-sm font-bold text-dark mt-0.5">{{ $stats['total_subjects'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Highest</p>
                                <p class="text-sm font-bold text-primary mt-0.5">{{ $stats['highest'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Lowest</p>
                                <p class="text-sm font-bold text-accent-dark mt-0.5">{{ $stats['lowest'] }}</p>
                            </div>
                        </div>

                        @if($stats['results']->count() > 0)
                        <div class="space-y-1.5 text-xs max-h-40 overflow-y-auto mb-4 pr-1">
                            @foreach($stats['results'] as $res)
                            <div class="flex justify-between border-b border-gray-50 pb-1">
                                <span class="text-gray-600 font-medium">{{ $res->subject->name ?? 'N/A' }}</span>
                                <span class="font-bold text-gray-700">{{ $res->total_score }}% ({{ $res->grade }})</span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-xs text-gray-400 italic py-4 text-center">No exam results recorded.</p>
                        @endif
                    </div>

                    <button class="w-full py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-xs font-semibold transition-colors" onclick="alert('Printing PDF report card functionality ready.')">Print Report Card</button>
                </div>
                @empty
                <div class="col-span-full bg-white rounded-2xl p-8 border border-gray-100 text-center text-gray-400">
                    No student report cards found. Select a class above to narrow down results.
                </div>
                @endforelse
            </div>
        </div>
    </main>
</div>
@endsection
