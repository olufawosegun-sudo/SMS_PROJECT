@if(Auth::check() && Auth::user()->role && Auth::user()->role->name === 'Teacher')
    @include('partials.teacher_nav')
    @php return; @endphp
@endif
{{-- ======================================== TOP BAR (Responsive) ======================================== --}}
@php
    $topbarSessions = \App\Models\AcademicSession::where('school_id', Auth::user()->school_id)->orderBy('start_date', 'desc')->get();
    $topbarCurrentSession = $topbarSessions->where('is_current', true)->first();
    $topbarCurrentTerm = \App\Models\AcademicTerm::where('school_id', Auth::user()->school_id)->where('is_current', true)->first();
@endphp
<header class="sticky top-0 z-30 bg-white/80 backdrop-blur-lg border-b border-gray-100">
    <div class="flex items-center justify-between px-4 md:px-8 py-4">
        {{-- Mobile Hamburger + Search --}}
        <div class="flex items-center gap-3 flex-1">
            {{-- Hamburger Menu (Mobile Only) --}}
            <button onclick="toggleSidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors flex-shrink-0" aria-label="Toggle Sidebar">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Search --}}
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" placeholder="Search students, teachers, classes..."
                       class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        {{-- Right Actions --}}
        <div class="flex items-center gap-2 md:gap-4 ml-3 md:ml-6">

            {{-- Academic Session Selector --}}
            <div class="relative" id="sessionDropdownWrapper">
                <button type="button" id="sessionDropdownBtn"
                        class="hidden sm:flex items-center gap-2 px-3 py-2 bg-primary/5 border border-primary/15 rounded-xl hover:bg-primary/10 transition-all cursor-pointer"
                        onclick="toggleSessionDropdown()">
                    <svg class="w-4 h-4 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div class="text-left">
                        <p class="text-[10px] text-gray-400 font-semibold leading-none">Session</p>
                        <p class="text-xs font-bold text-primary leading-tight" id="topbarSessionName">
                            {{ $topbarCurrentSession->name ?? 'Not set' }}
                            @if($topbarCurrentTerm) · {{ $topbarCurrentTerm->name }} @endif
                        </p>
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" id="sessionChevronIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown --}}
                <div id="sessionDropdownMenu" class="hidden absolute right-0 top-full mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                        <p class="text-xs font-bold text-gray-600 uppercase tracking-wider">Switch Academic Session</p>
                    </div>
                    <div class="max-h-64 overflow-y-auto py-2">
                        @forelse($topbarSessions as $sess)
                        <button type="button"
                                class="session-switch-btn w-full text-left px-4 py-3 hover:bg-primary/5 transition-colors flex items-center justify-between group {{ $sess->is_current ? 'bg-primary/5' : '' }}"
                                data-session-id="{{ $sess->id }}"
                                data-session-name="{{ $sess->name }}"
                                onclick="setActiveSession({{ $sess->id }}, '{{ $sess->name }}')">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg {{ $sess->is_current ? 'bg-primary text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-primary/10 group-hover:text-primary' }} flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold {{ $sess->is_current ? 'text-primary' : 'text-dark' }}">{{ $sess->name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $sess->start_date?->format('M Y') }} — {{ $sess->end_date?->format('M Y') }}</p>
                                </div>
                            </div>
                            @if($sess->is_current)
                            <span class="px-2 py-0.5 text-[9px] font-bold rounded-full bg-primary/10 text-primary uppercase">Active</span>
                            @endif
                        </button>
                        @empty
                        <p class="px-4 py-6 text-center text-xs text-gray-400 italic">No sessions created yet. <a href="{{ route('sessions.index') }}" class="text-primary font-semibold hover:underline">Create one</a></p>
                        @endforelse
                    </div>
                    <div class="px-4 py-2 bg-gray-50 border-t border-gray-100">
                        <a href="{{ route('sessions.index') }}" class="text-xs text-primary font-semibold hover:underline flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Manage Sessions & Terms
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick Actions Dropdown --}}
            <div class="relative hidden sm:block">
                <button class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center hover:bg-primary/20 transition-colors" title="Quick Actions">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </button>
            </div>

            {{-- Notifications --}}
            <button class="relative w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if(isset($notifications) && count($notifications) > 0)
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-danger rounded-full text-[10px] font-bold text-white flex items-center justify-center">{{ count($notifications) }}</span>
                @endif
            </button>

            {{-- User Profile --}}
            <div class="flex items-center gap-3 pl-3 md:pl-4 border-l border-gray-200">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-sm font-bold text-primary">
                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1) . substr(Auth::user()->last_name, 0, 1)) }}
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-semibold text-dark">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ Auth::user()->role->name ?? 'Owner' }}</p>
                </div>
                <button class="hidden sm:flex w-8 h-8 rounded-lg hover:bg-gray-100 items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>

{{-- Session Dropdown JS --}}
<script>
function toggleSessionDropdown() {
    const menu = document.getElementById('sessionDropdownMenu');
    const chevron = document.getElementById('sessionChevronIcon');
    menu.classList.toggle('hidden');
    chevron.classList.toggle('rotate-180');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('sessionDropdownWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('sessionDropdownMenu')?.classList.add('hidden');
        document.getElementById('sessionChevronIcon')?.classList.remove('rotate-180');
    }
});

function setActiveSession(sessionId, sessionName) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) { window.location.href = '/sessions/' + sessionId + '/set-active'; return; }

    fetch('/sessions/' + sessionId + '/set-active', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update the topbar label
            const label = document.getElementById('topbarSessionName');
            if (label) {
                label.textContent = data.session + (data.term ? ' · ' + data.term : '');
            }
            // Update active states in dropdown
            document.querySelectorAll('.session-switch-btn').forEach(btn => {
                btn.classList.remove('bg-primary/5');
                const badge = btn.querySelector('span');
                if (badge) badge.remove();
                const icon = btn.querySelector('.w-8');
                if (icon) {
                    icon.classList.remove('bg-primary', 'text-white');
                    icon.classList.add('bg-gray-100', 'text-gray-500');
                }
                const nameEl = btn.querySelector('.text-sm');
                if (nameEl) {
                    nameEl.classList.remove('text-primary');
                    nameEl.classList.add('text-dark');
                }
            });
            // Highlight the selected one
            const activeBtn = document.querySelector(`.session-switch-btn[data-session-id="${sessionId}"]`);
            if (activeBtn) {
                activeBtn.classList.add('bg-primary/5');
                const icon = activeBtn.querySelector('.w-8');
                if (icon) {
                    icon.classList.add('bg-primary', 'text-white');
                    icon.classList.remove('bg-gray-100', 'text-gray-500');
                }
                const nameEl = activeBtn.querySelector('.text-sm');
                if (nameEl) {
                    nameEl.classList.add('text-primary');
                    nameEl.classList.remove('text-dark');
                }
                const badgeContainer = activeBtn.querySelector('.flex.items-center.justify-between');
                if (!activeBtn.querySelector('.bg-primary\\/10')) {
                    const badge = document.createElement('span');
                    badge.className = 'px-2 py-0.5 text-[9px] font-bold rounded-full bg-primary/10 text-primary uppercase';
                    badge.textContent = 'Active';
                    activeBtn.appendChild(badge);
                }
            }
            // Close dropdown
            toggleSessionDropdown();
        }
    })
    .catch(err => {
        console.error('Failed to switch session:', err);
        window.location.reload();
    });
}
</script>
