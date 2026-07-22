{{-- ========================================
     SIDEBAR - Teacher Access
     ======================================== --}}

{{-- Mobile Hamburger Button --}}
<button onclick="toggleTeacherSidebar()" class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 rounded-lg bg-primary shadow-lg flex items-center justify-center text-white">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

{{-- Mobile Overlay --}}
<div id="teacherSidebarOverlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden" onclick="toggleTeacherSidebar()"></div>

<aside class="fixed top-0 left-0 bottom-0 w-64 sidebar-gradient z-40 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 overflow-y-auto" id="teacherSidebar">
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
                    <span class="block text-[9px] text-white/40 -mt-1 tracking-widest uppercase">Teacher</span>
                </div>
            </a>
            {{-- Close button for mobile --}}
            <button onclick="toggleTeacherSidebar()" class="lg:hidden w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 overflow-y-auto">
        {{-- DASHBOARD --}}
        <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mb-4 px-3">Dashboard</p>
        <ul class="space-y-1 mb-6">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        {{-- MY CLASSES --}}
        <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mb-4 px-3">Teaching</p>
        <ul class="space-y-1 mb-6">
            <li>
                <a href="{{ route('attendance.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('attendance.*') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Attendance</span>
                </a>
            </li>
            <li>
                <a href="{{ route('timetables.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('timetables.*') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>My Timetable</span>
                </a>
            </li>
        </ul>

        {{-- ASSESSMENTS --}}
        <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mb-4 px-3">Assessments</p>
        <ul class="space-y-1 mb-6">
            {{-- Assessments Dropdown --}}
            <li>
                <button onclick="toggleAssessmentSubmenu()" class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Assessments</span>
                    </div>
                    <svg id="assessmentChevron" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <ul id="assessmentSubmenu" class="hidden mt-1 ml-8 space-y-1">
                    <li>
                        <a href="{{ route('continuous-assessments.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('continuous-assessments.*') ? 'text-white bg-white/10' : 'text-white/50 hover:text-white hover:bg-white/5' }} transition-all duration-200">
                            Continuous Assessments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('assessment-questions.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('assessment-questions.*') ? 'text-white bg-white/10' : 'text-white/50 hover:text-white hover:bg-white/5' }} transition-all duration-200">
                            Assessment Questions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('assessment-options.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('assessment-options.*') ? 'text-white bg-white/10' : 'text-white/50 hover:text-white hover:bg-white/5' }} transition-all duration-200">
                            Question Options
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('assessment-answers.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ request()->routeIs('assessment-answers.*') ? 'text-white bg-white/10' : 'text-white/50 hover:text-white hover:bg-white/5' }} transition-all duration-200">
                            Assessment Answers
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Other Assessment Items --}}
            <li>
                <a href="{{ route('results.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('results.*') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Grade Results</span>
                </a>
            </li>
            <li>
                <a href="{{ route('report-cards.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('report-cards.*') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Report Cards</span>
                </a>
            </li>
        </ul>

        {{-- STUDENTS --}}
        <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold mb-4 px-3">Students</p>
        <ul class="space-y-1 mb-6">
            <li>
                <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('students.*') ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>My Students</span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- User Profile at Bottom --}}
    <div class="px-4 py-4 border-t border-white/10 flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-accent/20 flex items-center justify-center text-sm font-bold text-accent">
                {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                <p class="text-xs text-white/40 truncate">{{ Auth::user()->role->name ?? 'Teacher' }}</p>
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

{{-- Sidebar Toggle Script --}}
<script>
    function toggleTeacherSidebar() {
        const sidebar = document.getElementById('teacherSidebar');
        const overlay = document.getElementById('teacherSidebarOverlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    function toggleAssessmentSubmenu() {
        const submenu = document.getElementById('assessmentSubmenu');
        const chevron = document.getElementById('assessmentChevron');
        submenu.classList.toggle('hidden');
        chevron.classList.toggle('rotate-90');
    }
</script>
