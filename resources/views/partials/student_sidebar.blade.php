{{-- ========================================
     SIDEBAR - Student Portal (Exact Owner/Principal Layout)
     ======================================== --}}

{{-- Mobile Overlay --}}
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden" onclick="toggleSidebar()"></div>

<aside class="fixed top-0 left-0 bottom-0 w-64 sidebar-gradient z-40 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 overflow-y-auto" id="sidebar">
    {{-- Logo & Brand --}}
    <div class="px-6 py-6 border-b border-white/10 flex-shrink-0">
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="text-left">
                    <span class="text-lg font-bold text-white">Edu<span class="text-accent">West</span></span>
                    <span class="block text-[9px] text-white/40 -mt-1 tracking-widest uppercase">Student Portal</span>
                </div>
            </a>
            {{-- Close button for mobile --}}
            <button onclick="toggleSidebar()" class="lg:hidden w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 overflow-y-auto" id="studentSidebarNav">

        {{-- DASHBOARD --}}
        <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mb-4 px-3">Dashboard</p>
        <ul class="space-y-1 mb-6">
            <li>
                @php $isDash = request()->routeIs('dashboard'); @endphp
                <a href="{{ route('dashboard') }}" class="sidebar-ripple-btn flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $isDash ? 'bg-primary text-white shadow-lg shadow-primary/30 font-bold is-active' : 'text-white/60 hover:text-white hover:bg-white/5' }}" @if($isDash) data-active="true" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        {{-- MY ACADEMICS --}}
        <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mb-4 px-3">My Academics</p>
        <ul class="space-y-1 mb-6">
            @foreach([
                ['label' => 'My Results', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'route' => 'results.index'],
                ['label' => 'Report Cards', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'route' => 'report-cards.index'],
                ['label' => 'CBT Exams', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'route' => 'cbt-exams.index'],
                ['label' => 'Class Timetable', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'route' => 'timetables.index'],
                ['label' => 'My Subjects', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'route' => 'subjects.index'],
            ] as $item)
            @php $isActive = request()->routeIs($item['route']); @endphp
            <li>
                <a href="{{ route($item['route']) }}" class="sidebar-ripple-btn flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $isActive ? 'bg-primary text-white shadow-lg shadow-primary/30 font-bold is-active' : 'text-white/60 hover:text-white hover:bg-white/5' }}" @if($isActive) data-active="true" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
            @endforeach
        </ul>

        {{-- ATTENDANCE --}}
        <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mb-4 px-3">Attendance</p>
        <ul class="space-y-1 mb-6">
            @php $isAttActive = request()->routeIs('attendance.*'); @endphp
            <li>
                <a href="{{ route('attendance.index') }}" class="sidebar-ripple-btn flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $isAttActive ? 'bg-primary text-white shadow-lg shadow-primary/30 font-bold is-active' : 'text-white/60 hover:text-white hover:bg-white/5' }}" @if($isAttActive) data-active="true" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span>My Attendance</span>
                </a>
            </li>
        </ul>

        {{-- COMMUNICATION --}}
        <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mb-4 px-3">Communication</p>
        <ul class="space-y-1 mb-6">
            @foreach([
                ['label' => 'Announcements', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'route' => 'announcements.index'],
                ['label' => 'Messages', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'route' => 'messages.index'],
            ] as $item)
            @php $isActive = request()->routeIs($item['route']); @endphp
            <li>
                <a href="{{ route($item['route']) }}" class="sidebar-ripple-btn flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $isActive ? 'bg-primary text-white shadow-lg shadow-primary/30 font-bold is-active' : 'text-white/60 hover:text-white hover:bg-white/5' }}" @if($isActive) data-active="true" @endif>
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
            @endforeach
        </ul>

        {{-- ACCOUNT --}}
        <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mb-4 px-3">Account</p>
        <ul class="space-y-1 mb-6">
            <li>
                <a href="{{ route('password.request') }}" class="sidebar-ripple-btn flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all duration-200">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span>Change Password</span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- User Profile & Logout at Bottom (Exact Match to Owner/Principal Footer) --}}
    <div class="px-4 py-4 border-t border-white/10 flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-accent/20 flex items-center justify-center text-sm font-bold text-accent flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->first_name ?? 'S', 0, 1) . substr(Auth::user()->last_name ?? 'T', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? (Auth::user()->first_name . ' ' . Auth::user()->last_name) }}</p>
                <p class="text-xs text-white/40 truncate">Student</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-red-500/20 flex items-center justify-center transition-colors group" title="Logout">
                    <svg class="w-4 h-4 text-white/40 group-hover:text-red-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Sidebar Toggle & Gmail State Layer Script --}}
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar && overlay) {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const nav = document.getElementById('studentSidebarNav');
        if (nav) {
            const savedScroll = sessionStorage.getItem('sms_student_sidebar_scroll');
            if (savedScroll !== null) nav.scrollTop = parseInt(savedScroll, 10);
        }

        const buttons = document.querySelectorAll('#sidebar .sidebar-ripple-btn');
        buttons.forEach(function (btn) {
            btn.addEventListener('mousedown', function () {
                btn.classList.add('is-pressed');
            });
            btn.addEventListener('mouseup', function () {
                btn.classList.remove('is-pressed');
            });
            btn.addEventListener('mouseleave', function () {
                btn.classList.remove('is-pressed');
            });

            btn.addEventListener('click', function () {
                if (nav) sessionStorage.setItem('sms_student_sidebar_scroll', nav.scrollTop);

                if (btn.tagName.toLowerCase() === 'a' && btn.getAttribute('href') && btn.getAttribute('href') !== '#') {
                    buttons.forEach(function (b) {
                        b.classList.remove('bg-primary', 'text-white', 'shadow-lg', 'shadow-primary/30', 'is-active', 'font-bold');
                        b.removeAttribute('data-active');
                        b.classList.add('text-white/60');
                    });
                    btn.classList.remove('text-white/60');
                    btn.classList.add('bg-primary', 'text-white', 'shadow-lg', 'shadow-primary/30', 'is-active', 'font-bold');
                    btn.setAttribute('data-active', 'true');
                }
            });
        });
    });
</script>
