@extends('layouts.app')
@section('title', 'Staff Payroll')
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
                    <h1 class="text-3xl font-bold text-dark mb-2">Staff Payroll</h1>
                    <p class="text-gray-500">Manage monthly salaries, allowances, and deductions for all staff members</p>
                </div>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            {{-- Summary Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-2xl font-extrabold text-danger mb-1">{{ $currencySymbol }}{{ number_format($summary['total_paid'], 2) }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Payroll Outflow</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-2xl font-extrabold text-primary mb-1">{{ $currencySymbol }}{{ number_format($summary['this_month'], 2) }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Salary Outflow (This Month)</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <p class="text-2xl font-extrabold text-accent-dark mb-1">{{ $summary['count'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Slips Generated</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Create Pay Slip --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Record Payroll Slip</h3>
                    <form method="POST" action="{{ route('payroll.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Select Staff Member</label>
                            <select name="staff_id" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="">Select Staff</option>
                                @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}">
                                    {{ $staff->user->first_name }} {{ $staff->user->last_name }} 
                                    ({{ $staff->staff_type }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Basic Salary</label>
                            <input type="number" name="basic_salary" required placeholder="0.00" min="0" step="0.01" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Allowance</label>
                                <input type="number" name="allowance" placeholder="0.00" min="0" step="0.01" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Deduction</label>
                                <input type="number" name="deduction" placeholder="0.00" min="0" step="0.01" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Month</label>
                                <select name="month" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                    @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                                    <option value="{{ $m }}" {{ date('F') === $m ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-2">Year</label>
                                <input type="number" name="year" required value="{{ date('Y') }}" min="2020" max="2100" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Release Payslip</button>
                    </form>
                </div>

                {{-- Right: Payroll Logs --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm h-fit">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Released Slips</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Slip Ref</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Staff Member</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Month/Year</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Basic</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Allowance</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Deduction</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Net Salary</th>
                                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($payrolls as $pay)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono font-bold text-primary">SLIP-{{ $pay->id }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-dark">
                                        <p>{{ $pay->staff->user->first_name ?? 'N/A' }} {{ $pay->staff->user->last_name ?? '' }}</p>
                                        <p class="text-[10px] text-gray-400">
                                            {{ $pay->staff->staff_type ?? 'Staff' }} • Paid: {{ $pay->paid_at?->format('M d, Y') ?? '—' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $pay->month }}, {{ $pay->year }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $currencySymbol }}{{ number_format($pay->basic_salary, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-success">+{{ $currencySymbol }}{{ number_format($pay->allowance, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-danger">-{{ $currencySymbol }}{{ number_format($pay->deduction, 2) }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-dark">{{ $currencySymbol }}{{ number_format($pay->net_salary, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('payroll.destroy', $pay->id) }}" onsubmit="return confirm('Remove payroll record?')">
                                            @csrf @method('DELETE')
                                            <button class="text-xs text-danger font-semibold hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="px-6 py-12 text-center text-gray-400">No payroll slips issued yet.</td></tr>
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
