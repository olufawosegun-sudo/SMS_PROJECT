@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
<style>
    /* ========================================
       Teacher Dashboard — Luxury Design System
       ======================================== */

    :root {
        /* Core Palette */
        --lx-primary: #1B6B3E;
        --lx-primary-soft: #0F4D2A;
        --lx-accent: #D4A843;
        --lx-accent-light: #E8C36E;
        --lx-accent-glow: rgba(212, 168, 67, 0.15);
        --lx-surface: #ffffff;
        --lx-surface-alt: #f8fafc;
        --lx-border: rgba(0, 0, 0, 0.06);
        --lx-border-hover: rgba(0, 0, 0, 0.1);
        --lx-text: #0f172a;
        --lx-text-secondary: #64748b;
        --lx-text-muted: #94a3b8;

        /* Functional Colors */
        --lx-blue: #3b82f6;
        --lx-emerald: #10b981;
        --lx-violet: #8b5cf6;
        --lx-amber: #f59e0b;
        --lx-rose: #f43f5e;

        /* Shadows */
        --lx-shadow-sm: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.02);
        --lx-shadow: 0 4px 20px rgba(0,0,0,0.06);
        --lx-shadow-lg: 0 12px 40px rgba(0,0,0,0.08);
        --lx-shadow-xl: 0 20px 60px rgba(0,0,0,0.1);
        --lx-shadow-gold: 0 8px 32px rgba(212, 168, 67, 0.15);

        /* Radius */
        --lx-radius: 16px;
        --lx-radius-lg: 24px;
        --lx-radius-sm: 10px;
    }

    /* ---- Global Reset ---- */
    .lx-dashboard * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .lx-dashboard {
        min-height: 100vh;
        background: #f1f5f9;
        position: relative;
    }

    /* Subtle background texture */
    .lx-dashboard::before {
        content: '';
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background:
            radial-gradient(ellipse 80% 60% at 50% -20%, rgba(27, 107, 62, 0.06), transparent),
            radial-gradient(ellipse 60% 50% at 100% 100%, rgba(212, 168, 67, 0.04), transparent);
        pointer-events: none;
        z-index: 0;
    }

    /* ---- Main Container ---- */
    .lx-main {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem 4rem;
        position: relative;
        z-index: 1;
    }

    /* ========================================
       HERO BANNER — Dark Luxury Green
       ======================================== */
    .lx-hero {
        background: linear-gradient(135deg, var(--lx-primary) 0%, var(--lx-primary-soft) 50%, var(--lx-primary) 100%);
        border-radius: 0 0 var(--lx-radius-lg) var(--lx-radius-lg);
        padding: 2.5rem 2.5rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        animation: lx-fadeIn 0.6s ease;
    }

    .lx-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, var(--lx-accent-glow) 0%, transparent 60%);
        pointer-events: none;
    }

    .lx-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--lx-accent), transparent);
    }

    /* Decorative grid pattern */
    .lx-hero-pattern {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
        background-size: 60px 60px;
        pointer-events: none;
    }

    .lx-hero-content {
        position: relative;
        z-index: 2;
    }

    .lx-hero-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .lx-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(212, 168, 67, 0.12);
        border: 1px solid rgba(212, 168, 67, 0.2);
        border-radius: 20px;
        padding: 5px 14px;
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--lx-accent-light);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .lx-hero-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--lx-accent);
        animation: lx-pulse 2s infinite;
    }

    .lx-hero-date {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.5);
        font-weight: 500;
    }

    .lx-hero-greeting {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.2;
        margin-bottom: 0.5rem;
    }

    .lx-hero-greeting span {
        background: linear-gradient(135deg, var(--lx-accent), var(--lx-accent-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .lx-hero-subtitle {
        font-size: 0.88rem;
        color: rgba(255,255,255,0.5);
        font-weight: 400;
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .lx-hero-subtitle-divider {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: rgba(255,255,255,0.25);
    }

    /* ========================================
       PASSWORD BANNER
       ======================================== */
    .lx-alert-banner {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 1px solid rgba(245,158,11,0.2);
        border-radius: var(--lx-radius);
        padding: 16px 20px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: var(--lx-shadow-sm);
        animation: lx-slideDown 0.5s ease;
    }

    .lx-alert-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--lx-radius-sm);
        background: rgba(245,158,11,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .lx-alert-content { flex: 1; }
    .lx-alert-content h4 { font-size: 0.85rem; font-weight: 700; color: #92400e; margin-bottom: 2px; }
    .lx-alert-content p { font-size: 0.8rem; color: #a16207; margin: 0; }

    .lx-alert-btn {
        padding: 8px 18px;
        border-radius: var(--lx-radius-sm);
        background: #f59e0b;
        color: white;
        font-size: 0.78rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .lx-alert-btn:hover { background: #d97706; transform: translateY(-1px); }

    .lx-alert-close {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #a16207;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .lx-alert-close:hover { background: rgba(0,0,0,0.05); }

    /* ========================================
       STAT CARDS — Luxury Glass Style
       ======================================== */
    .lx-stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 1.75rem;
    }

    .lx-stat {
        background: var(--lx-surface);
        border: 1px solid var(--lx-border);
        border-radius: var(--lx-radius);
        padding: 22px 20px;
        position: relative;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        animation: lx-fadeInUp 0.6s ease backwards;
    }

    .lx-stat:nth-child(1) { animation-delay: 0.05s; }
    .lx-stat:nth-child(2) { animation-delay: 0.1s; }
    .lx-stat:nth-child(3) { animation-delay: 0.15s; }
    .lx-stat:nth-child(4) { animation-delay: 0.2s; }

    .lx-stat:hover {
        transform: translateY(-4px);
        box-shadow: var(--lx-shadow-lg);
        border-color: var(--lx-border-hover);
    }

    .lx-stat::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 20px;
        right: 20px;
        height: 2px;
        border-radius: 2px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .lx-stat:hover::after { opacity: 1; }

    .lx-stat--blue::after { background: linear-gradient(90deg, var(--lx-blue), transparent); }
    .lx-stat--emerald::after { background: linear-gradient(90deg, var(--lx-emerald), transparent); }
    .lx-stat--violet::after { background: linear-gradient(90deg, var(--lx-violet), transparent); }
    .lx-stat--amber::after { background: linear-gradient(90deg, var(--lx-amber), transparent); }

    .lx-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .lx-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .lx-stat:hover .lx-stat-icon { transform: scale(1.08); }

    .lx-stat-icon svg { width: 22px; height: 22px; }

    .lx-stat--blue .lx-stat-icon { background: rgba(59,130,246,0.08); color: var(--lx-blue); }
    .lx-stat--emerald .lx-stat-icon { background: rgba(16,185,129,0.08); color: var(--lx-emerald); }
    .lx-stat--violet .lx-stat-icon { background: rgba(139,92,246,0.08); color: var(--lx-violet); }
    .lx-stat--amber .lx-stat-icon { background: rgba(245,158,11,0.08); color: var(--lx-amber); }

    .lx-stat-trend {
        font-size: 0.68rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        background: rgba(16,185,129,0.08);
        color: var(--lx-emerald);
    }

    .lx-stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--lx-text);
        line-height: 1;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
        animation: lx-countPop 0.5s cubic-bezier(0.4, 0, 0.2, 1) backwards;
    }

    .lx-stat:nth-child(1) .lx-stat-value { animation-delay: 0.2s; }
    .lx-stat:nth-child(2) .lx-stat-value { animation-delay: 0.3s; }
    .lx-stat:nth-child(3) .lx-stat-value { animation-delay: 0.4s; }
    .lx-stat:nth-child(4) .lx-stat-value { animation-delay: 0.5s; }

    .lx-stat-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--lx-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    /* ========================================
       CONTENT GRID
       ======================================== */
    .lx-grid {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 20px;
    }

    .lx-col { display: flex; flex-direction: column; gap: 20px; }

    /* ========================================
       LUXURY CARD COMPONENT
       ======================================== */
    .lx-card {
        background: var(--lx-surface);
        border: 1px solid var(--lx-border);
        border-radius: var(--lx-radius);
        overflow: hidden;
        transition: all 0.3s ease;
        animation: lx-fadeInUp 0.6s ease backwards;
    }

    .lx-card:hover {
        box-shadow: var(--lx-shadow);
        border-color: var(--lx-border-hover);
    }

    .lx-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--lx-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .lx-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--lx-text);
    }

    .lx-card-title-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lx-card-title-icon svg { width: 16px; height: 16px; }

    .lx-card-count {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        background: rgba(212, 168, 67, 0.08);
        color: var(--lx-accent);
        border: 1px solid rgba(212, 168, 67, 0.15);
    }

    .lx-card-body { padding: 18px 22px; }

    /* ========================================
       CLASS ITEMS — Refined Row
       ======================================== */
    .lx-class-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid transparent;
        margin-bottom: 8px;
        transition: all 0.25s ease;
        background: var(--lx-surface-alt);
    }

    .lx-class-item:last-child { margin-bottom: 0; }

    .lx-class-item:hover {
        background: #ffffff;
        border-color: var(--lx-border-hover);
        box-shadow: var(--lx-shadow-sm);
        transform: translateX(4px);
    }

    .lx-class-left { display: flex; align-items: center; gap: 14px; }

    .lx-class-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
    }

    .lx-class-icon--0 { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .lx-class-icon--1 { background: linear-gradient(135deg, #10b981, #059669); }
    .lx-class-icon--2 { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .lx-class-icon--3 { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .lx-class-icon--4 { background: linear-gradient(135deg, #f43f5e, #e11d48); }
    .lx-class-icon--5 { background: linear-gradient(135deg, #06b6d4, #0891b2); }

    .lx-class-name {
        font-size: 0.85rem;
        font-weight: 650;
        color: var(--lx-text);
    }

    .lx-class-meta {
        font-size: 0.75rem;
        color: var(--lx-text-secondary);
        margin-top: 2px;
    }

    .lx-class-btn {
        padding: 7px 16px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1.5px solid var(--lx-primary);
        background: transparent;
        color: var(--lx-primary);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .lx-class-btn:hover {
        background: var(--lx-primary);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15,23,42,0.2);
    }

    /* ========================================
       QUICK ACTIONS — Ultra Premium Glass Morphism
       ======================================== */
    .lx-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px;
        animation: lx-fadeInUp 0.6s ease backwards;
        animation-delay: 0.3s;
    }

    .lx-action {
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1.5px solid rgba(255,255,255,0.8);
        border-radius: 18px;
        padding: 24px 20px;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.08),
            0 2px 8px rgba(0, 0, 0, 0.04),
            inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }

    /* Gradient overlays for each action type */
    .lx-action::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        opacity: 0.05;
        transition: opacity 0.4s ease;
        pointer-events: none;
        z-index: 1;
    }

    .lx-action--blue::before { 
        background: linear-gradient(135deg, #3b82f6, #60a5fa);
    }
    .lx-action--emerald::before { 
        background: linear-gradient(135deg, #10b981, #34d399);
    }
    .lx-action--violet::before { 
        background: linear-gradient(135deg, #8b5cf6, #a78bfa);
    }
    .lx-action--amber::before { 
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
    }
    .lx-action--sky::before { 
        background: linear-gradient(135deg, #0ea5e9, #38bdf8);
    }
    .lx-action--rose::before { 
        background: linear-gradient(135deg, #f43f5e, #fb7185);
    }
    .lx-action--purple::before { 
        background: linear-gradient(135deg, #a855f7, #c084fc);
    }
    .lx-action--orange::before { 
        background: linear-gradient(135deg, #fb923c, #fdba74);
    }

    /* Animated gradient border effect */
    .lx-action::after {
        content: '';
        position: absolute;
        top: -2px; left: -2px; right: -2px; bottom: -2px;
        border-radius: 18px;
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 0;
        pointer-events: none;
    }

    .lx-action--blue::after { background: linear-gradient(135deg, #3b82f6, #60a5fa, #3b82f6); }
    .lx-action--emerald::after { background: linear-gradient(135deg, #10b981, #34d399, #10b981); }
    .lx-action--violet::after { background: linear-gradient(135deg, #8b5cf6, #a78bfa, #8b5cf6); }
    .lx-action--amber::after { background: linear-gradient(135deg, #f59e0b, #fbbf24, #f59e0b); }
    .lx-action--sky::after { background: linear-gradient(135deg, #0ea5e9, #38bdf8, #0ea5e9); }
    .lx-action--rose::after { background: linear-gradient(135deg, #f43f5e, #fb7185, #f43f5e); }
    .lx-action--purple::after { background: linear-gradient(135deg, #a855f7, #c084fc, #a855f7); }
    .lx-action--orange::after { background: linear-gradient(135deg, #fb923c, #fdba74, #fb923c); }

    .lx-action:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 
            0 20px 48px rgba(0, 0, 0, 0.12),
            0 8px 16px rgba(0, 0, 0, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 1);
        border-color: rgba(255,255,255,1);
    }

    .lx-action:hover::before { opacity: 0.12; }
    .lx-action:hover::after { opacity: 0.6; }

    .lx-action:active {
        transform: translateY(-2px) scale(0.98);
        transition: all 0.1s ease;
    }

    /* Icon container with gradient background */
    .lx-action-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        z-index: 2;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .lx-action-icon svg { 
        width: 24px; 
        height: 24px; 
        transition: all 0.3s ease;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    /* Vibrant gradient backgrounds for icons */
    .lx-action--blue .lx-action-icon { 
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: #ffffff;
    }
    .lx-action--emerald .lx-action-icon { 
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
    }
    .lx-action--violet .lx-action-icon { 
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: #ffffff;
    }
    .lx-action--amber .lx-action-icon { 
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #ffffff;
    }
    .lx-action--sky .lx-action-icon { 
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: #ffffff;
    }
    .lx-action--rose .lx-action-icon { 
        background: linear-gradient(135deg, #f43f5e, #e11d48);
        color: #ffffff;
    }
    .lx-action--purple .lx-action-icon { 
        background: linear-gradient(135deg, #a855f7, #9333ea);
        color: #ffffff;
    }
    .lx-action--orange .lx-action-icon { 
        background: linear-gradient(135deg, #fb923c, #f97316);
        color: #ffffff;
    }

    .lx-action:hover .lx-action-icon { 
        transform: scale(1.15) rotate(5deg);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    .lx-action:hover .lx-action-icon svg {
        transform: scale(1.1);
    }

    /* Typography with gradient text */
    .lx-action-label {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--lx-text);
        margin-bottom: 6px;
        position: relative;
        z-index: 2;
        line-height: 1.3;
        transition: all 0.3s ease;
    }

    .lx-action:hover .lx-action-label {
        transform: translateX(2px);
    }

    .lx-action-desc {
        font-size: 0.75rem;
        color: var(--lx-text-secondary);
        line-height: 1.4;
        position: relative;
        z-index: 2;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .lx-action:hover .lx-action-desc {
        color: var(--lx-text);
        transform: translateX(2px);
    }

    /* Animated arrow with gradient background */
    .lx-action-arrow {
        position: absolute;
        bottom: 16px;
        right: 16px;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 2;
        opacity: 0.5;
    }

    .lx-action-arrow svg { 
        width: 14px; 
        height: 14px; 
        transition: all 0.3s ease;
    }

    .lx-action--blue .lx-action-arrow { background: rgba(59,130,246,0.1); }
    .lx-action--blue .lx-action-arrow svg { color: #3b82f6; }
    
    .lx-action--emerald .lx-action-arrow { background: rgba(16,185,129,0.1); }
    .lx-action--emerald .lx-action-arrow svg { color: #10b981; }
    
    .lx-action--violet .lx-action-arrow { background: rgba(139,92,246,0.1); }
    .lx-action--violet .lx-action-arrow svg { color: #8b5cf6; }
    
    .lx-action--amber .lx-action-arrow { background: rgba(245,158,11,0.1); }
    .lx-action--amber .lx-action-arrow svg { color: #f59e0b; }
    
    .lx-action--sky .lx-action-arrow { background: rgba(14,165,233,0.1); }
    .lx-action--sky .lx-action-arrow svg { color: #0ea5e9; }
    
    .lx-action--rose .lx-action-arrow { background: rgba(244,63,94,0.1); }
    .lx-action--rose .lx-action-arrow svg { color: #f43f5e; }
    
    .lx-action--purple .lx-action-arrow { background: rgba(168,85,247,0.1); }
    .lx-action--purple .lx-action-arrow svg { color: #a855f7; }
    
    .lx-action--orange .lx-action-arrow { background: rgba(251,146,60,0.1); }
    .lx-action--orange .lx-action-arrow svg { color: #fb923c; }

    .lx-action:hover .lx-action-arrow {
        opacity: 1;
        transform: translateX(4px) scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .lx-action--blue:hover .lx-action-arrow { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .lx-action--blue:hover .lx-action-arrow svg { color: #ffffff; }
    
    .lx-action--emerald:hover .lx-action-arrow { background: linear-gradient(135deg, #10b981, #059669); }
    .lx-action--emerald:hover .lx-action-arrow svg { color: #ffffff; }
    
    .lx-action--violet:hover .lx-action-arrow { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .lx-action--violet:hover .lx-action-arrow svg { color: #ffffff; }
    
    .lx-action--amber:hover .lx-action-arrow { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .lx-action--amber:hover .lx-action-arrow svg { color: #ffffff; }
    
    .lx-action--sky:hover .lx-action-arrow { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
    .lx-action--sky:hover .lx-action-arrow svg { color: #ffffff; }
    
    .lx-action--rose:hover .lx-action-arrow { background: linear-gradient(135deg, #f43f5e, #e11d48); }
    .lx-action--rose:hover .lx-action-arrow svg { color: #ffffff; }
    
    .lx-action--purple:hover .lx-action-arrow { background: linear-gradient(135deg, #a855f7, #9333ea); }
    .lx-action--purple:hover .lx-action-arrow svg { color: #ffffff; }
    
    .lx-action--orange:hover .lx-action-arrow { background: linear-gradient(135deg, #fb923c, #f97316); }
    .lx-action--orange:hover .lx-action-arrow svg { color: #ffffff; }

    /* Stagger animation for grid items */
    .lx-action:nth-child(1) { animation: lx-fadeInUp 0.5s ease backwards; animation-delay: 0.05s; }
    .lx-action:nth-child(2) { animation: lx-fadeInUp 0.5s ease backwards; animation-delay: 0.1s; }
    .lx-action:nth-child(3) { animation: lx-fadeInUp 0.5s ease backwards; animation-delay: 0.15s; }
    .lx-action:nth-child(4) { animation: lx-fadeInUp 0.5s ease backwards; animation-delay: 0.2s; }
    .lx-action:nth-child(5) { animation: lx-fadeInUp 0.5s ease backwards; animation-delay: 0.25s; }
    .lx-action:nth-child(6) { animation: lx-fadeInUp 0.5s ease backwards; animation-delay: 0.3s; }
    .lx-action:nth-child(7) { animation: lx-fadeInUp 0.5s ease backwards; animation-delay: 0.35s; }
    .lx-action:nth-child(8) { animation: lx-fadeInUp 0.5s ease backwards; animation-delay: 0.4s; }

    /* ========================================
       DROPDOWN ASSESSMENT CARD
       ======================================== */
    .lx-action-dropdown {
        position: relative;
        cursor: default;
        overflow: visible !important;
    }

    .lx-action-main {
        cursor: pointer;
        display: flex;
        flex-direction: column;
        position: relative;
        z-index: 2;
    }

    .lx-action-dropdown.active .lx-action-main {
        padding-bottom: 12px;
    }

    .lx-action-dropdown-arrow {
        transition: all 0.3s ease;
    }

    .lx-action-dropdown.active .lx-action-dropdown-arrow {
        transform: rotate(180deg);
    }

    .lx-action-dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.98), rgba(255,255,255,0.95));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1.5px solid rgba(255,255,255,0.8);
        border-top: none;
        border-radius: 0 0 18px 18px;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 
            0 12px 32px rgba(0, 0, 0, 0.12),
            0 4px 8px rgba(0, 0, 0, 0.08);
        z-index: 1;
    }

    .lx-action-dropdown.active .lx-action-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .lx-action-dropdown.active {
        z-index: 10;
    }

    .lx-action-dropdown.active .lx-action-main {
        border-radius: 18px 18px 0 0;
    }

    .lx-action-dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        text-decoration: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .lx-action-dropdown-item:last-child {
        border-bottom: none;
    }

    .lx-action-dropdown-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(135deg, #10b981, #059669);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .lx-action-dropdown-item:hover::before {
        opacity: 1;
    }

    .lx-action-dropdown-item:hover {
        background: rgba(16, 185, 129, 0.04);
        padding-left: 20px;
    }

    .lx-dropdown-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .lx-dropdown-icon svg {
        width: 18px;
        height: 18px;
    }

    .lx-action-dropdown-item:hover .lx-dropdown-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .lx-dropdown-content {
        flex: 1;
    }

    .lx-dropdown-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--lx-text);
        line-height: 1.3;
        margin-bottom: 2px;
    }

    .lx-dropdown-desc {
        font-size: 0.7rem;
        color: var(--lx-text-secondary);
        line-height: 1.3;
    }

    .lx-action-dropdown-item:hover .lx-dropdown-label {
        color: var(--lx-emerald);
    }

    /* ========================================
       PROFILE CARD — Elevated Design
       ======================================== */
    .lx-profile-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 22px;
        background: linear-gradient(135deg, var(--lx-primary), var(--lx-primary-soft));
        position: relative;
        overflow: hidden;
    }

    .lx-profile-header::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 120px; height: 120px;
        border-radius: 50%;
        background: rgba(201, 168, 76, 0.08);
        pointer-events: none;
    }

    .lx-profile-avatar {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--lx-accent), var(--lx-accent-light));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 800;
        color: var(--lx-primary);
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }

    .lx-profile-info { position: relative; z-index: 2; }
    .lx-profile-info h4 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 2px;
    }
    .lx-profile-info p {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.5);
        margin: 0;
    }

    .lx-profile-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--lx-border);
    }

    .lx-profile-row:last-child { border-bottom: none; }

    .lx-profile-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.78rem;
        font-weight: 500;
        color: var(--lx-text-secondary);
    }

    .lx-profile-label svg { width: 15px; height: 15px; color: var(--lx-text-muted); }

    .lx-profile-value {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--lx-text);
        text-align: right;
    }

    .lx-email-warning {
        font-size: 0.68rem;
        color: var(--lx-rose);
        font-weight: 600;
    }

    /* ========================================
       DOCUMENT SECTION
       ======================================== */
    .lx-doc-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--lx-border);
    }

    .lx-doc-stat {
        padding: 14px;
        border-radius: 12px;
        background: var(--lx-surface-alt);
        border: 1px solid var(--lx-border);
    }

    .lx-doc-stat-icon {
        width: 30px; height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }

    .lx-doc-stat-icon svg { width: 14px; height: 14px; }

    .lx-doc-stat--blue .lx-doc-stat-icon { background: rgba(59,130,246,0.1); color: var(--lx-blue); }
    .lx-doc-stat--amber .lx-doc-stat-icon { background: rgba(245,158,11,0.1); color: var(--lx-amber); }

    .lx-doc-stat-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--lx-text);
        line-height: 1;
        margin-bottom: 2px;
    }

    .lx-doc-stat-label {
        font-size: 0.68rem;
        color: var(--lx-text-secondary);
        font-weight: 500;
    }

    /* Document item */
    .lx-doc-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--lx-border);
    }

    .lx-doc-item:last-child { border-bottom: none; }

    .lx-doc-left { display: flex; align-items: center; gap: 12px; }

    .lx-doc-avatar {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--lx-text-secondary);
    }

    .lx-doc-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--lx-text);
    }

    .lx-doc-type {
        font-size: 0.7rem;
        color: var(--lx-text-muted);
        margin-top: 1px;
    }

    .lx-doc-actions { display: flex; gap: 6px; }

    .lx-doc-action {
        width: 30px; height: 30px;
        border-radius: 8px;
        border: 1px solid var(--lx-border);
        background: var(--lx-surface);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--lx-text-secondary);
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .lx-doc-action svg { width: 14px; height: 14px; }

    .lx-doc-action:hover {
        background: var(--lx-primary);
        border-color: var(--lx-primary);
        color: white;
    }

    /* ========================================
       EMPTY STATE
       ======================================== */
    .lx-empty {
        text-align: center;
        padding: 3rem 1rem;
    }

    .lx-empty-icon {
        width: 64px; height: 64px;
        border-radius: 16px;
        background: var(--lx-surface-alt);
        border: 1px solid var(--lx-border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
    }

    .lx-empty-icon svg { width: 28px; height: 28px; color: var(--lx-text-muted); }

    .lx-empty h4 {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--lx-text-secondary);
        margin-bottom: 4px;
    }

    .lx-empty p {
        font-size: 0.78rem;
        color: var(--lx-text-muted);
        margin: 0;
    }

    /* ========================================
       ANIMATIONS
       ======================================== */
    @keyframes lx-fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes lx-fadeInUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes lx-slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes lx-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    @keyframes lx-countPop {
        from { opacity: 0; transform: scale(0.5); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes lx-wave {
        0% { transform: rotate(0deg); }
        10% { transform: rotate(14deg); }
        20% { transform: rotate(-8deg); }
        30% { transform: rotate(14deg); }
        40% { transform: rotate(-4deg); }
        50% { transform: rotate(10deg); }
        60% { transform: rotate(0deg); }
        100% { transform: rotate(0deg); }
    }

    /* ========================================
       RESPONSIVE
       ======================================== */
    @media (max-width: 1280px) {
        .lx-grid { grid-template-columns: 1fr 360px; }
    }

    @media (max-width: 1024px) {
        .lx-grid { grid-template-columns: 1fr; }
        .lx-stats-row { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .lx-main { padding: 0 1rem 3rem; }

        .lx-hero {
            padding: 2rem 1.5rem 1.5rem;
            border-radius: 0 0 var(--lx-radius) var(--lx-radius);
        }

        .lx-hero-top { flex-direction: column; align-items: flex-start; gap: 8px; }

        .lx-hero-greeting { font-size: 1.6rem; }

        .lx-hero-subtitle { font-size: 0.78rem; }

        .lx-stat { padding: 16px; }
        .lx-stat-value { font-size: 1.6rem; }
        .lx-stat-icon { width: 38px; height: 38px; }

        .lx-card-header { padding: 14px 16px; }
        .lx-card-body { padding: 14px 16px; }

        .lx-class-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .lx-class-btn { width: 100%; text-align: center; }

        .lx-alert-banner {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .lx-actions-grid { 
            grid-template-columns: repeat(2, 1fr);
            gap: 10px; 
        }
        
        .lx-action { 
            padding: 18px 14px;
        }
        
        .lx-action-icon {
            width: 44px;
            height: 44px;
            margin-bottom: 12px;
        }
        
        .lx-action-icon svg {
            width: 20px;
            height: 20px;
        }
        
        .lx-action-label { 
            font-size: 0.78rem;
            margin-bottom: 4px;
        }
        
        .lx-action-desc { 
            font-size: 0.68rem;
            line-height: 1.3;
        }
        
        .lx-action-arrow {
            width: 28px;
            height: 28px;
            bottom: 12px;
            right: 12px;
        }
        
        .lx-action-arrow svg {
            width: 12px;
            height: 12px;
        }

        /* Dropdown mobile styles */
        .lx-action-dropdown-menu {
            position: relative;
            top: 0;
            border: none;
            border-top: 1.5px solid rgba(0,0,0,0.05);
            border-radius: 0;
            box-shadow: none;
            max-height: 0;
            opacity: 1;
            visibility: visible;
            transform: none;
            transition: max-height 0.4s ease;
            background: rgba(0,0,0,0.01);
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }

        .lx-action-dropdown.active .lx-action-dropdown-menu {
            max-height: 400px;
        }

        .lx-action-dropdown-item {
            padding: 12px 14px;
            gap: 10px;
        }

        .lx-dropdown-icon {
            width: 32px;
            height: 32px;
        }

        .lx-dropdown-icon svg {
            width: 16px;
            height: 16px;
        }

        .lx-dropdown-label {
            font-size: 0.75rem;
        }

        .lx-dropdown-desc {
            font-size: 0.68rem;
        }

        .lx-action-dropdown.active .lx-action-main {
            padding-bottom: 10px;
        }
    }

    @media (max-width: 480px) {
        .lx-stats-row { grid-template-columns: 1fr 1fr; gap: 10px; }
        .lx-stat { padding: 14px; }
        .lx-stat-value { font-size: 1.4rem; }
        .lx-stat-icon { width: 34px; height: 34px; }
        .lx-stat-icon svg { width: 18px; height: 18px; }

        .lx-hero-greeting { font-size: 1.35rem; }

        .lx-actions-grid { 
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .lx-action {
            padding: 20px 16px;
        }
        
        .lx-action-icon {
            width: 48px;
            height: 48px;
        }
        
        .lx-action-icon svg {
            width: 22px;
            height: 22px;
        }
        
        .lx-action-label {
            font-size: 0.85rem;
        }
        
        .lx-action-desc {
            font-size: 0.72rem;
        }

        .lx-doc-stats { gap: 8px; }
        .lx-doc-stat { padding: 10px; }
        .lx-doc-stat-value { font-size: 1.2rem; }

        /* Dropdown mobile single column */
        .lx-action-dropdown-item {
            padding: 14px 16px;
        }

        .lx-dropdown-icon {
            width: 36px;
            height: 36px;
        }

        .lx-dropdown-icon svg {
            width: 18px;
            height: 18px;
        }

        .lx-dropdown-label {
            font-size: 0.8rem;
        }

        .lx-dropdown-desc {
            font-size: 0.7rem;
        }
    }
    
    /* Tablet landscape optimization */
    @media (min-width: 769px) and (max-width: 1024px) {
        .lx-actions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    /* Large desktop optimization */
    @media (min-width: 1400px) {
        .lx-actions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .lx-action {
            padding: 26px 22px;
        }
        
        .lx-action-icon {
            width: 56px;
            height: 56px;
        }
        
        .lx-action-icon svg {
            width: 26px;
            height: 26px;
        }

        /* Dropdown desktop styles */
        .lx-dropdown-icon {
            width: 40px;
            height: 40px;
        }

        .lx-dropdown-icon svg {
            width: 20px;
            height: 20px;
        }

        .lx-dropdown-label {
            font-size: 0.88rem;
        }

        .lx-dropdown-desc {
            font-size: 0.72rem;
        }

        .lx-action-dropdown-item {
            padding: 16px 20px;
        }
    }
    
    /* Ultra-wide optimization */
    @media (min-width: 1600px) {
        .lx-actions-grid {
            gap: 16px;
        }
    }
</style>
@endpush

@section('body')
<div class="flex min-h-screen bg-surface">
    @include('partials.teacher_sidebar')
    
    <main class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
        @include('partials.topbar')
        
        <div class="lx-dashboard">

    {{-- ======== HERO BANNER ======== --}}
    <div class="lx-hero">
        <div class="lx-hero-pattern"></div>
        <div class="lx-hero-content">
            <div class="lx-hero-top">
                <div class="lx-hero-badge">
                    Teacher Portal
                </div>
                <div class="lx-hero-date" id="lxDate"></div>
            </div>
            <div class="lx-hero-greeting">
                <span id="lxGreeting">Good morning</span>, {{ $user->first_name }}.
            </div>
            <div class="lx-hero-subtitle">
                @if($currentSession || $currentTerm)
                    <span>{{ $currentSession->name ?? '' }} @if($currentTerm) · {{ $currentTerm->name }} @endif</span>
                    <span class="lx-hero-subtitle-divider"></span>
                @endif
                <span>{{ $user->teacher->department->name ?? 'Department' }}</span>
                <span class="lx-hero-subtitle-divider"></span>
                <span>{{ $user->teacher->teacherSubjects->unique('class_id')->count() }} classes assigned</span>
            </div>
        </div>
    </div>

    {{-- ======== MAIN CONTENT ======== --}}
    <div class="lx-main">

        {{-- Password Alert --}}
        @if(Hash::check('password123', Auth::user()->password))
        <div class="lx-alert-banner" id="passwordReminder">
            <div class="lx-alert-icon">
                <svg width="20" height="20" fill="none" stroke="#f59e0b" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="lx-alert-content">
                <h4>🔐 Security Reminder</h4>
                <p>You're using the default password. Please change it for security.</p>
            </div>
            <a href="{{ route('password.request') }}" class="lx-alert-btn">Change Password</a>
            <button onclick="document.getElementById('passwordReminder').remove()" class="lx-alert-close">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        {{-- ======== STAT CARDS ======== --}}
        <div class="lx-stats-row">
            <div class="lx-stat lx-stat--blue">
                <div class="lx-stat-top">
                    <div class="lx-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <div class="lx-stat-value">{{ $allClasses->count() }}</div>
                <div class="lx-stat-label">My Classes</div>
            </div>

            <div class="lx-stat lx-stat--emerald">
                <div class="lx-stat-top">
                    <div class="lx-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div class="lx-stat-value">{{ $stats['total_students'] }}</div>
                <div class="lx-stat-label">My Students</div>
            </div>

            <div class="lx-stat lx-stat--violet">
                <div class="lx-stat-top">
                    <div class="lx-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
                <div class="lx-stat-value">{{ $teacherSubjects->unique('subject_id')->count() }}</div>
                <div class="lx-stat-label">Subjects</div>
            </div>

            <div class="lx-stat lx-stat--amber">
                <div class="lx-stat-top">
                    <div class="lx-stat-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="lx-stat-value">{{ $stats['attendance_rate'] ?? 0 }}%</div>
                <div class="lx-stat-label">Attendance Rate</div>
            </div>
        </div>

        {{-- ======== MAIN CONTENT GRID ======== --}}
        <div class="lx-grid">

            {{-- Left Column --}}
            <div class="lx-col">

                {{-- My Classes & Subjects --}}
                <div class="lx-card" style="animation-delay:0.1s">
                    <div class="lx-card-header">
                        <div class="lx-card-title">
                            <div class="lx-card-title-icon" style="background:rgba(59,130,246,0.08);color:#3b82f6;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            My Classes
                        </div>
                        @if($allClasses->count() > 0)
                            <div class="lx-card-count">{{ $allClasses->count() }} Classes</div>
                        @endif
                    </div>
                    <div class="lx-card-body">
                        @if($allClasses->count() > 0)
                            @foreach($allClasses as $index => $class)
                                @php
                                    $isMarked = isset($markedClassIds) && in_array($class->id, $markedClassIds);
                                @endphp
                                <div class="lx-class-item">
                                    <div class="lx-class-left">
                                        <div class="lx-class-icon lx-class-icon--{{ $index % 6 }}">
                                            {{ substr($class->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="lx-class-name" style="display: flex; align-items: center; flex-wrap: wrap; gap: 6px;">
                                                {{ $class->name }}
                                                @if($isMarked)
                                                    <span style="font-size: 0.65rem; background: rgba(16, 185, 129, 0.1); color: var(--lx-emerald); padding: 2px 8px; border-radius: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                                        <svg style="width: 10px; height: 10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                        Marked Today
                                                    </span>
                                                @else
                                                    <span style="font-size: 0.65rem; background: rgba(245, 158, 11, 0.1); color: var(--lx-amber); padding: 2px 8px; border-radius: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                                        Pending
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="lx-class-meta">{{ $class->students->count() }} students · {{ $class->arms->count() }} arm(s)</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('attendance.index', ['class_id' => $class->id]) }}" class="lx-class-btn">
                                        {{ $isMarked ? 'Update Attendance' : 'Mark Attendance' }}
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="lx-empty">
                                <div class="lx-empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <h4>No classes assigned yet</h4>
                                <p>Contact your administrator to get assigned to classes</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Today's Schedule --}}
                <div class="lx-card" style="animation-delay:0.15s">
                    <div class="lx-card-header">
                        <div class="lx-card-title">
                            <div class="lx-card-title-icon" style="background:rgba(245,158,11,0.08);color:#f59e0b;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            Today's Schedule
                        </div>
                        <div class="lx-card-count" id="lxDayBadge">Today</div>
                    </div>
                    <div class="lx-card-body">
                        <div class="lx-empty">
                            <div class="lx-empty-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h4>No classes scheduled for today</h4>
                            <p>Check your timetable for upcoming classes</p>
                        </div>
                    </div>
                </div>

                {{-- Student Documents --}}
                <div class="lx-card" style="animation-delay:0.2s">
                    <div class="lx-card-header">
                        <div class="lx-card-title">
                            <div class="lx-card-title-icon" style="background:rgba(139,92,246,0.08);color:#8b5cf6;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            Student Documents
                        </div>
                    </div>

                    <div class="lx-doc-stats">
                        <div class="lx-doc-stat lx-doc-stat--blue">
                            <div class="lx-doc-stat-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div class="lx-doc-stat-value">{{ $documentStats['total_students'] ?? 0 }}</div>
                            <div class="lx-doc-stat-label">Your Students</div>
                        </div>
                        <div class="lx-doc-stat lx-doc-stat--amber">
                            <div class="lx-doc-stat-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="lx-doc-stat-value">{{ $documentStats['missing_documents'] ?? 0 }}</div>
                            <div class="lx-doc-stat-label">Missing Birth Cert</div>
                        </div>
                    </div>

                    <div class="lx-card-body">
                        <div style="font-size:0.72rem;font-weight:600;color:var(--lx-text-muted);margin-bottom:14px;text-transform:uppercase;letter-spacing:0.6px;">Recent Documents</div>
                        @forelse($recentDocuments as $document)
                            <div class="lx-doc-item">
                                <div class="lx-doc-left">
                                    <div class="lx-doc-avatar">
                                        {{ substr($document->student->user->first_name ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="lx-doc-name">{{ $document->student->user->first_name ?? 'N/A' }} {{ $document->student->user->last_name ?? '' }}</div>
                                        <div class="lx-doc-type">{{ $document->getDocumentTypeLabel() }}</div>
                                    </div>
                                </div>
                                <div class="lx-doc-actions">
                                    <a href="{{ route('student-documents.view', $document->id) }}" class="lx-doc-action" title="View">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('student-documents.download', $document->id) }}" class="lx-doc-action" title="Download">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="lx-empty">
                                <div class="lx-empty-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h4>No documents yet</h4>
                                <p>Documents will appear when your students enroll</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="lx-col">

                {{-- Quick Actions --}}
                <div class="lx-card" style="animation-delay:0.12s">
                    <div class="lx-card-header">
                        <div class="lx-card-title">
                            <div class="lx-card-title-icon" style="background:rgba(201,168,76,0.1);color:#c9a84c;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            Quick Actions
                        </div>
                    </div>
                    <div class="lx-card-body">
                        <div class="lx-actions-grid">
                            <a href="{{ route('attendance.index') }}" class="lx-action lx-action--blue">
                                <div class="lx-action-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="lx-action-label">Attendance</div>
                                <div class="lx-action-desc">Mark daily presence</div>
                                <div class="lx-action-arrow">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </a>

                            {{-- Assessment Dropdown Card --}}
                            <div class="lx-action lx-action--emerald lx-action-dropdown">
                                <div class="lx-action-main" onclick="toggleAssessmentDropdown(event)">
                                    <div class="lx-action-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="lx-action-label">Assessments</div>
                                    <div class="lx-action-desc">Manage all assessments</div>
                                    <div class="lx-action-arrow lx-action-dropdown-arrow">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                <div class="lx-action-dropdown-menu">
                                    <a href="{{ route('assessments.index') }}" class="lx-action-dropdown-item">
                                        <div class="lx-dropdown-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="lx-dropdown-content">
                                            <div class="lx-dropdown-label">Continuous Assessments</div>
                                            <div class="lx-dropdown-desc">Create & manage CA tests</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('assessment-questions.index') }}" class="lx-action-dropdown-item">
                                        <div class="lx-dropdown-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div class="lx-dropdown-content">
                                            <div class="lx-dropdown-label">Assessment Questions</div>
                                            <div class="lx-dropdown-desc">Create test questions</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('assessment-options.index') }}" class="lx-action-dropdown-item">
                                        <div class="lx-dropdown-icon" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                        </div>
                                        <div class="lx-dropdown-content">
                                            <div class="lx-dropdown-label">Question Options</div>
                                            <div class="lx-dropdown-desc">Manage answer choices</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('assessment-answers.index') }}" class="lx-action-dropdown-item">
                                        <div class="lx-dropdown-icon" style="background: linear-gradient(135deg, #f43f5e, #e11d48);">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div class="lx-dropdown-content">
                                            <div class="lx-dropdown-label">Assessment Answers</div>
                                            <div class="lx-dropdown-desc">Review student responses</div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <a href="{{ route('results.index') }}" class="lx-action lx-action--purple">
                                <div class="lx-action-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                </div>
                                <div class="lx-action-label">Grading</div>
                                <div class="lx-action-desc">Record student results</div>
                                <div class="lx-action-arrow">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </a>

                            <a href="{{ route('report-cards.index') }}" class="lx-action lx-action--sky">
                                <div class="lx-action-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="lx-action-label">Report Cards</div>
                                <div class="lx-action-desc">Generate student reports</div>
                                <div class="lx-action-arrow">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </a>

                            <a href="{{ route('cbt-exams.index') }}" class="lx-action lx-action--rose">
                                <div class="lx-action-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="lx-action-label">CBT Exams</div>
                                <div class="lx-action-desc">Manage online tests</div>
                                <div class="lx-action-arrow">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </a>

                            <a href="{{ route('timetables.index') }}" class="lx-action lx-action--orange">
                                <div class="lx-action-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="lx-action-label">Timetable</div>
                                <div class="lx-action-desc">View your schedule</div>
                                <div class="lx-action-arrow">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Profile Information --}}
                <div class="lx-card" style="animation-delay:0.18s">
                    <div class="lx-profile-header">
                        <div class="lx-profile-avatar">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                        <div class="lx-profile-info">
                            <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
                            <p>{{ $user->role->name ?? 'Teacher' }} · {{ $user->teacher->department->name ?? 'No Department' }}</p>
                        </div>
                    </div>
                    <div class="lx-card-body">
                        <div class="lx-profile-row">
                            <span class="lx-profile-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                Staff No
                            </span>
                            <span class="lx-profile-value">{{ $user->teacher->staff_no ?? 'N/A' }}</span>
                        </div>
                        <div class="lx-profile-row">
                            <span class="lx-profile-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Department
                            </span>
                            <span class="lx-profile-value">{{ $user->teacher->department->name ?? 'Not Assigned' }}</span>
                        </div>
                        <div class="lx-profile-row">
                            <span class="lx-profile-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                Qualification
                            </span>
                            <span class="lx-profile-value">{{ $user->teacher->qualification ?? 'N/A' }}</span>
                        </div>
                        <div class="lx-profile-row">
                            <span class="lx-profile-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Joined
                            </span>
                            <span class="lx-profile-value">{{ $user->teacher->employment_date ? \Carbon\Carbon::parse($user->teacher->employment_date)->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="lx-profile-row">
                            <span class="lx-profile-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Email
                            </span>
                            <span class="lx-profile-value">
                                {{ $user->email }}
                                @if(!$user->email_verified_at)
                                    <br><span class="lx-email-warning">⚠️ Not verified</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    </main>
</div>


<script>
// Toggle Assessment Dropdown - MUST BE BEFORE DOMContentLoaded
window.toggleAssessmentDropdown = function(event) {
    event.preventDefault();
    event.stopPropagation();
    const dropdown = event.currentTarget.closest('.lx-action-dropdown');
    if (dropdown) {
        dropdown.classList.toggle('active');
        console.log('Dropdown toggled! Active:', dropdown.classList.contains('active'));
    } else {
        console.error('Dropdown element not found!');
    }
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('Teacher Dashboard JS Loaded!');
    
    // ---- Time-aware greeting ----
    const hour = new Date().getHours();
    let greeting = 'Good morning';
    if (hour >= 12 && hour < 17) greeting = 'Good afternoon';
    else if (hour >= 17) greeting = 'Good evening';
    const el = document.getElementById('lxGreeting');
    if (el) el.textContent = greeting;

    // ---- Current date ----
    const dateEl = document.getElementById('lxDate');
    if (dateEl) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateEl.textContent = new Date().toLocaleDateString('en-US', options);
    }

    // ---- Day badge ----
    const dayBadge = document.getElementById('lxDayBadge');
    if (dayBadge) {
        const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        dayBadge.textContent = days[new Date().getDay()];
    }

    // ---- Close dropdown when clicking outside ----
    document.addEventListener('click', function(e) {
        const dropdown = document.querySelector('.lx-action-dropdown');
        if (dropdown && !dropdown.contains(e.target)) {
            dropdown.classList.remove('active');
            console.log('Dropdown closed by outside click');
        }
    });

    // Verify dropdown exists
    const dropdownEl = document.querySelector('.lx-action-dropdown');
    console.log('Dropdown element found:', dropdownEl ? 'YES' : 'NO');
});
</script>
@endsection
