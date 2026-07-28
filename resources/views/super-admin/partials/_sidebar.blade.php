{{-- Super Admin Professional Sidebar Partial --}}
{{-- Usage: @include('super-admin.partials._sidebar', ['activePage' => 'dashboard']) --}}

@php
    $navGroups = [
        [
            'title' => 'Overview & Institutions',
            'items' => [
                ['route' => 'super-admin.dashboard', 'key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'super-admin.schools', 'key' => 'schools', 'label' => 'Master Schools', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
            ]
        ],
        [
            'title' => 'Billing & Financials',
            'items' => [
                ['route' => 'super-admin.subscriptions', 'key' => 'subscriptions', 'label' => 'Subscriptions', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                ['route' => 'super-admin.payments', 'key' => 'payments', 'label' => 'Payments & Ledger', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
            ]
        ],
        [
            'title' => 'System & Auditing',
            'items' => [
                ['route' => 'super-admin.users', 'key' => 'users', 'label' => 'Platform Users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['route' => 'super-admin.activities', 'key' => 'activities', 'label' => 'Global Audit Feed', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                ['route' => 'super-admin.settings', 'key' => 'settings', 'label' => 'System Configuration', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ]
        ]
    ];
@endphp

{{-- Mobile Sidebar Overlay --}}
<div id="saOverlay" class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-30 lg:hidden hidden transition-opacity" onclick="toggleSASidebar()"></div>

{{-- Sidebar Container --}}
<aside class="fixed top-0 left-0 bottom-0 w-[280px] z-40 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 border-r border-slate-800/60 shadow-2xl" id="saSidebar"
       style="background: linear-gradient(180deg, #0b1320 0%, #080e18 100%);">

    {{-- Brand & Header Section --}}
    <div class="px-6 py-5 border-b border-slate-800/80 flex items-center justify-between">
        <div class="flex items-center gap-3.5">
            <div class="relative w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-950/50"
                 style="background: linear-gradient(135deg, #1B6B3E 0%, #0D4726 60%, #D4A843 100%);">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-emerald-400 border-2 border-slate-900 rounded-full animate-pulse"></span>
            </div>
            <div>
                <div class="flex items-center gap-1.5">
                    <h2 class="text-xs font-black tracking-widest text-white uppercase" style="letter-spacing: 0.1em;">EduWest</h2>
                    <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-amber-500/10 text-amber-400 border border-amber-500/20">HQ</span>
                </div>
                <p class="text-[10px] font-bold text-slate-400 tracking-wide mt-0.5">Super Admin Portal</p>
            </div>
        </div>
    </div>

    {{-- Navigation Links Grouped --}}
    <nav class="flex-1 px-4 py-6 overflow-y-auto space-y-6 scrollbar-thin scrollbar-thumb-slate-800">
        @foreach($navGroups as $group)
        <div>
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 mb-2.5 px-3">
                {{ $group['title'] }}
            </p>
            <ul class="space-y-1">
                @foreach($group['items'] as $item)
                @php $isActive = ($activePage ?? '') === $item['key']; @endphp
                <li>
                    <a href="{{ route($item['route']) }}"
                       class="group relative flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-200
                       {{ $isActive
                           ? 'bg-emerald-500/10 text-white border border-emerald-500/20 shadow-md shadow-emerald-950/20'
                           : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/40'
                       }}"
                    >
                        @if($isActive)
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-emerald-500 rounded-r-full"></span>
                        @endif

                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 flex-shrink-0 transition-colors {{ $isActive ? 'text-emerald-400' : 'text-slate-500 group-hover:text-slate-300' }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </div>

                        @if($isActive)
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </nav>

    {{-- Bottom User Account & Actions Card --}}
    <div class="p-4 border-t border-slate-800/80 bg-slate-950/50 space-y-3">
        {{-- Profile Box --}}
        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-900/80 border border-slate-800/60">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-emerald-600/20 border border-emerald-500/30 flex items-center justify-center text-xs font-black text-emerald-400 flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->first_name ?? 'S', 0, 1) . substr(Auth::user()->last_name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        {{-- Switch & Logout buttons --}}
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('dashboard') }}"
               class="flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-800/60 hover:bg-slate-800 text-slate-300 hover:text-white rounded-lg text-[10px] font-bold transition-all border border-slate-700/50"
               title="Switch to standard school dashboard">
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Portal View
            </a>
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-1.5 px-3 py-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-lg text-[10px] font-bold transition-all border border-rose-500/20">
                    <svg class="w-3 h-3 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>

<script>
function toggleSASidebar() {
    const sidebar = document.getElementById('saSidebar');
    const overlay = document.getElementById('saOverlay');
    if (sidebar && overlay) {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
}
</script>
