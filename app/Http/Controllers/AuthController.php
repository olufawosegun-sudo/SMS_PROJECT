<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\Role;
use App\Models\AcademicSession;
use App\Models\AcademicTerm;
use App\Models\LoginSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Check if any users exist in the database
        if (User::count() > 0) {
            return redirect()->route('login')->with('info', 'School setup is already complete. Please login to continue.');
        }

        return view('auth.register');
    }

    /**
     * Handle registration request (Multi-step).
     */
    public function register(Request $request)
    {
        // Validate school and administrator data together
        $validated = $request->validate([
            // School Details
            'school_name' => ['required', 'string', 'max:255'],
            'school_code' => ['required', 'string', 'max:50', 'unique:schools,school_code'],
            'school_email' => ['nullable', 'email', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:50'],
            'school_country' => ['required', 'string', 'max:100'],
            'school_motto' => ['nullable', 'string', 'max:255'],

            // Admin Details
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            DB::beginTransaction();

            // 1. Create School
            $school = School::create([
                'uuid' => (string) Str::uuid(),
                'name' => $validated['school_name'],
                'school_code' => strtoupper($validated['school_code']),
                'email' => $validated['school_email'],
                'phone' => $validated['school_phone'],
                'country' => $validated['school_country'],
                'motto' => $validated['school_motto'],
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

            // 3. Create Admin User (Owner)
            $user = User::create([
                'uuid' => (string) Str::uuid(),
                'school_id' => $school->id,
                'role_id' => $roles['Owner']->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => 'active',
            ]);

            // 4. Create Default Academic Session & Term
            $session = AcademicSession::create([
                'school_id' => $school->id,
                'name' => date('Y') . '/' . (date('Y') + 1),
                'start_date' => date('Y') . '-09-01',
                'end_date' => (date('Y') + 1) . '-07-20',
                'is_current' => true,
            ]);

            AcademicTerm::create([
                'school_id' => $school->id,
                'session_id' => $session->id,
                'name' => 'First Term',
                'start_date' => date('Y') . '-09-01',
                'end_date' => date('Y') . '-12-15',
                'is_current' => true,
            ]);

            DB::commit();

            // Log User In
            Auth::login($user);

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

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while creating your school profile: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            // Mark login session as logged out
            LoginSession::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'logout_at' => now(),
                    'status' => 'inactive',
                ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    // Helper functions for user-agent parsing
    private function getDevice($ua) {
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) return 'Tablet';
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $ua)) return 'Mobile';
        return 'Desktop';
    }

    private function getBrowser($ua) {
        if (preg_match('/MSIE/i', $ua) && !preg_match('/Opera/i', $ua)) return 'Internet Explorer';
        if (preg_match('/Firefox/i', $ua)) return 'Firefox';
        if (preg_match('/Chrome/i', $ua)) return 'Chrome';
        if (preg_match('/Safari/i', $ua)) return 'Safari';
        if (preg_match('/Opera/i', $ua)) return 'Opera';
        return 'Unknown';
    }

    private function getOS($ua) {
        if (preg_match('/windows/i', $ua)) return 'Windows';
        if (preg_match('/macintosh|mac os x/i', $ua)) return 'Mac OS';
        if (preg_match('/linux/i', $ua)) return 'Linux';
        if (preg_match('/iphone|ipad|ipod/i', $ua)) return 'iOS';
        if (preg_match('/android/i', $ua)) return 'Android';
        return 'Unknown';
    }
}
