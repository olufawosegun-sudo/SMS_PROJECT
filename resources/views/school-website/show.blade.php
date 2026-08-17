<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $school->name }} — Official School Portal & Website</title>
    <meta name="description" content="{{ $school->about_text ?? $school->motto ?? 'Welcome to ' . $school->name . '. Providing excellence in education.' }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Vite Tailwind / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: {{ $school->primary_color ?? '#1B6B3E' }};
            --brand-secondary: {{ $school->secondary_color ?? '#EAA315' }};
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .bg-brand-primary { background-color: var(--brand-primary); }
        .text-brand-primary { color: var(--brand-primary); }
        .border-brand-primary { border-color: var(--brand-primary); }
        .bg-brand-secondary { background-color: var(--brand-secondary); }
        .text-brand-secondary { color: var(--brand-secondary); }
        .gradient-hero {
            background: linear-gradient(135deg, var(--brand-primary) 0%, #0c2d1b 100%);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-brand-primary selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-slate-900 text-white text-xs py-2 px-4 border-b border-white/10">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-4">
                @if($school->phone)
                    <span class="flex items-center gap-1.5 opacity-80 hover:opacity-100 transition-opacity">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $school->phone }}
                    </span>
                @endif
                @if($school->email)
                    <span class="flex items-center gap-1.5 opacity-80 hover:opacity-100 transition-opacity">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $school->email }}
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $school->admission_status === 'open' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $school->admission_status === 'open' ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400' }}"></span>
                    Admissions {{ ucfirst($school->admission_status ?? 'open') }}
                </span>
                <a href="{{ route('login') }}" class="text-amber-400 hover:text-amber-300 font-semibold transition-colors flex items-center gap-1">
                    Portal Login &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand / Logo -->
                <a href="{{ route('school.website', $school->slug) }}" class="flex items-center gap-3.5 group">
                    @if($school->logo)
                        <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="h-12 w-12 rounded-xl object-contain shadow-sm border border-slate-100 p-1 bg-white">
                    @else
                        <div class="h-12 w-12 rounded-xl bg-brand-primary text-white flex items-center justify-center font-bold text-xl shadow-sm">
                            {{ substr($school->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <span class="block font-heading font-extrabold text-lg sm:text-xl text-slate-900 leading-tight group-hover:text-brand-primary transition-colors">
                            {{ $school->name }}
                        </span>
                        @if($school->motto)
                            <span class="block text-xs text-slate-500 font-medium truncate max-w-xs sm:max-w-md">
                                {{ $school->motto }}
                            </span>
                        @endif
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="#about" class="hover:text-brand-primary transition-colors">About Us</a>
                    <a href="#academics" class="hover:text-brand-primary transition-colors">Academics</a>
                    @if($announcements->count() > 0)
                        <a href="#news" class="hover:text-brand-primary transition-colors">Announcements</a>
                    @endif
                    <a href="{{ route('school.careers', $school->slug) }}" class="hover:text-brand-primary transition-colors flex items-center gap-1">
                        <span>Careers</span>
                        <span class="px-1.5 py-0.5 text-[10px] bg-amber-100 text-amber-800 rounded font-bold">Hiring</span>
                    </a>
                    <a href="#contact" class="hover:text-brand-primary transition-colors">Contact</a>
                </nav>

                <!-- CTA Buttons -->
                <div class="hidden sm:flex items-center gap-3">
                    @if($school->admission_status === 'open')
                        <a href="{{ route('school.apply', $school->slug) }}" class="px-5 py-2.5 rounded-xl bg-brand-primary text-white text-sm font-bold shadow-md hover:brightness-110 active:scale-95 transition-all">
                            Apply for Admission
                        </a>
                    @endif
                    <a href="{{ route('login') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-semibold transition-all">
                        Portal Login
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative gradient-hero text-white py-20 lg:py-32 overflow-hidden">
        <!-- Abstract Background Ornaments -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-brand-secondary/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold text-amber-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span>Excellence &bull; Character &bull; Leadership</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-heading font-extrabold tracking-tight leading-tight">
                        {{ $school->portal_hero_title ?? 'Nurturing Future Leaders with Academic Distinction' }}
                    </h1>

                    <p class="text-base sm:text-lg text-slate-200 max-w-2xl mx-auto lg:mx-0 font-normal leading-relaxed">
                        {{ $school->portal_hero_subtitle ?? ($school->motto ? '"' . $school->motto . '" — ' : '') . 'Welcome to ' . $school->name . '. We provide world-class education, discipline, and holistic development to inspire every child to reach their highest potential.' }}
                    </p>

                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-4">
                        @if($school->admission_status === 'open')
                            <a href="{{ route('school.apply', $school->slug) }}" class="px-7 py-3.5 rounded-xl bg-amber-400 text-slate-950 font-bold text-base shadow-xl hover:bg-amber-300 active:scale-95 transition-all flex items-center gap-2">
                                <span>Enroll Your Child Online</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                        <a href="{{ route('school.careers', $school->slug) }}" class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 font-bold text-base text-white transition-all flex items-center gap-2">
                            <span>Join Our Teaching Staff</span>
                        </a>
                    </div>
                </div>

                <!-- Hero Card / Quick Highlights -->
                <div class="lg:col-span-5">
                    <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 sm:p-8 shadow-2xl text-white space-y-6">
                        <div class="flex items-center justify-between border-b border-white/15 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl bg-amber-400 text-slate-950 flex items-center justify-center font-bold text-xl shadow-md">
                                    🎓
                                </div>
                                <div>
                                    <h3 class="font-heading font-bold text-lg text-white">School Quick Info</h3>
                                    <p class="text-xs text-white/70">{{ $school->country ?? 'West Africa' }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-amber-300">
                                Code: {{ $school->school_code }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                                <span class="text-xs text-white/70 block mb-1">Academic Programs</span>
                                <span class="text-2xl font-bold font-heading text-amber-300">{{ $classes->count() }}</span>
                                <span class="text-[11px] text-white/60 block">Configured Classes</span>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10">
                                <span class="text-xs text-white/70 block mb-1">Campus Branches</span>
                                <span class="text-2xl font-bold font-heading text-amber-300">{{ max($branches->count(), 1) }}</span>
                                <span class="text-[11px] text-white/60 block">Location(s)</span>
                            </div>
                        </div>

                        <div class="space-y-2.5 text-xs text-white/80">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Online Admission & Result Checking Portal</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Modern Curriculum, STEM & Arts Focus</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Qualified and Passionate Educators</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('login') }}" class="w-full py-3 rounded-xl bg-white text-slate-900 font-bold text-sm shadow-md hover:bg-slate-100 transition-all flex items-center justify-center gap-2">
                                <span>Sign In to Student/Parent Portal</span>
                                <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Principal Welcome & About Us -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-5 space-y-6">
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-3xl bg-gradient-to-tr from-slate-900 to-slate-800 p-8 text-white flex flex-col justify-between shadow-xl relative overflow-hidden">
                            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-brand-primary/40 rounded-full blur-2xl"></div>
                            <div>
                                <span class="px-3 py-1 rounded-full bg-white/20 text-xs font-bold text-amber-300 inline-block mb-3">
                                    Principal's Welcome
                                </span>
                                <h3 class="text-2xl font-heading font-bold leading-snug">
                                    "We believe every student has the potential to excel."
                                </h3>
                            </div>
                            <div class="border-t border-white/20 pt-4 mt-6">
                                <p class="font-bold text-white text-base">{{ $school->welcome_title ?? 'School Leadership' }}</p>
                                <p class="text-xs text-white/70">{{ $school->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-6">
                    <span class="text-xs font-bold tracking-widest text-brand-primary uppercase">About Our Institution</span>
                    <h2 class="text-3xl sm:text-4xl font-heading font-extrabold text-slate-900 leading-tight">
                        Empowering Minds, Shaping Tomorrow
                    </h2>

                    <div class="prose prose-slate max-w-none text-slate-600 space-y-4 text-base leading-relaxed">
                        @if($school->about_text)
                            {!! nl2br(e($school->about_text)) !!}
                        @else
                            <p>
                                At <strong>{{ $school->name }}</strong>, our mission is to provide an enriching and supportive learning environment where students discover their talents, build strong character, and achieve academic excellence.
                            </p>
                            <p>
                                We combine rigorous academics with a vibrant array of extracurricular activities, sports, and leadership programs to prepare our pupils and students for global opportunities.
                            </p>
                        @endif
                        @if($school->welcome_message)
                            <div class="p-5 rounded-2xl bg-slate-50 border-l-4 border-brand-primary text-slate-700 italic text-sm mt-4">
                                {!! nl2br(e($school->welcome_message)) !!}
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                            <span class="text-2xl mb-1 block">🏆</span>
                            <h4 class="font-bold text-slate-900 text-sm">Academic Quality</h4>
                            <p class="text-xs text-slate-500 mt-1">Outstanding track record in national examinations.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                            <span class="text-2xl mb-1 block">💡</span>
                            <h4 class="font-bold text-slate-900 text-sm">Modern Learning</h4>
                            <p class="text-xs text-slate-500 mt-1">Digital tools, science labs & sports facilities.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                            <span class="text-2xl mb-1 block">🌱</span>
                            <h4 class="font-bold text-slate-900 text-sm">Character Building</h4>
                            <p class="text-xs text-slate-500 mt-1">Discipline, ethics, and civic responsibility.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Academic Programs / Classes Configured by this School -->
    <section id="academics" class="py-20 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold tracking-widest text-brand-primary uppercase">Academic Structure</span>
                <h2 class="text-3xl sm:text-4xl font-heading font-extrabold text-slate-900">
                    Classes & Educational Programs
                </h2>
                <p class="text-slate-600 text-base">
                    Discover our customized curriculum and classes tailored specifically for our students.
                </p>
            </div>

            @if($classes->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($classes as $class)
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all hover:border-brand-primary/50 group">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl bg-brand-primary/10 text-brand-primary flex items-center justify-center font-heading font-bold text-xl group-hover:bg-brand-primary group-hover:text-white transition-colors">
                                    {{ substr($class->name, 0, 2) }}
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                    {{ $class->level ?? 'Standard' }}
                                </span>
                            </div>

                            <h3 class="font-heading font-bold text-xl text-slate-900 mb-2">
                                {{ $class->name }}
                            </h3>

                            <p class="text-xs text-slate-500 mb-4 line-clamp-2">
                                {{ $class->description ?? 'Comprehensive academic training with structured term assessments and continuous development.' }}
                            </p>

                            @if($class->arms->count() > 0)
                                <div class="pt-4 border-t border-slate-100">
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Available Arms:</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($class->arms as $arm)
                                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                                                Arm {{ $arm->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-3xl border border-slate-200 p-8">
                    <p class="text-slate-500 text-base">Academic classes are currently being updated for this session.</p>
                </div>
            @endif

            <!-- Admissions CTA banner -->
            @if($school->admission_status === 'open')
                <div class="mt-16 bg-gradient-to-r from-brand-primary to-slate-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="space-y-2 text-center md:text-left">
                        <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider">
                            Now Enrolling
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-heading font-extrabold">
                            Secure Your Child's Admission Today
                        </h3>
                        <p class="text-slate-200 text-sm max-w-xl">
                            Fast and seamless online registration. Fill the form, upload documents, and receive your confirmation immediately.
                        </p>
                    </div>
                    <a href="{{ route('school.apply', $school->slug) }}" class="px-8 py-4 rounded-xl bg-amber-400 text-slate-950 font-bold text-base shadow-lg hover:bg-amber-300 active:scale-95 transition-all shrink-0">
                        Start Application &rarr;
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Announcements & Events -->
    @if($announcements->count() > 0 || $events->count() > 0)
        <section id="news" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                    <span class="text-xs font-bold tracking-widest text-brand-primary uppercase">Updates & Noticeboard</span>
                    <h2 class="text-3xl sm:text-4xl font-heading font-extrabold text-slate-900">
                        Latest School News & Announcements
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($announcements as $announcement)
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between text-xs text-slate-400 mb-3">
                                    <span class="px-2.5 py-0.5 rounded-full bg-brand-primary/10 text-brand-primary font-semibold capitalize">
                                        {{ $announcement->type ?? 'General' }}
                                    </span>
                                    <span>{{ $announcement->announced_at ? $announcement->announced_at->format('M d, Y') : $announcement->created_at->format('M d, Y') }}</span>
                                </div>
                                <h3 class="font-heading font-bold text-lg text-slate-900 mb-2">
                                    {{ $announcement->title }}
                                </h3>
                                <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                                    {{ $announcement->content }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Careers Banner -->
    <section class="py-16 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8 bg-white/5 rounded-3xl p-8 sm:p-12 border border-white/10">
                <div class="space-y-3 text-center lg:text-left">
                    <span class="px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 font-bold text-xs">
                        Careers at {{ $school->name }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-extrabold">
                        Join Our Passionate Team of Educators & Staff
                    </h2>
                    <p class="text-slate-300 text-sm max-w-2xl">
                        Are you a talented teacher, administrator, or educational specialist looking for a rewarding career? Submit your application and resume to join us.
                    </p>
                </div>
                <a href="{{ route('school.careers', $school->slug) }}" class="px-8 py-4 rounded-xl bg-white text-slate-950 font-bold text-base shadow-lg hover:bg-slate-100 active:scale-95 transition-all shrink-0">
                    View Careers & Apply &rarr;
                </a>
            </div>
        </div>
    </section>

    <!-- Contact & Location -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold tracking-widest text-brand-primary uppercase">Get in Touch</span>
                <h2 class="text-3xl sm:text-4xl font-heading font-extrabold text-slate-900">
                    Contact & Campus Location
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Address -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 text-center space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-brand-primary/10 text-brand-primary mx-auto flex items-center justify-center font-bold text-xl">
                        📍
                    </div>
                    <h4 class="font-heading font-bold text-slate-900 text-lg">Physical Address</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $school->address ?? 'Main Campus, ' . ($school->city ?? '') . ' ' . ($school->state ?? '') }}
                        <br>{{ $school->country ?? 'West Africa' }}
                    </p>
                </div>

                <!-- Phone -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 text-center space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-brand-primary/10 text-brand-primary mx-auto flex items-center justify-center font-bold text-xl">
                        📞
                    </div>
                    <h4 class="font-heading font-bold text-slate-900 text-lg">Phone & Enquiries</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $school->phone ?? 'Contact office during school hours' }}
                    </p>
                </div>

                <!-- Email & Portal -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 text-center space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-brand-primary/10 text-brand-primary mx-auto flex items-center justify-center font-bold text-xl">
                        ✉️
                    </div>
                    <h4 class="font-heading font-bold text-slate-900 text-lg">Email Address</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ $school->email ?? 'admin@' . ($school->slug ?? 'school') . '.edu' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 text-xs py-12 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    @if($school->logo)
                        <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="h-8 w-8 rounded-lg object-contain bg-white p-0.5">
                    @endif
                    <span class="font-heading font-bold text-white text-sm">{{ $school->name }}</span>
                </div>

                <div class="flex items-center gap-6">
                    <a href="{{ route('school.website', $school->slug) }}" class="hover:text-white transition-colors">Home</a>
                    <a href="{{ route('school.apply', $school->slug) }}" class="hover:text-white transition-colors">Admissions</a>
                    <a href="{{ route('school.careers', $school->slug) }}" class="hover:text-white transition-colors">Careers</a>
                    <a href="{{ route('login') }}" class="hover:text-white transition-colors">Portal Login</a>
                </div>

                <p class="text-slate-500">
                    &copy; {{ date('Y') }} {{ $school->name }}. Powered by EduWest Africa.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
