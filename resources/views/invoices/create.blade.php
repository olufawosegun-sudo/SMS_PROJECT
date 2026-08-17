@extends('layouts.app')
@section('title', 'Create Invoice')
@section('body')
@php
    $currencySymbol = $school->currency_symbol ?? '₦';
@endphp
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">Create Student Invoice</h1>
                    <p class="text-gray-500">Select or write directly in each container — suggestions shown but you can enter your own</p>
                </div>
                <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors text-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Invoices
                </a>
            </div>

            @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-start gap-2 text-red-700">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <ul class="text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            {{-- Datalist suggestions for Students --}}
            <datalist id="student_suggestions">
                @foreach($students as $st)
                <option value="{{ $st->user->first_name }} {{ $st->user->last_name }} ({{ $st->admission_no ?? 'ID:'.$st->id }}) — {{ $st->schoolClass->name ?? 'No Class' }}" data-id="{{ $st->id }}">
                @endforeach
            </datalist>

            {{-- Datalist suggestions for Billing Schedule --}}
            <datalist id="schedule_suggestions">
                <option value="First Term {{ date('Y') }}/{{ date('Y')+1 }}">
                <option value="Second Term {{ date('Y') }}/{{ date('Y')+1 }}">
                <option value="Third Term {{ date('Y') }}/{{ date('Y')+1 }}">
                <option value="Full Academic Session {{ date('Y') }}/{{ date('Y')+1 }}">
                <option value="Monthly Installment">
                <option value="Mid-Term Assessment Fee">
                <option value="Graduation & Final Term">
            </datalist>

            {{-- Datalist suggestions for Fee Categories --}}
            <datalist id="fee_category_suggestions">
                @foreach($feeCategories as $fc)
                <option value="{{ $fc->name }}" data-amount="{{ $fc->amount }}" data-id="{{ $fc->id }}">
                @endforeach
                <option value="Tuition Fee">
                <option value="Science Practical & Lab Fee">
                <option value="Examination & Continuous Assessment">
                <option value="Uniform & Sports Wear">
                <option value="Library & E-Learning Fee">
                <option value="Hostel & Boarding Accommodation">
                <option value="School Bus Transportation">
                <option value="Educational Excursion & Field Trip">
            </datalist>

            <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Left Column: Form Containers --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- 1. Invoice Recipient & Schedule Container --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-5">
                            <div class="pb-3 border-b border-gray-100">
                                <h3 class="text-lg font-bold text-dark flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Invoice Recipient & Schedule
                                </h3>
                                <p class="text-xs text-gray-400 mt-0.5">Select from list or write directly in each container</p>
                            </div>

                            <div class="space-y-4">
                                {{-- Student Recipient Container --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Student Recipient *</label>
                                    <input type="text"
                                           name="student_name"
                                           id="studentInput"
                                           required
                                           placeholder="e.g. Ama Kofi (AGS-2026-001) or type student name"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-semibold text-dark"
                                           value="{{ old('student_name') }}"
                                           list="student_suggestions"
                                           autocomplete="off">
                                    <input type="hidden" name="student_id" id="student_id_hidden" value="{{ old('student_id') }}">
                                    <p class="text-xs text-gray-400 mt-1">Type any student name or admission number — suggestions shown but you can write or select directly</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    {{-- Schedule Container --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Billing Schedule / Term *</label>
                                        <input type="text"
                                               name="schedule_term"
                                               id="scheduleInput"
                                               required
                                               placeholder="e.g. First Term, Second Term, Annual"
                                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-semibold text-dark"
                                               value="{{ old('schedule_term', 'First Term') }}"
                                               list="schedule_suggestions"
                                               autocomplete="off">
                                        <p class="text-xs text-gray-400 mt-1">Select from suggestions or enter your own schedule</p>
                                    </div>

                                    {{-- Due Date Container --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-600 mb-2">Payment Due Date *</label>
                                        <input type="date"
                                               name="due_date"
                                               id="dueDateInput"
                                               required
                                               value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}"
                                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-semibold text-dark">
                                        <p class="text-xs text-gray-400 mt-1">Payment settlement deadline</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Fee Categories Container --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-3">
                                <div>
                                    <h3 class="text-lg font-bold text-dark flex items-center gap-2">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        Fee Categories & Billing Items
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-0.5">Select standard fee items or type any custom description directly into the container</p>
                                </div>
                                <button type="button" id="addItemBtn"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary/10 text-primary hover:bg-primary/20 rounded-xl transition-all text-sm font-bold shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    Add Fee Item
                                </button>
                            </div>

                            {{-- Line Items List --}}
                            <div id="lineItemsContainer" class="space-y-4 mt-4">
                                {{-- Item Row 0 --}}
                                <div class="line-item bg-gray-50/70 hover:bg-gray-50 rounded-2xl p-4 border border-gray-200/80 transition-all space-y-3" data-index="0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary text-xs font-bold flex items-center justify-center item-number">1</span>
                                            <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Fee / Expense Item</span>
                                        </div>
                                        <button type="button" class="remove-item-btn text-xs font-semibold text-danger hover:underline hidden flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Remove
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                                        {{-- Category Name Input (Select or Write) --}}
                                        <div class="sm:col-span-7">
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Fee Category *</label>
                                            <input type="text"
                                                   name="items[0][category_name]"
                                                   required
                                                   placeholder="e.g. Tuition Fee, Science Lab, Excursion..."
                                                   class="category-input w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-semibold text-dark"
                                                   list="fee_category_suggestions"
                                                   autocomplete="off">
                                            <input type="hidden" name="items[0][fee_category_id]" class="category-id-hidden" value="">
                                            <p class="text-[11px] text-gray-400 mt-1">Select from suggestions or write your own custom category</p>
                                        </div>

                                        {{-- Amount Input --}}
                                        <div class="sm:col-span-5">
                                            <label class="block text-xs font-semibold text-gray-600 mb-1">Amount ({{ $currencySymbol }}) *</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-bold">{{ $currencySymbol }}</span>
                                                <input type="number"
                                                       name="items[0][amount]"
                                                       required
                                                       step="0.01"
                                                       min="0.01"
                                                       placeholder="0.00"
                                                       class="item-amount w-full pl-8 pr-3 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm text-right font-bold text-dark">
                                            </div>
                                            <p class="text-[11px] text-gray-400 mt-1">Fee charge for this item</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Live Summary Card --}}
                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm sticky top-24">
                            <h3 class="text-lg font-bold text-dark mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                Live Invoice Summary
                            </h3>

                            <div class="mb-4 p-3 bg-gray-50 rounded-xl space-y-1 text-xs">
                                <div class="flex justify-between text-gray-500">
                                    <span>Recipient:</span>
                                    <span class="font-bold text-dark truncate max-w-[150px]" id="summaryRecipient">None selected</span>
                                </div>
                                <div class="flex justify-between text-gray-500">
                                    <span>Schedule:</span>
                                    <span class="font-bold text-dark" id="summarySchedule">First Term</span>
                                </div>
                            </div>

                            {{-- Summary items list --}}
                            <div id="summaryItems" class="space-y-2 mb-4 max-h-52 overflow-y-auto pr-1 divide-y divide-gray-50">
                                <p class="text-xs text-gray-400 italic py-4 text-center" id="summaryEmpty">No line items added yet</p>
                            </div>

                            {{-- Divider & Total --}}
                            <div class="border-t-2 border-dashed border-gray-200 pt-4 mb-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-bold text-gray-600 uppercase tracking-wide">Total Invoiced</span>
                                    <span class="text-2xl font-extrabold text-primary" id="totalAmount">{{ $currencySymbol }}0.00</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1" id="itemCountLabel">0 items</p>
                            </div>

                            {{-- Submit button --}}
                            <button type="submit" id="submitBtn"
                                    class="w-full py-3.5 bg-primary text-white rounded-xl hover:bg-primary-dark transition-all font-bold text-sm shadow-lg shadow-primary/20 hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                                    disabled>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Generate & Issue Invoice
                            </button>
                            <p class="text-[10px] text-gray-400 text-center mt-2">Saved into student billing ledger &amp; financial reports</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>

{{-- Interactive Scripting --}}
<script>
const feeCategoriesMap = @json($feeCategories->mapWithKeys(fn($fc) => [strtolower($fc->name) => ['id' => $fc->id, 'name' => $fc->name, 'amount' => (float)$fc->amount]]));
const currencySymbol = @json($currencySymbol);

document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('lineItemsContainer');
    const addBtn = document.getElementById('addItemBtn');
    const totalEl = document.getElementById('totalAmount');
    const itemCountEl = document.getElementById('itemCountLabel');
    const submitBtn = document.getElementById('submitBtn');
    const summaryItems = document.getElementById('summaryItems');
    const studentInput = document.getElementById('studentInput');
    const studentHidden = document.getElementById('student_id_hidden');
    const scheduleInput = document.getElementById('scheduleInput');
    const summaryRecipient = document.getElementById('summaryRecipient');
    const summarySchedule = document.getElementById('summarySchedule');
    let itemIndex = 1;

    // Student input changes
    studentInput.addEventListener('input', function() {
        const val = this.value.trim();
        summaryRecipient.textContent = val.length > 0 ? val.split(' (')[0] : 'None selected';
        recalculate();
    });

    scheduleInput.addEventListener('input', function() {
        summarySchedule.textContent = this.value.trim() || 'First Term';
    });

    function createRow(index) {
        const row = document.createElement('div');
        row.className = 'line-item bg-gray-50/70 hover:bg-gray-50 rounded-2xl p-4 border border-gray-200/80 transition-all space-y-3';
        row.dataset.index = index;
        row.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-primary/10 text-primary text-xs font-bold flex items-center justify-center item-number">${index + 1}</span>
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Fee / Expense Item</span>
                </div>
                <button type="button" class="remove-item-btn text-xs font-semibold text-danger hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Remove
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                <div class="sm:col-span-7">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Fee Category *</label>
                    <input type="text"
                           name="items[${index}][category_name]"
                           required
                           placeholder="e.g. Tuition Fee, Science Lab, Excursion..."
                           class="category-input w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm font-semibold text-dark"
                           list="fee_category_suggestions"
                           autocomplete="off">
                    <input type="hidden" name="items[${index}][fee_category_id]" class="category-id-hidden" value="">
                    <p class="text-[11px] text-gray-400 mt-1">Select from suggestions or write your own custom category</p>
                </div>
                <div class="sm:col-span-5">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Amount (${currencySymbol}) *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-bold">${currencySymbol}</span>
                        <input type="number"
                               name="items[${index}][amount]"
                               required
                               step="0.01"
                               min="0.01"
                               placeholder="0.00"
                               class="item-amount w-full pl-8 pr-3 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm text-right font-bold text-dark">
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">Fee charge for this item</p>
                </div>
            </div>
        `;
        return row;
    }

    addBtn.addEventListener('click', function() {
        const row = createRow(itemIndex);
        container.appendChild(row);
        itemIndex++;

        row.style.opacity = '0';
        row.style.transform = 'translateY(-8px)';
        requestAnimationFrame(() => {
            row.style.transition = 'opacity 0.25s, transform 0.25s';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        });

        updateRemoveButtons();
        bindRowEvents(row);
        recalculate();
    });

    container.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-item-btn');
        if (removeBtn) {
            const row = removeBtn.closest('.line-item');
            row.style.transition = 'opacity 0.2s, transform 0.2s';
            row.style.opacity = '0';
            row.style.transform = 'translateX(20px)';
            setTimeout(() => {
                row.remove();
                renumberRows();
                updateRemoveButtons();
                recalculate();
            }, 200);
        }
    });

    function bindRowEvents(row) {
        const input = row.querySelector('.category-input');
        const hiddenId = row.querySelector('.category-id-hidden');
        const amountInput = row.querySelector('.item-amount');

        input.addEventListener('input', function() {
            const val = this.value.trim();
            const lower = val.toLowerCase();

            if (feeCategoriesMap[lower]) {
                const match = feeCategoriesMap[lower];
                hiddenId.value = match.id;
                if (!amountInput.value || parseFloat(amountInput.value) === 0) {
                    amountInput.value = match.amount.toFixed(2);
                }
            } else {
                hiddenId.value = '';
            }
            recalculate();
        });

        amountInput.addEventListener('input', recalculate);
    }

    // Bind events to first row
    bindRowEvents(container.querySelector('.line-item'));

    function recalculate() {
        const rows = container.querySelectorAll('.line-item');
        let total = 0;
        let count = 0;
        let summaryHtml = '';

        rows.forEach((row, i) => {
            const nameInput = row.querySelector('.category-input');
            const amtInput = row.querySelector('.item-amount');
            const name = nameInput.value.trim() || `Item #${i + 1}`;
            const amt = parseFloat(amtInput.value) || 0;

            if (amt > 0) {
                total += amt;
                count++;
                summaryHtml += `
                    <div class="flex justify-between items-center py-2 text-xs">
                        <span class="font-semibold text-dark truncate pr-2">${name}</span>
                        <span class="font-bold text-primary whitespace-nowrap">${currencySymbol}${amt.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>
                `;
            }
        });

        totalEl.textContent = currencySymbol + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        itemCountEl.textContent = count + ' item' + (count !== 1 ? 's' : '');

        if (summaryHtml) {
            summaryItems.innerHTML = summaryHtml;
        } else {
            summaryItems.innerHTML = '<p class="text-xs text-gray-400 italic py-4 text-center">No line items added yet</p>';
        }

        const studentEntered = studentInput.value.trim().length > 0;
        submitBtn.disabled = !(total > 0 && studentEntered);
    }

    function renumberRows() {
        container.querySelectorAll('.line-item').forEach((row, i) => {
            row.querySelector('.item-number').textContent = i + 1;
        });
    }

    function updateRemoveButtons() {
        const rows = container.querySelectorAll('.line-item');
        rows.forEach(row => {
            const btn = row.querySelector('.remove-item-btn');
            if (rows.length <= 1) {
                btn.classList.add('hidden');
            } else {
                btn.classList.remove('hidden');
            }
        });
    }

    updateRemoveButtons();
});
</script>
@endsection
