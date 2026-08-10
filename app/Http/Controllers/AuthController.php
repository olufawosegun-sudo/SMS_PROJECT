<?php

namespace App\Http\Controllers;

use App\Mail\GuardianWelcomeMail;
use App\Mail\PrincipalWelcomeMail;
use App\Mail\StudentWelcomeMail;
use App\Mail\TeacherWelcomeMail;
use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\Guardian;
use App\Models\LoginSession;
use App\Models\Role;
use App\Models\School;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Track login session
            LoginSession::create([
                'user_id' => $user->id,
                'session_id' => $request->session()->getId(),
                'device' => $request->header('User-Agent') ? $this->getDevice($request->header('User-Agent')) : 'Unknown',
                'browser' => $request->header('User-Agent') ? $this->getBrowser($request->header('User-Agent')) : 'Unknown',
                'operating_system' => $request->header('User-Agent') ? $this->getOS($request->header('User-Agent')) : 'Unknown',
                'ip_address' => $request->ip(),
                'login_at' => now(),
                'status' => 'active',
            ]);

            // Save last login time
            $user->update(['last_login' => now()]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form (Get Started).
     * Allows multiple schools to register independently.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle registration request (Multi-step).
     * Creates school with Owner, Principal, Teacher, Parent, and Student accounts.
     */
    public function register(Request $request)
    {
        // Validate school and all user accounts
        $validated = $request->validate([
            // School Details
            'school_name' => ['required', 'string', 'max:255'],
            'school_code' => ['required', 'string', 'max:50', 'unique:schools,school_code'],
            'school_email' => ['nullable', 'email', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:50'],
            'school_website' => ['nullable', 'url', 'max:255'],
            'school_address' => ['nullable', 'string', 'max:500'],
            'school_city' => ['nullable', 'string', 'max:100'],
            'school_state' => ['nullable', 'string', 'max:100'],
            'school_country' => ['required', 'string', 'max:100'],
            'school_motto' => ['nullable', 'string', 'max:255'],
            'school_logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'], // 2MB max

            // Owner Details
            'owner_first_name' => ['required', 'string', 'max:255'],
            'owner_last_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'owner_phone' => ['nullable', 'string', 'max:50'],
            'owner_gender' => ['nullable', 'in:male,female'],
            'owner_dob' => ['nullable', 'date', 'before:today'],
            'owner_profile_photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],
            'owner_password' => ['required', 'confirmed', Password::defaults()],

            // Principal Details (Optional)
            'principal_first_name' => ['nullable', 'string', 'max:255'],
            'principal_last_name' => ['nullable', 'string', 'max:255'],
            'principal_email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'principal_phone' => ['nullable', 'string', 'max:50'],

            // Teacher Details (Optional)
            'teacher_first_name' => ['nullable', 'string', 'max:255'],
            'teacher_last_name' => ['nullable', 'string', 'max:255'],
            'teacher_email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'teacher_qualification' => ['nullable', 'string', 'max:255'],

            // Parent/Guardian Details (Optional)
            'parent_first_name' => ['nullable', 'string', 'max:255'],
            'parent_last_name' => ['nullable', 'string', 'max:255'],
            'parent_email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'parent_phone' => ['nullable', 'string', 'max:50'],
            'parent_occupation' => ['nullable', 'string', 'max:255'],

            // Student Details (Optional)
            'student_first_name' => ['nullable', 'string', 'max:255'],
            'student_last_name' => ['nullable', 'string', 'max:255'],
            'student_email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'student_admission_no' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            DB::beginTransaction();

            // 1. Handle logo upload if provided
            $logoPath = null;
            if ($request->hasFile('school_logo')) {
                $logoPath = $request->file('school_logo')->store('school-logos', 'public');
            }

            // Handle owner profile photo upload if provided
            $ownerPhotoPath = null;
            if ($request->hasFile('owner_profile_photo')) {
                $ownerPhotoPath = $request->file('owner_profile_photo')->store('profile-photos', 'public');
            }

            // 2. Create School
            $school = School::create([
                'uuid' => (string) Str::uuid(),
                'name' => $validated['school_name'],
                'school_code' => strtoupper($validated['school_code']),
                'email' => $validated['school_email'],
                'phone' => $validated['school_phone'],
                'website' => $validated['school_website'] ?? null,
                'address' => $validated['school_address'] ?? null,
                'city' => $validated['school_city'] ?? null,
                'state' => $validated['school_state'] ?? null,
                'country' => $validated['school_country'],
                'motto' => $validated['school_motto'],
                'logo' => $logoPath,
            ]);

            // 2. Create Default Roles for the school
            $roleNames = [
                'Owner' => 'School Owner with full access rights',
                'Principal' => 'School Principal directing academic affairs',
                'Vice Principal' => 'Vice Principal assisting the principal',
                'Teacher' => 'Class and subject teacher',
                'Guardian' => 'Parent or legal guardian of students',
                'Student' => 'Student enrolled at the school',
                'Accountant' => 'Financial officer of the school',
                'Librarian' => 'Library administrator',
                'Hostel Master' => 'Hostel supervisor',
            ];

            $roles = [];
            foreach ($roleNames as $name => $desc) {
                $roles[$name] = Role::create([
                    'school_id' => $school->id,
                    'name' => $name,
                    'description' => $desc,
                ]);
            }

            // 3. Create Owner User (Main Administrator)
            $ownerUser = User::create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $school->id,
                'role_id' => $roles['Owner']->id,
                'first_name' => $validated['owner_first_name'],
                'last_name' => $validated['owner_last_name'],
                'email' => $validated['owner_email'],
                'phone' => $validated['owner_phone'] ?? null,
                'gender' => $validated['owner_gender'] ?? null,
                'dob' => $validated['owner_dob'] ?? null,
                'profile_photo' => $ownerPhotoPath,
                'password' => Hash::make($validated['owner_password']),
                'status' => 'active',
            ]);

            // 4. Create Principal User (if provided)
            if (! empty($validated['principal_first_name']) && ! empty($validated['principal_email'])) {
                $principalUser = User::create([
                    'uuid' => (string) Str::uuid(),
                    'school_id' => $school->id,
                    'role_id' => $roles['Principal']->id,
                    'first_name' => $validated['principal_first_name'],
                    'last_name' => $validated['principal_last_name'],
                    'email' => $validated['principal_email'],
                    'phone' => $validated['principal_phone'] ?? null,
                    'password' => Hash::make('password123'), // Default password
                    'status' => 'active',
                ]);

                // Create Staff profile for Principal
                $principalStaff = Staff::create([
                    'school_id' => $school->id,
                    'user_id' => $principalUser->id,
                    'staff_no' => 'PRI'.str_pad($principalUser->id, 5, '0', STR_PAD_LEFT),
                    'staff_type' => 'Principal',
                    'employment_date' => now(),
                    'contract_type' => 'permanent',
                    'status' => 'active',
                ]);

                // Send welcome email to principal
                try {
                    Mail::to($principalUser->email)->send(new PrincipalWelcomeMail($principalStaff, 'password123'));
                } catch (\Exception $mailException) {
                    // Log email error but don't fail registration
                    \Log::error('Failed to send principal welcome email: '.$mailException->getMessage());
                }
            }

            // 5. Create Teacher User (if provided)
            if (! empty($validated['teacher_first_name']) && ! empty($validated['teacher_email'])) {
                $teacherUser = User::create([
                    'uuid' => (string) Str::uuid(),
                    'school_id' => $school->id,
                    'role_id' => $roles['Teacher']->id,
                    'first_name' => $validated['teacher_first_name'],
                    'last_name' => $validated['teacher_last_name'],
                    'email' => $validated['teacher_email'],
                    'password' => Hash::make('password123'), // Default password
                    'status' => 'active',
                ]);

                // Create Staff profile for Teacher
                $teacherStaff = Staff::create([
                    'school_id' => $school->id,
                    'user_id' => $teacherUser->id,
                    'staff_no' => 'TCH'.str_pad($teacherUser->id, 5, '0', STR_PAD_LEFT),
                    'staff_type' => 'Teacher',
                    'qualification' => $validated['teacher_qualification'] ?? null,
                    'employment_date' => now(),
                    'contract_type' => 'permanent',
                    'status' => 'active',
                ]);

                // Send welcome email to teacher
                try {
                    Mail::to($teacherUser->email)->send(new TeacherWelcomeMail($teacherStaff, 'password123'));
                } catch (\Exception $mailException) {
                    // Log email error but don't fail registration
                    \Log::error('Failed to send teacher welcome email: '.$mailException->getMessage());
                }
            }

            // 6. Create Parent/Guardian User (if provided)
            $guardianUser = null;
            if (! empty($validated['parent_first_name']) && ! empty($validated['parent_email'])) {
                $guardianUser = User::create([
                    'uuid' => (string) Str::uuid(),
                    'school_id' => $school->id,
                    'role_id' => $roles['Guardian']->id,
                    'first_name' => $validated['parent_first_name'],
                    'last_name' => $validated['parent_last_name'],
                    'email' => $validated['parent_email'],
                    'phone' => $validated['parent_phone'] ?? null,
                    'password' => Hash::make('password123'), // Default password
                    'status' => 'active',
                ]);

                // Create Guardian profile
                $guardian = Guardian::create([
                    'school_id' => $school->id,
                    'user_id' => $guardianUser->id,
                    'occupation' => $validated['parent_occupation'] ?? null,
                    'relationship' => 'Parent',
                    'status' => 'active',
                ]);

                // Send welcome email to guardian
                try {
                    Mail::to($guardianUser->email)->send(new GuardianWelcomeMail($guardian, 'password123'));
                } catch (\Exception $mailException) {
                    // Log email error but don't fail registration
                    \Log::error('Failed to send guardian welcome email: '.$mailException->getMessage());
                }
            }

            // 7. Create Student User (if provided)
            if (! empty($validated['student_first_name']) && ! empty($validated['student_email'])) {
                $studentUser = User::create([
                    'uuid' => (string) Str::uuid(),
                    'school_id' => $school->id,
                    'role_id' => $roles['Student']->id,
                    'first_name' => $validated['student_first_name'],
                    'last_name' => $validated['student_last_name'],
                    'email' => $validated['student_email'],
                    'password' => Hash::make('password123'), // Default password
                    'status' => 'active',
                ]);

                // Create Student profile
                $student = Student::create([
                    'uuid' => (string) Str::uuid(),
                    'school_id' => $school->id,
                    'user_id' => $studentUser->id,
                    'admission_no' => $validated['student_admission_no'] ?? 'STU'.str_pad($school->id, 5, '0', STR_PAD_LEFT).'001',
                    'admission_date' => now(),
                    'status' => 'active',
                ]);

                // Send welcome email to student
                try {
                    Mail::to($studentUser->email)->send(new StudentWelcomeMail($student, 'password123'));
                } catch (\Exception $mailException) {
                    // Log email error but don't fail registration
                    \Log::error('Failed to send student welcome email: '.$mailException->getMessage());
                }
            }

            // 8. Create Default Academic Session & Term
            $session = AcademicSession::create([
                'school_id' => $school->id,
                'name' => date('Y').'/'.(date('Y') + 1),
                'start_date' => date('Y').'-09-01',
                'end_date' => (date('Y') + 1).'-07-20',
                'is_current' => true,
            ]);

            AcademicTerm::create([
                'school_id' => $school->id,
                'session_id' => $session->id,
                'name' => 'First Term',
                'start_date' => date('Y').'-09-01',
                'end_date' => date('Y').'-12-15',
                'is_current' => true,
            ]);

            DB::commit();

            // Log Owner User In
            Auth::login($ownerUser);

            // Track login session
            LoginSession::create([
                'user_id' => $ownerUser->id,
                'session_id' => $request->session()->getId(),
                'device' => $request->header('User-Agent') ? $this->getDevice($request->header('User-Agent')) : 'Unknown',
                'browser' => $request->header('User-Agent') ? $this->getBrowser($request->header('User-Agent')) : 'Unknown',
                'operating_system' => $request->header('User-Agent') ? $this->getOS($request->header('User-Agent')) : 'Unknown',
                'ip_address' => $request->ip(),
                'login_at' => now(),
                'status' => 'active',
            ]);

            return redirect()->route('dashboard')->with('success', 'School registered successfully! Additional accounts have been created with default password: password123');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'An error occurred while creating your school profile: '.$e->getMessage()])->withInput();
        }
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        \Log::info('Logout attempt', ['user_id' => $user?->id, 'email' => $user?->email]);

        if ($user) {
            // Mark login session as logged out
            LoginSession::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'logout_at' => now(),
                    'status' => 'inactive',
                ]);
            \Log::info('LoginSession updated', ['user_id' => $user->id]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Log::info('Logout successful, redirecting to landing page');

        return redirect()->route('landing');
    }

    // Helper functions for user-agent parsing
    private function getDevice($ua)
    {
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) {
            return 'Tablet';
        }
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $ua)) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    private function getBrowser($ua)
    {
        if (preg_match('/MSIE/i', $ua) && ! preg_match('/Opera/i', $ua)) {
            return 'Internet Explorer';
        }
        if (preg_match('/Firefox/i', $ua)) {
            return 'Firefox';
        }
        if (preg_match('/Chrome/i', $ua)) {
            return 'Chrome';
        }
        if (preg_match('/Safari/i', $ua)) {
            return 'Safari';
        }
        if (preg_match('/Opera/i', $ua)) {
            return 'Opera';
        }

        return 'Unknown';
    }

    private function getOS($ua)
    {
        if (preg_match('/windows/i', $ua)) {
            return 'Windows';
        }
        if (preg_match('/macintosh|mac os x/i', $ua)) {
            return 'Mac OS';
        }
        if (preg_match('/linux/i', $ua)) {
            return 'Linux';
        }
        if (preg_match('/iphone|ipad|ipod/i', $ua)) {
            return 'iOS';
        }
        if (preg_match('/android/i', $ua)) {
            return 'Android';
        }

        return 'Unknown';
    }
}
