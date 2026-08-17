<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers & Job Openings — {{ $school->name }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: {{ $school->primary_color ?? '#1B6B3E' }};
            --brand-secondary: {{ $school->secondary_color ?? '#EAA315' }};
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Outfit', sans-serif; }
        .bg-brand-primary { background-color: var(--brand-primary); }
        .text-brand-primary { color: var(--brand-primary); }
        .border-brand-primary { border-color: var(--brand-primary); }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <a href="{{ route('school.website', $school->slug) }}" class="flex items-center gap-3">
                @if($school->logo)
                    <img src="{{ asset('storage/' . $school->logo) }}" alt="{{ $school->name }}" class="h-10 w-10 rounded-xl object-contain border border-slate-100 p-1">
                @else
                    <div class="h-10 w-10 rounded-xl bg-brand-primary text-white flex items-center justify-center font-bold text-lg">
                        {{ substr($school->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h1 class="font-heading font-bold text-slate-900 text-base leading-tight">{{ $school->name }}</h1>
                    <p class="text-xs text-slate-500">Careers & Employment Portal</p>
                </div>
            </a>

            <a href="{{ route('school.website', $school->slug) }}" class="text-xs font-semibold text-slate-600 hover:text-brand-primary transition-colors flex items-center gap-1">
                &larr; Back to School Website
            </a>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">

        <!-- Success Modal / Alert -->
        @if(session('success_career'))
            @php $c = session('success_career'); @endphp
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-emerald-200 shadow-xl mb-8 text-center space-y-4 animate-fade-in">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center text-3xl shadow-inner">
                    ✓
                </div>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-900">
                    Application Submitted Successfully!
                </h2>
                <p class="text-slate-600 text-sm max-w-md mx-auto">
                    Dear <strong>{{ $c['applicant_name'] }}</strong>, your application for the position of <strong>{{ $c['position'] }}</strong> at <strong>{{ $c['school_name'] }}</strong> has been received.
                </p>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Our Human Resources / Academic recruitment committee will review your profile and resume. If shortlisted, you will be contacted via <strong>{{ $c['email'] }}</strong>.
                </p>
                <div class="pt-4 flex justify-center gap-3">
                    <a href="{{ route('school.website', $school->slug) }}" class="px-6 py-2.5 rounded-xl bg-brand-primary text-white text-sm font-bold shadow-md hover:brightness-110">
                        Return to School Home
                    </a>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-sm">
                <p class="font-bold mb-1">Please correct the following errors:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
            <div class="border-b border-slate-100 pb-6 mb-8 text-center sm:text-left">
                <span class="text-xs font-bold text-brand-primary uppercase tracking-wider">Join Our Team</span>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-900 mt-1">
                    Staff & Faculty Employment Application
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Submit your credentials and CV to be considered for open teaching and administrative positions.
                </p>
            </div>

            <form action="{{ route('school.careers.submit', $school->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- 1. Personal Details -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-brand-primary text-white flex items-center justify-center font-bold text-xs">1</span>
                        <h3 class="font-heading font-bold text-slate-900 text-base">Personal Details</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">First Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="e.g. David">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Last Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="e.g. Adeleke">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="applicant@example.com">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Phone Number <span class="text-rose-500">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="+234 800 000 0000">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Gender</label>
                            <select name="gender" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none bg-white">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 2. Position & Qualifications -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-brand-primary text-white flex items-center justify-center font-bold text-xs">2</span>
                        <h3 class="font-heading font-bold text-slate-900 text-base">Position & Qualifications</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Position Applied For <span class="text-rose-500">*</span></label>
                            <input type="text" name="position_applied" value="{{ old('position_applied') }}" required list="popular_positions" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="e.g. Mathematics Teacher">
                            <datalist id="popular_positions">
                                <option value="Mathematics Teacher">
                                <option value="English Language & Literature Teacher">
                                <option value="Physics Teacher">
                                <option value="Chemistry Teacher">
                                <option value="Biology Teacher">
                                <option value="Economics Teacher">
                                <option value="Computer Studies / ICT Teacher">
                                <option value="Early Childhood / Nursery Teacher">
                                <option value="Primary Class Teacher">
                                <option value="School Accountant / Bursar">
                                <option value="Administrative Officer">
                                <option value="School Nurse">
                                <option value="Librarian">
                                <option value="School Driver">
                                <option value="Security Officer">
                            </datalist>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Highest Educational Qualification <span class="text-rose-500">*</span></label>
                            <select name="qualification" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none bg-white">
                                <option value="">Select Qualification</option>
                                <option value="B.Ed (Bachelor of Education)" {{ old('qualification') == 'B.Ed (Bachelor of Education)' ? 'selected' : '' }}>B.Ed (Bachelor of Education)</option>
                                <option value="B.Sc / B.A / B.Tech" {{ old('qualification') == 'B.Sc / B.A / B.Tech' ? 'selected' : '' }}>B.Sc / B.A / B.Tech</option>
                                <option value="NCE (National Certificate in Education)" {{ old('qualification') == 'NCE (National Certificate in Education)' ? 'selected' : '' }}>NCE (National Certificate in Education)</option>
                                <option value="HND (Higher National Diploma)" {{ old('qualification') == 'HND (Higher National Diploma)' ? 'selected' : '' }}>HND (Higher National Diploma)</option>
                                <option value="M.Ed / M.Sc / M.A" {{ old('qualification') == 'M.Ed / M.Sc / M.A' ? 'selected' : '' }}>M.Ed / M.Sc / M.A (Master's Degree)</option>
                                <option value="Ph.D / Doctorate" {{ old('qualification') == 'Ph.D / Doctorate' ? 'selected' : '' }}>Ph.D / Doctorate</option>
                                <option value="OND / Diploma" {{ old('qualification') == 'OND / Diploma' ? 'selected' : '' }}>OND / Diploma</option>
                                <option value="SSCE / WASSCE" {{ old('qualification') == 'SSCE / WASSCE' ? 'selected' : '' }}>SSCE / WASSCE</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Field / Specialization</label>
                            <input type="text" name="specialization" value="{{ old('specialization') }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="e.g. Pure Mathematics">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Years of Relevant Experience <span class="text-rose-500">*</span></label>
                            <input type="number" name="years_of_experience" value="{{ old('years_of_experience', 0) }}" min="0" max="50" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Previous Employer / School</label>
                            <input type="text" name="previous_employer" value="{{ old('previous_employer') }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="e.g. St. Gregory College">
                        </div>
                    </div>

                    @if($branches->count() > 0)
                        <div class="mt-4">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Preferred Campus Branch</label>
                            <select name="school_branch_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none bg-white">
                                <option value="">Main Campus / Any Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('school_branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }} - {{ $branch->city ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <!-- 3. Cover Letter & Resume Upload -->
                <div>
                    <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                        <span class="w-7 h-7 rounded-lg bg-brand-primary text-white flex items-center justify-center font-bold text-xs">3</span>
                        <h3 class="font-heading font-bold text-slate-900 text-base">Cover Letter & Resume</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Cover Letter / Personal Statement</label>
                        <textarea name="cover_letter" rows="4" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="Tell us why you are interested in joining our institution and what makes you a great fit...">{{ old('cover_letter') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Resume / Curriculum Vitae (CV) <span class="text-rose-500">*</span></label>
                            <p class="text-[11px] text-slate-400 mb-2">Upload in PDF, DOC, or DOCX format (Max 5MB)</p>
                            <input type="file" name="resume_cv" required accept=".pdf,.doc,.docx" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-primary file:text-white hover:file:brightness-110">
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Certificates / Credentials (Optional)</label>
                            <p class="text-[11px] text-slate-400 mb-2">Teaching licenses, degree certificates (Max 5MB)</p>
                            <input type="file" name="certificates" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:brightness-110">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
                    <p class="text-xs text-slate-400 text-center sm:text-left">
                        Your information is kept secure and treated in strict confidence.
                    </p>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-brand-primary text-white font-bold text-sm shadow-lg hover:brightness-110 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span>Submit Job Application</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
