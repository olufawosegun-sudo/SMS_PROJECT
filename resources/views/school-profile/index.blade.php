@extends('layouts.app')
@section('title', 'School Profile & Website Settings')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-dark mb-1">School Profile & Website Settings</h1>
                <p class="text-sm text-gray-500">Configure institutional profile, manage public website URLs, branding, and admissions</p>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold text-sm">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm">
                <p class="font-bold mb-1">Please fix the following issues:</p>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- 💎 School Public Portals — Premium Inline Links -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-8 overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-yellow-500 flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900 leading-tight">Your School Public Portals</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Share these links with parents, students & job seekers</p>
                        </div>
                    </div>
                    <span class="hidden sm:inline-flex px-3 py-1.5 rounded-lg bg-gray-100 text-xs font-bold text-gray-500 border border-gray-200/80">
                        Slug: {{ $school->slug }}
                    </span>
                </div>

                <!-- Link Rows -->
                <div class="divide-y divide-gray-50">
                    <!-- School Website -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 sm:px-6 py-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 003 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm font-semibold text-gray-900">School Website</span>
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-wider">Parents Apply Here</span>
                                </div>
                                <p class="text-xs text-gray-400 font-mono truncate mt-0.5">{{ $school->public_url }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button" id="settingsCopyWebsite" onclick="copySettingsLink('{{ $school->public_url }}', 'settingsCopyWebsite')" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-semibold text-gray-700 transition-all active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Copy Link</span>
                            </button>
                            <a href="{{ $school->public_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 rounded-xl text-xs font-semibold text-white transition-all active:scale-95 shadow-sm">
                                <span>Visit</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Careers Portal -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 sm:px-6 py-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-sm font-semibold text-gray-900">Careers Portal</span>
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-100 text-blue-700 uppercase tracking-wider">Recruitment</span>
                                </div>
                                <p class="text-xs text-gray-400 font-mono truncate mt-0.5">{{ $school->careers_url }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button" id="settingsCopyCareers" onclick="copySettingsLink('{{ $school->careers_url }}', 'settingsCopyCareers')" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-semibold text-gray-700 transition-all active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Copy Link</span>
                            </button>
                            <a href="{{ $school->careers_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-xl text-xs font-semibold text-white transition-all active:scale-95 shadow-sm">
                                <span>Visit</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copy-to-clipboard micro-interaction -->
            <script>
            function copySettingsLink(url, btnId) {
                navigator.clipboard.writeText(url).then(() => {
                    const btn = document.getElementById(btnId);
                    const original = btn.innerHTML;
                    btn.innerHTML = `<svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg><span class="text-emerald-600">Copied!</span>`;
                    btn.classList.add('bg-emerald-50', 'border', 'border-emerald-200');
                    setTimeout(() => {
                        btn.innerHTML = original;
                        btn.classList.remove('bg-emerald-50', 'border', 'border-emerald-200');
                    }, 2000);
                });
            }
            </script>

            <!-- Profile and Website Customization Form -->
            <form method="POST" action="{{ route('school-profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: School Identity Card -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-center">
                            <div class="mb-4 relative inline-block">
                                @if($school->logo)
                                    <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="w-24 h-24 rounded-2xl object-contain mx-auto border border-gray-200 p-1 shadow-sm bg-white">
                                @else
                                    <div class="w-24 h-24 bg-primary/10 text-primary rounded-2xl mx-auto flex items-center justify-center font-bold text-4xl shadow-sm">
                                        {{ substr($school->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <h3 class="text-lg font-bold text-dark leading-tight">{{ $school->name }}</h3>
                            <p class="text-xs text-gray-400 mt-1 italic">"{{ $school->motto ?? 'Education is Light' }}"</p>
                            <span class="inline-block mt-3 px-3 py-1 bg-gray-100 rounded-full text-xs font-semibold text-gray-600">
                                School Code: {{ $school->school_code }}
                            </span>

                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <label class="block text-xs font-bold text-gray-700 mb-2 text-left">Update School Logo</label>
                                <input type="file" name="school_logo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:brightness-110">
                            </div>
                        </div>

                        <!-- Brand Colors -->
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                            <h4 class="font-bold text-dark text-sm border-b border-gray-100 pb-3">Website Colors</h4>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Primary Theme Color</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="primary_color" value="{{ $school->primary_color ?? '#1B6B3E' }}" class="h-10 w-14 rounded-xl border border-gray-200 cursor-pointer p-1">
                                    <input type="text" value="{{ $school->primary_color ?? '#1B6B3E' }}" class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-xs font-mono" readonly>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Secondary Accent Color</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="secondary_color" value="{{ $school->secondary_color ?? '#EAA315' }}" class="h-10 w-14 rounded-xl border border-gray-200 cursor-pointer p-1">
                                    <input type="text" value="{{ $school->secondary_color ?? '#EAA315' }}" class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-xs font-mono" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Admission Status -->
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-3">
                            <h4 class="font-bold text-dark text-sm border-b border-gray-100 pb-3">Admission Status</h4>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-2">Public Admission Applications</label>
                                <select name="admission_status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="open" {{ ($school->admission_status ?? 'open') === 'open' ? 'selected' : '' }}>🟢 Open (Accepting Applications)</option>
                                    <option value="closed" {{ ($school->admission_status ?? 'open') === 'closed' ? 'selected' : '' }}>🔴 Closed (Paused)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Institutional Details & Website Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- 1. Institutional Information -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-4">
                            <h3 class="text-base font-bold text-dark border-b border-gray-100 pb-3">Institutional Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">School Name <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $school->name) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Subdomain / URL Slug</label>
                                    <div class="flex items-center">
                                        <span class="px-3 py-2.5 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-xs text-gray-500">/school/</span>
                                        <input type="text" name="slug" value="{{ old('slug', $school->slug) }}" required class="flex-1 px-3 py-2.5 border border-gray-200 rounded-r-xl text-sm font-mono focus:ring-2 focus:ring-primary focus:outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Motto / Tagline</label>
                                    <input type="text" name="motto" value="{{ old('motto', $school->motto) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Official Phone Number</label>
                                    <input type="text" name="phone" value="{{ old('phone', $school->phone) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Official Email</label>
                                    <input type="email" name="email" value="{{ old('email', $school->email) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Custom External Website (Optional)</label>
                                    <input type="url" name="website" value="{{ old('website', $school->website) }}" placeholder="https://example.com" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Country & Regional Currency <span class="text-rose-500">*</span></label>
                                    <select name="country" id="profileCountrySelect" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-dark focus:ring-2 focus:ring-primary focus:outline-none bg-white">
                                        @foreach($countries ?? \App\Models\School::getCountryCurrencyMap() as $cName => $cData)
                                            <option value="{{ $cName }}"
                                                    data-symbol="{{ $cData['symbol'] }}"
                                                    data-code="{{ $cData['code'] }}"
                                                    {{ (old('country', $school->country ?: 'Nigeria') === $cName) ? 'selected' : '' }}>
                                                {{ $cData['flag'] ?? '🌍' }} {{ $cName }} ({{ $cData['code'] }} • {{ $cData['symbol'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">State / Province / Region</label>
                                    <input type="text" name="state" value="{{ old('state', $school->state) }}" placeholder="e.g. Lagos State / Greater Accra" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">City / Town</label>
                                    <input type="text" name="city" value="{{ old('city', $school->city) }}" placeholder="e.g. Ikeja / Accra" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Physical Campus Address</label>
                                    <textarea name="address" rows="2" placeholder="Street address, campus building..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">{{ old('address', $school->address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Official Banking & Fee Payment Configuration -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-5">
                            <div class="pb-3 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-bold text-dark flex items-center gap-2">
                                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                        Official Banking & Fee Collection Settings
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-0.5">These official bank &amp; mobile money details are displayed on student invoice URLs for fee payments</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-lg bg-primary/10 text-primary font-bold text-xs">{{ $school->country ?? 'National' }} Banking</span>
                            </div>

                            <datalist id="country_bank_suggestions">
                                @php
                                    $bankList = $school->resolved_banking_details['available_country_banks'] ?? [];
                                @endphp
                                @foreach($bankList as $b)
                                    <option value="{{ $b }}">
                                @endforeach
                            </datalist>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Official Bank Name *</label>
                                    <input type="text"
                                           name="bank_name"
                                           value="{{ old('bank_name', $school->bank_name ?? $school->resolved_banking_details['bank_name']) }}"
                                           placeholder="e.g. {{ $school->resolved_banking_details['bank_name'] }}"
                                           list="country_bank_suggestions"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-dark focus:ring-2 focus:ring-primary focus:outline-none">
                                    <p class="text-[11px] text-gray-400 mt-1">Select from suggestions or enter your school's exact commercial bank</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">School Account Number *</label>
                                    <input type="text"
                                           name="account_number"
                                           value="{{ old('account_number', $school->account_number) }}"
                                           placeholder="e.g. 0123456789 (NUBAN / IBAN / Account)"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-mono font-bold text-dark focus:ring-2 focus:ring-primary focus:outline-none">
                                    <p class="text-[11px] text-gray-400 mt-1">Official bank account where student fees are deposited</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Official Account Name</label>
                                    <input type="text"
                                           name="account_name"
                                           value="{{ old('account_name', $school->account_name ?: $school->name) }}"
                                           placeholder="e.g. {{ $school->name }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-semibold text-dark focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Bank Branch / Sort Code</label>
                                    <input type="text"
                                           name="bank_branch"
                                           value="{{ old('bank_branch', $school->bank_branch) }}"
                                           placeholder="e.g. Main Commercial Branch / Sort Code"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Mobile Money Network / Provider</label>
                                    <input type="text"
                                           name="momo_network"
                                           value="{{ old('momo_network', $school->momo_network ?? ($school->resolved_banking_details['momo_networks'][0] ?? '')) }}"
                                           placeholder="e.g. MTN MoMo, Telecel Cash, M-Pesa"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Mobile Money / Till / Paybill Number</label>
                                    <input type="text"
                                           name="momo_number"
                                           value="{{ old('momo_number', $school->momo_number) }}"
                                           placeholder="e.g. 0244123456 / 247247"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Custom Payment Instructions for Parents</label>
                                    <textarea name="payment_instructions" rows="2" placeholder="e.g. Please use student admission number as transfer remark or WhatsApp payment receipt to Bursary." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">{{ old('payment_instructions', $school->payment_instructions) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Public Website Content -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-4">
                            <h3 class="text-base font-bold text-dark border-b border-gray-100 pb-3">Public Website Content</h3>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hero Headline Title</label>
                                <input type="text" name="portal_hero_title" value="{{ old('portal_hero_title', $school->portal_hero_title) }}" placeholder="e.g. Nurturing Future Leaders with Academic Distinction" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hero Subtitle / Introductory Note</label>
                                <textarea name="portal_hero_subtitle" rows="2" placeholder="Brief introduction displayed on the hero banner..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">{{ old('portal_hero_subtitle', $school->portal_hero_subtitle) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">About Us Description</label>
                                <textarea name="about_text" rows="4" placeholder="Detailed history, mission, vision, and core values of your school..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">{{ old('about_text', $school->about_text) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Principal's / Proprietor's Name</label>
                                    <input type="text" name="welcome_title" value="{{ old('welcome_title', $school->welcome_title) }}" placeholder="e.g. Dr. A. Oluwaseun (Principal)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Principal's Welcome Note</label>
                                    <textarea name="welcome_message" rows="3" placeholder="Welcome message to prospective parents and students..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">{{ old('welcome_message', $school->welcome_message) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Social Media Links -->
                        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-4">
                            <h3 class="text-base font-bold text-dark border-b border-gray-100 pb-3">Social Media Handles</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Facebook Page URL</label>
                                    <input type="url" name="social_facebook" value="{{ old('social_facebook', $school->social_facebook) }}" placeholder="https://facebook.com/yourschool" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Instagram Profile URL</label>
                                    <input type="url" name="social_instagram" value="{{ old('social_instagram', $school->social_instagram) }}" placeholder="https://instagram.com/yourschool" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Twitter / X URL</label>
                                    <input type="url" name="social_twitter" value="{{ old('social_twitter', $school->social_twitter) }}" placeholder="https://twitter.com/yourschool" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">LinkedIn Page URL</label>
                                    <input type="url" name="social_linkedin" value="{{ old('social_linkedin', $school->social_linkedin) }}" placeholder="https://linkedin.com/company/yourschool" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-8 py-3.5 bg-primary text-white rounded-2xl hover:bg-primary-dark transition-all font-bold text-sm shadow-md active:scale-95">
                                Save Profile & Website Settings
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
