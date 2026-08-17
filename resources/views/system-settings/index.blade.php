@extends('layouts.app')
@section('title', 'System Configuration & Currency')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-dark mb-1">System Configuration & Currency</h1>
                <p class="text-sm text-gray-500">Configure country-specific currency localization, system diagnostics, and operational preferences</p>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-success font-semibold text-sm">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm">
                <p class="font-bold mb-1">Please check the following:</p>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Country & Currency Configuration Form --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-5">
                        <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 font-bold text-lg" id="flagDisplay">
                                {{ $currencyInfo['flag'] ?? '🌍' }}
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-dark leading-tight">Country & Currency</h3>
                                <p class="text-xs text-gray-400">Dynamic country currency binding</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('system-settings.update') }}" class="space-y-4" id="systemSettingsForm">
                            @csrf

                            {{-- 1. Country Selection --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                    School Country <span class="text-rose-500">*</span>
                                </label>
                                <select name="country" id="countrySelect" required class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white transition-all font-semibold text-dark">
                                    @foreach($countries as $countryName => $cData)
                                        <option value="{{ $countryName }}"
                                                data-code="{{ $cData['code'] }}"
                                                data-symbol="{{ $cData['symbol'] }}"
                                                data-name="{{ $cData['name'] }}"
                                                data-flag="{{ $cData['flag'] ?? '🌍' }}"
                                                {{ (old('country', $school->country ?: 'Nigeria') === $countryName) ? 'selected' : '' }}>
                                            {{ $cData['flag'] ?? '' }} {{ $countryName }} ({{ $cData['code'] }} • {{ $cData['symbol'] }})
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-gray-400 mt-1">Selecting a country automatically assigns its official currency across all fees, invoices, receipts, and payroll.</p>
                            </div>

                            {{-- 2. Currency Symbol (Auto-synced from Country) --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Currency Symbol</label>
                                    <input type="text" name="currency_symbol" id="currencySymbolInput"
                                           value="{{ old('currency_symbol', $school->currency_symbol) }}"
                                           required
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold text-dark bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">ISO Code</label>
                                    <input type="text" name="currency" id="currencyCodeInput"
                                           value="{{ old('currency', $school->currency_code) }}"
                                           required
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-bold font-mono text-dark bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary transition-all">
                                </div>
                            </div>

                            {{-- Live Currency Preview Card --}}
                            <div class="p-4 bg-gradient-to-br from-emerald-500/10 to-teal-500/5 border border-emerald-500/20 rounded-2xl space-y-2">
                                <div class="flex items-center justify-between text-xs text-emerald-800 font-semibold">
                                    <span>Active Currency</span>
                                    <span id="activeCurrencyName" class="font-bold">{{ $currencyInfo['name'] }}</span>
                                </div>
                                <div class="pt-2 border-t border-emerald-500/10 flex items-center justify-between">
                                    <span class="text-[11px] text-gray-500">Sample Invoice Total</span>
                                    <span class="text-base font-black text-emerald-700" id="sampleInvoiceDisplay">
                                        {{ $school->currency_symbol }}150,000.00
                                    </span>
                                </div>
                            </div>

                            {{-- Backup Frequency --}}
                            <div class="pt-2 border-t border-gray-100">
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Automatic Database Backup</label>
                                <select name="backup_freq" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:outline-none bg-white">
                                    @php $currentFreq = $backupFreqSetting->value ?? 'weekly'; @endphp
                                    <option value="daily" {{ $currentFreq === 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ $currentFreq === 'weekly' ? 'selected' : '' }}>Weekly (Recommended)</option>
                                    <option value="monthly" {{ $currentFreq === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                </select>
                            </div>

                            {{-- Maintenance Mode --}}
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                @php $isMaint = ($maintenanceModeSetting->value ?? '0') === '1'; @endphp
                                <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" {{ $isMaint ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                                <label for="maintenance_mode" class="text-xs font-medium text-gray-700 cursor-pointer">Enable School Maintenance Mode</label>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-primary text-white rounded-2xl hover:bg-primary-dark transition-all font-bold text-sm shadow-md active:scale-95">
                                Save System Configuration
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Right: System diagnostics & Country Currency Reference --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Country Currency Directory --}}
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <h3 class="text-base font-bold text-dark">Supported African & Regional Currencies</h3>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-primary/10 text-primary">
                                Auto-Applied System-Wide
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">When your school's country is set, all financial modules (Invoices, Student Fee Payments, WAEC Registration, Staff Payroll, and Reports) immediately format amounts in your local currency.</p>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 pt-2">
                            @foreach([
                                ['country' => 'Nigeria', 'flag' => '🇳🇬', 'code' => 'NGN', 'symbol' => '₦', 'sample' => '₦50,000'],
                                ['country' => 'Ghana', 'flag' => '🇬🇭', 'code' => 'GHS', 'symbol' => 'GH₵', 'sample' => 'GH₵2,500'],
                                ['country' => 'Sierra Leone', 'flag' => '🇸🇱', 'code' => 'SLE', 'symbol' => 'Le', 'sample' => 'Le 1,200'],
                                ['country' => 'Liberia', 'flag' => '🇱🇷', 'code' => 'LRD', 'symbol' => 'L$', 'sample' => 'L$ 15,000'],
                                ['country' => 'The Gambia', 'flag' => '🇬🇲', 'code' => 'GMD', 'symbol' => 'D', 'sample' => 'D 3,500'],
                                ['country' => 'Kenya', 'flag' => '🇰🇪', 'code' => 'KES', 'symbol' => 'KSh', 'sample' => 'KSh 45,000'],
                                ['country' => 'South Africa', 'flag' => '🇿🇦', 'code' => 'ZAR', 'symbol' => 'R', 'sample' => 'R 8,500'],
                                ['country' => 'Rwanda', 'flag' => '🇷🇼', 'code' => 'RWF', 'symbol' => 'FRw', 'sample' => 'FRw 95,000'],
                                ['country' => 'Uganda', 'flag' => '🇺🇬', 'code' => 'UGX', 'symbol' => 'USh', 'sample' => 'USh 350k'],
                                ['country' => 'Tanzania', 'flag' => '🇹🇿', 'code' => 'TZS', 'symbol' => 'TSh', 'sample' => 'TSh 250k'],
                                ['country' => 'Cameroon', 'flag' => '🇨🇲', 'code' => 'XAF', 'symbol' => 'FCFA', 'sample' => 'FCFA 120k'],
                                ['country' => 'Ivory Coast', 'flag' => '🇨🇮', 'code' => 'XOF', 'symbol' => 'CFA', 'sample' => 'CFA 150k'],
                                ['country' => 'United Kingdom', 'flag' => '🇬🇧', 'code' => 'GBP', 'symbol' => '£', 'sample' => '£1,250'],
                                ['country' => 'United States', 'flag' => '🇺🇸', 'code' => 'USD', 'symbol' => '$', 'sample' => '$1,800'],
                                ['country' => 'Canada', 'flag' => '🇨🇦', 'code' => 'CAD', 'symbol' => 'CA$', 'sample' => 'CA$ 2,200'],
                                ['country' => 'Eurozone', 'flag' => '🇪🇺', 'code' => 'EUR', 'symbol' => '€', 'sample' => '€1,500'],
                            ] as $currCard)
                            <div class="p-3 rounded-2xl bg-gray-50 border border-gray-100 flex flex-col justify-between hover:bg-emerald-50/50 hover:border-emerald-200 transition-all cursor-pointer"
                                 onclick="quickSelectCountry('{{ $currCard['country'] }}')">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-sm">{{ $currCard['flag'] }}</span>
                                    <span class="text-xs font-bold text-gray-800 truncate">{{ $currCard['country'] }}</span>
                                </div>
                                <div class="mt-2 flex items-baseline justify-between">
                                    <span class="text-[10px] font-mono text-gray-400 font-semibold">{{ $currCard['code'] }}</span>
                                    <span class="text-xs font-extrabold text-emerald-600">{{ $currCard['symbol'] }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- System Diagnostics & Environment --}}
                    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-base font-bold text-dark">Platform Environment & Diagnostics</h3>
                            <span class="text-xs font-bold text-emerald-600 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Healthy
                            </span>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($systemInfo as $key => $val)
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $key) }}</p>
                                    <p class="text-xs font-bold text-dark mt-1 font-mono truncate">{{ $val }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Dynamic Country-to-Currency Real-Time Synchronization Script --}}
