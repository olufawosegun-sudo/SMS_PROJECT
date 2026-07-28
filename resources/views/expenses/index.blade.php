@extends('layouts.app')
@section('title', 'Expenses')
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
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">School Operational Expenses</h1>
                    <p class="text-gray-500">Record payments to suppliers, staff salary advances, and utilities</p>
                </div>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Summary Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-danger mb-1">{{ $currencySymbol }}{{ number_format($summary['total'], 2) }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Outflow</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-primary mb-1">{{ $currencySymbol }}{{ number_format($summary['this_month'], 2) }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Spent This Month</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100">
                    <p class="text-2xl font-extrabold text-accent-dark mb-1">{{ $summary['count'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Payments Logged</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Create Expense --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Record Outflow</h3>
                    <form method="POST" action="{{ route('expenses.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Expense Title</label>
                            <input type="text" name="title" required placeholder="e.g. Generator Fuel" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Amount</label>
                            <input type="number" name="amount" required step="0.01" min="0" placeholder="0.00" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Category</label>
                            <select name="category" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="utilities">Utilities & Bills</option>
                                <option value="salaries">Staff Salaries</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="supplies">Stationeries & Supplies</option>
                                <option value="other">Other Operations</option>
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
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Date Paid</label>
                            <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                            <textarea name="description" placeholder="Additional remarks" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Expense</button>
                    </form>
                </div>

                {{-- Right: Expense Log Table --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Outflow Ledger</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Expense Title</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Category</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Branch</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Amount</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($expenses as $exp)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-dark mb-0.5">{{ $exp->title }}</p>
                                        <p class="text-xs text-gray-400 truncate max-w-xs">{{ $exp->description ?? 'No extra details' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 uppercase font-medium">{{ $exp->category->name ?? 'Other' }}</td>
                                    <td class="px-6 py-4 text-xs font-bold"><span class="bg-primary/10 text-primary px-2 py-1 rounded">{{ $exp->schoolBranch->name ?? 'Main Campus' }}</span></td>
                                    <td class="px-6 py-4 text-sm font-bold text-danger">{{ $currencySymbol }}{{ number_format($exp->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $exp->expense_date?->format('M d, Y') ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('expenses.destroy', $exp->id) }}" onsubmit="return confirm('Delete expense record?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs text-danger font-semibold hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No operational outflows recorded yet.</td></tr>
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
