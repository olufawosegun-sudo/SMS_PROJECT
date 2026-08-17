<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Admission — {{ $school->name }}</title>
    
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
                    <p class="text-xs text-slate-500">Online Student Admission Portal</p>
                </div>
            </a>

            <a href="{{ route('school.website', $school->slug) }}" class="text-xs font-semibold text-slate-600 hover:text-brand-primary transition-colors flex items-center gap-1">
                &larr; Back to School Website
            </a>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">

        <!-- Success Modal / Alert -->
        @if(session('success_application'))
            @php $app = session('success_application'); @endphp
            <div class="bg-white rounded-3xl p-8 sm:p-10 border border-emerald-200 shadow-xl mb-8 text-center space-y-4 animate-fade-in">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center text-3xl shadow-inner">
                    ✓
                </div>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-900">
                    Application Submitted Successfully!
                </h2>
                <p class="text-slate-600 text-sm max-w-md mx-auto">
                    Thank you for applying to <strong>{{ $app['school_name'] ?? $school->name }}</strong> for <strong>{{ $app['student_name'] }}</strong>.
                </p>
                <div class="bg-slate-50 rounded-2xl p-4 max-w-sm mx-auto border border-slate-200">
                    <span class="text-xs text-slate-400 block font-semibold">APPLICATION NUMBER</span>
                    <span class="text-xl font-mono font-bold text-brand-primary">{{ $app['application_no'] }}</span>
                </div>
                <p class="text-xs text-slate-500">
                    A confirmation email has been dispatched to <strong>{{ $app['guardian_email'] }}</strong>. Our admissions team will review the application and contact you soon.
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
                <span class="text-xs font-bold text-brand-primary uppercase tracking-wider">Session {{ date('Y') }}/{{ date('Y')+1 }} Admission</span>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-slate-900 mt-1">
                    Student Enrollment & Registration Form
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Please provide accurate information for the student and parent/guardian.
                </p>
            </div>

            @if($school->admission_status === 'closed')
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                    <span class="text-3xl block">🔒</span>
                    <h3 class="font-heading font-bold text-lg text-slate-900">Admissions Are Currently Closed</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">
                        Online admissions for {{ $school->name }} are currently paused. Please contact the school admissions office directly for enquiries.
                    </p>
                </div>
            @else
                <form action="{{ route('school.apply.submit', $school->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- 1. Student Information -->
                    <div>
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-brand-primary text-white flex items-center justify-center font-bold text-xs">1</span>
                            <h3 class="font-heading font-bold text-slate-900 text-base">Student Information</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">First Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="e.g. Samuel">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Last Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="e.g. Okafor">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Other Name</label>
                                <input type="text" name="other_name" value="{{ old('other_name') }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="e.g. Chukwu">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Gender <span class="text-rose-500">*</span></label>
                                <select name="gender" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none bg-white">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Date of Birth <span class="text-rose-500">*</span></label>
                                <input type="date" name="dob" value="{{ old('dob') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Class Applying For <span class="text-rose-500">*</span></label>
                                <select name="applied_class_id" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none bg-white">
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('applied_class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }} ({{ $class->level ?? 'Standard' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if($branches->count() > 0)
                            <div class="mt-4">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Preferred Campus / Branch</label>
                                <select name="school_branch_id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none bg-white">
                                    <option value="">Main Campus</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('school_branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }} - {{ $branch->city ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mt-4">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Previous School Attended</label>
                            <input type="text" name="previous_school" value="{{ old('previous_school') }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="Name and city of previous school">
                        </div>
                    </div>

                    <!-- 2. Parent / Guardian Information -->
                    <div>
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-brand-primary text-white flex items-center justify-center font-bold text-xs">2</span>
                            <h3 class="font-heading font-bold text-slate-900 text-base">Parent / Guardian Information</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Parent / Guardian Full Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="Mr. / Mrs. John Doe">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                                <input type="email" name="guardian_email" value="{{ old('guardian_email') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="parent@example.com">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Phone Number <span class="text-rose-500">*</span></label>
                                <input type="tel" name="guardian_phone" value="{{ old('guardian_phone') }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="+234 800 000 0000">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Residential Address <span class="text-rose-500">*</span></label>
                            <textarea name="address" rows="2" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-primary focus:outline-none" placeholder="Street address, city, state">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <!-- 3. Document Uploads -->
                    <div>
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                            <span class="w-7 h-7 rounded-lg bg-brand-primary text-white flex items-center justify-center font-bold text-xs">3</span>
                            <h3 class="font-heading font-bold text-slate-900 text-base">Required Documents</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Passport Photograph <span class="text-rose-500">*</span></label>
                                <p class="text-[11px] text-slate-400 mb-2">Clear portrait photo of the child (JPG/PNG max 2MB)</p>
                                <input type="file" name="passport_photo" required accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-primary file:text-white hover:file:brightness-110">
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Birth Certificate <span class="text-rose-500">*</span></label>
                                <p class="text-[11px] text-slate-400 mb-2">Official birth certificate or declaration of age (PDF/JPG max 3MB)</p>
                                <input type="file" name="birth_certificate" required accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-primary file:text-white hover:file:brightness-110">
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Previous School Report Card (Optional)</label>
                                <p class="text-[11px] text-slate-400 mb-2">Most recent term result / report card</p>
                                <input type="file" name="school_report" accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:brightness-110">
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Medical Fitness Certificate (Optional)</label>
                                <p class="text-[11px] text-slate-400 mb-2">Medical report / immunization card</p>
                                <input type="file" name="medical_certificate" accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:brightness-110">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
                        <p class="text-xs text-slate-400 text-center sm:text-left">
                            By submitting this application, you certify that the provided information is true and accurate.
                        </p>
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-brand-primary text-white font-bold text-sm shadow-lg hover:brightness-110 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <span>Submit Admission Application</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

</body>
</html>
