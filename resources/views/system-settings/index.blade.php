@extends('layouts.app')
@section('title', 'System Settings')
@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.sidebar', ['role' => 'owner'])
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        <div class="p-4 md:p-6 lg:p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">System Configuration</h1>
                <p class="text-gray-500">Configure application properties, core environment stats, and configuration variables</p>
            </div>
            @if(session('success'))
            <div class="mb-6 p-4 bg-success/10 border border-success/20 rounded-xl">
                <p class="text-success font-semibold">{{ session('success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Configuration form --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 h-fit">
                    <h3 class="text-lg font-bold text-dark mb-4">Update Configuration</h3>
                    <form method="POST" action="{{ route('system-settings.update') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Default Currency Symbol</label>
                            <input type="text" name="currency" value="$" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">Automatic Backup Frequency</label>
                            <select name="backup_freq" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm">
                                <option value="daily">Daily</option>
                                <option value="weekly" selected>Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="maintenance_mode" id="maintenance_mode" class="rounded border-gray-300 text-primary">
                            <label for="maintenance_mode" class="text-sm font-medium text-gray-600">Enable Maintenance Mode</label>
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl hover:bg-primary-dark transition-colors font-semibold text-sm">Save Config</button>
                    </form>
                </div>

                {{-- Right: System diagnostics stats --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm h-fit">
                    <div class="px-6 py-4 border-b border-gray-100"><h3 class="text-lg font-bold text-dark">Diagnostics & Environment</h3></div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($systemInfo as $key => $val)
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-150">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $key) }}</p>
                                <p class="text-sm font-bold text-dark mt-1 font-mono truncate">{{ $val }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
