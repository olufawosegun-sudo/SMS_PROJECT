@extends('layouts.app')
@section('title', 'Fee Categories')
@section('body')
@php
    $currencySymbol = match(strtolower($school->country ?? '')) {
        'nigeria' => '₦',
        'ghana' => 'GH₵',
        'kenya' => 'KSh',
        'south africa' => 'R',
        'united kingdom', 'uk' => '£',
        'united states', 'us', 'usa' => '$',
        default => '$',
    };
@endphp
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">Tuition & Fees Setup</h1>
                <p class="text-gray-500">Configure school tuition fee categories and structures</p>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Create Category --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Add Fee Type</h3>
                    <form method="POST" action="{{ route('fee-categories.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Category Name</label>
                            <input type="text" name="name" required placeholder="e.g. First Term Tuition" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Amount</label>
                            <input type="number" name="amount" required min="0" step="0.01" placeholder="0.00" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                            <textarea name="description" placeholder="Notes or instructions" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Fee Category</button>
                    </form>
                </div>

                {{-- Right: Categories Table --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Active Fee Structures ({{ $categories->count() }})</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Category Name</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Amount</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Description</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($categories as $cat)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-dark">{{ $cat->name }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-primary">{{ $currencySymbol }}{{ number_format($cat->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $cat->description ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('fee-categories.destroy', $cat->id) }}" onsubmit="return confirm('Delete fee type?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs text-danger font-semibold hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">No fee categories configured.</td></tr>
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
