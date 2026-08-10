@extends('layouts.app')
@section('title', 'WAEC Fee Configuration')
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
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-dark mb-2">WAEC Fee Configuration</h1>
                    <p class="text-gray-500">Configure WAEC examination fees by session</p>
                </div>
                <a href="{{ route('owner.waec.reports') }}" 
                   class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-dark rounded-xl font-semibold transition-colors duration-200">
                    Back to Reports
                </a>
            </div>

            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-danger/10 border border-danger/20 rounded-xl">
                <p class="text-danger font-semibold">{{ session('error') }}</p>
            </div>
            @endif

            {{-- Configuration Form --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-6">
                <h3 class="text-lg font-bold text-dark mb-4">Add/Update Fee Configuration</h3>
                
                <form method="POST" action="{{ route('owner.waec.fees.update') }}" id="fee-form">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Select Session <span class="text-danger">*</span></label>
                        <select name="session_id" 
                                required 
                                id="session-select"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="">-- Select Session --</option>
                            @foreach($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="fees-container" class="space-y-4 mb-6">
                        {{-- Examination Fee --}}
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <h4 class="font-semibold text-dark mb-3">Examination Fee</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <input type="hidden" name="fees[0][fee_type]" value="examination_fee">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Fee Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="fees[0][fee_name]" 
                                           value="WAEC Examination Fee"
                                           required
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Amount ({{ $currencySymbol }}) <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="fees[0][amount]" 
                                           step="0.01"
                                           min="0"
                                           required
                                           placeholder="0.00"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Status <span class="text-danger">*</span></label>
                                    <select name="fees[0][status]" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                                    <textarea name="fees[0][description]" 
                                              rows="2"
                                              placeholder="Optional description..."
                                              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Registration Fee --}}
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <h4 class="font-semibold text-dark mb-3">Registration Fee</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <input type="hidden" name="fees[1][fee_type]" value="registration_fee">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Fee Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="fees[1][fee_name]" 
                                           value="WAEC Registration Fee"
                                           required
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Amount ({{ $currencySymbol }}) <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="fees[1][amount]" 
                                           step="0.01"
                                           min="0"
                                           required
                                           placeholder="0.00"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Status <span class="text-danger">*</span></label>
                                    <select name="fees[1][status]" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                                    <textarea name="fees[1][description]" 
                                              rows="2"
                                              placeholder="Optional description..."
                                              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" 
                                class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold transition-colors duration-200">
                            Save Configuration
                        </button>
                        <button type="button" 
                                onclick="loadExistingConfig()"
                                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-dark rounded-xl font-semibold transition-colors duration-200">
                            Load Existing Config
                        </button>
                    </div>
                </form>
            </div>

            {{-- Existing Configurations --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-dark mb-4">Existing Fee Configurations</h3>
                
                @if($feeConfigurations->isEmpty())
                <p class="text-gray-500 text-center py-8">No fee configurations found. Create your first configuration above.</p>
                @else
                <div class="space-y-6">
                    @foreach($feeConfigurations as $sessionId => $configs)
                    <div class="border border-gray-200 rounded-xl p-4">
                        <h4 class="font-bold text-dark mb-3">{{ $configs->first()->session->name }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($configs as $config)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-dark">{{ $config->fee_name }}</span>
                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $config->status === 'active' ? 'bg-success/10 text-success' : 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($config->status) }}
                                    </span>
                                </div>
                                <p class="text-2xl font-bold text-primary mb-2">{{ $currencySymbol }}{{ number_format($config->amount, 2) }}</p>
                                @if($config->description)
                                <p class="text-xs text-gray-600 mb-2">{{ $config->description }}</p>
                                @endif
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ ucfirst(str_replace('_', ' ', $config->fee_type)) }}</span>
                                    @if($config->updater)
                                    <span>By: {{ $config->updater->first_name }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
// Load existing configuration when session is selected
function loadExistingConfig() {
    const sessionId = document.getElementById('session-select').value;
    if (!sessionId) {
        alert('Please select a session first');
        return;
    }
    
    // Find existing configs for this session
    const configs = @json($feeConfigurations);
    if (configs[sessionId]) {
        const sessionConfigs = configs[sessionId];
        
        sessionConfigs.forEach((config, index) => {
            // Map fee types to form indices
            let formIndex = config.fee_type === 'examination_fee' ? 0 : 1;
            
            // Populate form fields
            document.querySelector(`[name="fees[${formIndex}][fee_name]"]`).value = config.fee_name;
            document.querySelector(`[name="fees[${formIndex}][amount]"]`).value = config.amount;
            document.querySelector(`[name="fees[${formIndex}][status]"]`).value = config.status;
            if (config.description) {
                document.querySelector(`[name="fees[${formIndex}][description]"]`).value = config.description;
            }
        });
        
        alert('Configuration loaded successfully!');
    } else {
        alert('No existing configuration found for this session');
    }
}
</script>
@endpush
@endsection