<script>
function syncCurrencyFromCountry() {
    const select = document.getElementById('countrySelect');
    const selectedOption = select.options[select.selectedIndex];
    if (!selectedOption) return;

    const code = selectedOption.getAttribute('data-code');
    const symbol = selectedOption.getAttribute('data-symbol');
    const name = selectedOption.getAttribute('data-name');
    const flag = selectedOption.getAttribute('data-flag');

    document.getElementById('currencySymbolInput').value = symbol || '$';
    document.getElementById('currencyCodeInput').value = code || 'USD';
    document.getElementById('flagDisplay').innerText = flag || '🌍';
    document.getElementById('activeCurrencyName').innerText = name || (code + ' (' + symbol + ')');
    document.getElementById('sampleInvoiceDisplay').innerText = (symbol || '$') + '150,000.00';
}

function quickSelectCountry(countryName) {
    const select = document.getElementById('countrySelect');
    for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value.toLowerCase() === countryName.toLowerCase()) {
            select.selectedIndex = i;
            syncCurrencyFromCountry();
            // Smooth scroll to form on mobile
            document.getElementById('systemSettingsForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
            break;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('countrySelect');
    if (select) {
        select.addEventListener('change', syncCurrencyFromCountry);
    }

    // Dynamic update when typing custom symbol
    const symbolInput = document.getElementById('currencySymbolInput');
    if (symbolInput) {
        symbolInput.addEventListener('input', function() {
            const sym = this.value || '$';
            document.getElementById('sampleInvoiceDisplay').innerText = sym + '150,000.00';
        });
    }
});
</script>
@endsection
