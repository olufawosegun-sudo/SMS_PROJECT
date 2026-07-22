{{-- ======== TOP NAVIGATION FOR TEACHER ======== --}}
<nav class="td-nav" id="tdNav">
    <div class="td-nav-inner">
        {{-- Brand --}}
        <a href="{{ route('dashboard') }}" class="td-brand" style="text-decoration: none;">
            <div class="td-brand-icon">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="td-brand-text">
                <h1>{{ Auth::user()->school->name ?? '' }}</h1>
                <p>Teacher Portal</p>
            </div>
        </a>

        {{-- Actions --}}
        <div class="td-nav-actions">
            {{-- Notification Bell --}}
            <button class="td-nav-btn td-nav-btn--notif" title="Notifications">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="td-notif-badge"></span>
            </button>

            {{-- Profile Group (Desktop) --}}
            <div class="td-profile-group">
                <div>
                    <div class="td-profile-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                    <div class="td-profile-role">{{ Auth::user()->role->name ?? 'Teacher' }}</div>
                </div>
                <div class="td-avatar">
                    {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                </div>
            </div>

            {{-- Logout (Desktop) --}}
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="td-nav-btn" title="Sign Out">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>

            {{-- Hamburger (Mobile) --}}
            <button class="td-hamburger" id="tdHamburger" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<div class="td-mobile-menu" id="tdMobileMenu">
    <div style="display:flex; align-items:center; gap:14px; padding:14px 16px; margin-bottom:12px;">
        <div class="td-avatar" style="width:48px;height:48px;border-radius:14px;font-size:1rem;">
            {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
        </div>
        <div>
            <div style="font-weight:700;color:#1e293b;">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
            <div style="font-size:0.78rem;color:#64748b;">{{ Auth::user()->role->name ?? 'Teacher' }}</div>
        </div>
    </div>
    <div class="td-mobile-menu-divider"></div>
    <a href="{{ route('dashboard') }}" class="td-mobile-menu-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
    </a>
    <a href="{{ route('attendance.index') }}" class="td-mobile-menu-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Mark Attendance
    </a>
    {{-- Collapsible Assessments Dropdown in Mobile Menu --}}
    <div class="td-mobile-menu-dropdown">
        <button class="td-mobile-menu-dropdown-toggle" onclick="toggleMobileDropdown(event)">
            <div style="display:flex; align-items:center; gap:14px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Assessments</span>
            </div>
            <svg class="td-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div class="td-mobile-menu-dropdown-content">
            <a href="{{ route('continuous-assessments.index') }}" class="td-mobile-menu-subitem">
                Continuous Assessments
            </a>
            <a href="{{ route('assessment-questions.index') }}" class="td-mobile-menu-subitem">
                Assessment Questions
            </a>
            <a href="{{ route('assessment-options.index') }}" class="td-mobile-menu-subitem">
                Question Options
            </a>
            <a href="{{ route('assessment-answers.index') }}" class="td-mobile-menu-subitem">
                Assessment Answers
            </a>
        </div>
    </div>
    <a href="{{ route('results.index') }}" class="td-mobile-menu-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        Grade Assessments
    </a>
    <a href="{{ route('timetables.index') }}" class="td-mobile-menu-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        View Timetable
    </a>
    <div class="td-mobile-menu-divider"></div>
    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="td-mobile-menu-item" style="width:100%;border:none;background:none;text-align:left;cursor:pointer;color:#ef4444;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#ef4444;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </button>
    </form>
</div>

@push('styles')
<style>
    :root {
        --td-slate: #1e293b;
    }
    
    .td-nav {
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(0,0,0,0.06);
        position: sticky;
        top: 0;
        z-index: 100;
        transition: box-shadow 0.3s ease;
    }

    .td-nav.scrolled {
        box-shadow: 0 4px 30px rgba(0,0,0,0.08);
    }

    .td-nav-inner {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.5rem;
        height: 68px;
    }

    .td-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .td-brand-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1B6B3E, #0F4D2A);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(27,107,62,0.3);
    }

    .td-brand-text h1 {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--td-slate);
        line-height: 1.2;
    }

    .td-brand-text p {
        font-size: 0.7rem;
        font-weight: 600;
        color: #1B6B3E;
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }

    .td-nav-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .td-nav-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        position: relative;
    }

    .td-nav-btn:hover {
        background: #f1f5f9;
        color: var(--td-slate);
    }

    .td-notif-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ef4444;
        border: 2px solid white;
        animation: td-pulse 2s infinite;
    }

    .td-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1B6B3E, #2D8F54);
        color: white;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(27,107,62,0.25);
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .td-avatar:hover {
        transform: scale(1.08);
    }

    .td-profile-group {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 4px 8px 4px 14px;
        border-radius: 14px;
        background: #f8faf9;
        border: 1px solid rgba(27,107,62,0.08);
        transition: all 0.2s ease;
    }

    .td-profile-group:hover {
        border-color: rgba(27,107,62,0.15);
        background: #f0fdf4;
    }

    .td-profile-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--td-slate);
        line-height: 1.3;
    }

    .td-profile-role {
        font-size: 0.7rem;
        color: #64748b;
    }

    .td-hamburger {
        display: none;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: none;
        background: transparent;
        cursor: pointer;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 0;
    }

    .td-hamburger span {
        display: block;
        width: 22px;
        height: 2.5px;
        border-radius: 2px;
        background: var(--td-slate);
        transition: all 0.3s ease;
    }

    .td-hamburger.active span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .td-hamburger.active span:nth-child(2) {
        opacity: 0;
    }

    .td-hamburger.active span:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -5px);
    }

    .td-mobile-menu {
        display: none;
        position: fixed;
        top: 68px;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        z-index: 99;
        padding: 1.5rem;
        overflow-y: auto;
        animation: td-slideDown 0.3s ease;
    }

    .td-mobile-menu.active {
        display: block;
    }

    .td-mobile-menu-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 14px;
        color: var(--td-slate);
        font-weight: 500;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 4px;
    }

    .td-mobile-menu-item:hover {
        background: #f0fdf4;
        color: #1B6B3E;
    }

    .td-mobile-menu-item svg {
        width: 22px;
        height: 22px;
        color: #64748b;
    }

    .td-mobile-menu-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 12px 0;
    }

    /* Collapsible Dropdown styles for Mobile menu */
    .td-mobile-menu-dropdown {
        margin-bottom: 4px;
    }

    .td-mobile-menu-dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 14px 16px;
        border-radius: 14px;
        color: var(--td-slate);
        font-weight: 500;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        background: transparent;
        cursor: pointer;
    }

    .td-mobile-menu-dropdown-toggle:hover {
        background: #f0fdf4;
        color: #1B6B3E;
    }

    .td-mobile-menu-dropdown-toggle svg:first-child {
        width: 22px;
        height: 22px;
        color: #64748b;
    }

    .td-dropdown-arrow {
        width: 16px;
        height: 16px;
        color: #64748b;
        transition: transform 0.3s ease;
    }

    .td-mobile-menu-dropdown-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        padding-left: 28px;
    }

    .td-mobile-menu-subitem {
        display: block;
        padding: 10px 16px;
        color: #64748b;
        font-size: 0.88rem;
        text-decoration: none;
        font-weight: 500;
        border-radius: 10px;
        transition: all 0.2s;
        margin-bottom: 2px;
    }

    .td-mobile-menu-subitem:hover {
        background: #f1f5f9;
        color: var(--td-slate);
    }

    @keyframes td-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.3); }
    }

    @keyframes td-slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .td-hamburger {
            display: flex;
        }

        .td-profile-group,
        .td-nav-actions .td-nav-btn:not(.td-nav-btn--notif) {
            display: none;
        }
    }

    /* Force layouts to adjust for teacher subpages (no sidebar, no margin) */
    aside#sidebar, aside {
        display: none !important;
    }
    #sidebarOverlay {
        display: none !important;
    }
    main {
        margin-left: 0 !important;
    }
</style>
@endpush

@push('scripts')
<script>
// Toggle mobile accordion dropdown
window.toggleMobileDropdown = function(event) {
    event.preventDefault();
    event.stopPropagation();
    const toggleBtn = event.currentTarget;
    const dropdown = toggleBtn.closest('.td-mobile-menu-dropdown');
    if (dropdown) {
        dropdown.classList.toggle('active');
        const content = dropdown.querySelector('.td-mobile-menu-dropdown-content');
        const arrow = dropdown.querySelector('.td-dropdown-arrow');
        if (content) {
            if (dropdown.classList.contains('active')) {
                content.style.maxHeight = content.scrollHeight + 'px';
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                content.style.maxHeight = '0';
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // ---- Nav scroll shadow ----
    const nav = document.getElementById('tdNav');
    if (nav) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 10) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    }

    // ---- Hamburger toggle ----
    const hamburger = document.getElementById('tdHamburger');
    const mobileMenu = document.getElementById('tdMobileMenu');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
        });
    }
});
</script>
@endpush
