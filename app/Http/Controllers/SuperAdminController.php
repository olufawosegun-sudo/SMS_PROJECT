<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolBranch;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\AdmissionApplication;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    /**
     * Ensure only Super Admin accesses these actions.
     */
    protected function authorizeSuperAdmin()
    {
        $user = Auth::user();
        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Unauthorized access. Only Master Super Admin can access this portal.');
        }
    }

    /**
     * Super Admin Master Dashboard — Command Center
     */
    public function dashboard()
    {
        $this->authorizeSuperAdmin();

        // Core platform stats
        $stats = [
            'total_schools'      => School::count(),
            'active_schools'     => School::whereNull('deleted_at')->count(),
            'total_branches'     => SchoolBranch::count(),
            'total_students'     => Student::count(),
            'total_staff'        => Staff::count(),
            'total_users'        => User::count(),
            'total_applications' => AdmissionApplication::count(),
        ];

        // Subscription stats
        $stats['active_subscriptions']   = Subscription::where('status', 'active')->count();
        $stats['expired_subscriptions']  = Subscription::where('status', 'expired')->count();
        $stats['expiring_soon']          = Subscription::where('status', 'active')
            ->where('ends_at', '<=', now()->addDays(14))
            ->where('ends_at', '>', now())
            ->count();

        // Revenue stats
        $stats['total_school_revenue']       = Payment::whereIn('status', ['successful', 'paid'])->sum('amount');
        $stats['total_subscription_revenue'] = SubscriptionPayment::where('status', 'paid')->sum('amount');
        $stats['total_revenue']              = $stats['total_school_revenue'] + $stats['total_subscription_revenue'];
        $stats['this_month_revenue']         = Payment::whereIn('status', ['successful', 'paid'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount')
            + SubscriptionPayment::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Monthly revenue trend (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $schoolRev = Payment::whereIn('status', ['successful', 'paid'])
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            $subRev = SubscriptionPayment::where('status', 'paid')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            $monthlyRevenue[] = [
                'month'        => $date->format('M Y'),
                'month_short'  => $date->format('M'),
                'school_fees'  => (float) $schoolRev,
                'subscriptions'=> (float) $subRev,
                'total'        => (float) ($schoolRev + $subRev),
            ];
        }

        // Subscription plan distribution
        $planDistribution = Subscription::where('status', 'active')
            ->select('plan', DB::raw('count(*) as count'))
            ->groupBy('plan')
            ->pluck('count', 'plan')
            ->toArray();

        // Recent schools
        $recentSchools = School::withCount(['users', 'students', 'staff'])
            ->with('activeSubscription')
            ->latest()
            ->take(5)
            ->get();

        // Recent payments (mixed: subscription + school fees)
        $recentPayments = Payment::with(['student', 'schoolBranch'])
            ->whereIn('status', ['successful', 'paid'])
            ->latest()
            ->take(8)
            ->get();

        $recentSubPayments = SubscriptionPayment::with(['subscription.school'])
            ->where('status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        // Recent activity
        $recentActivities = AuditLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Expiring subscriptions
        $expiringSubscriptions = Subscription::with('school')
            ->where('status', 'active')
            ->where('ends_at', '<=', now()->addDays(14))
            ->where('ends_at', '>', now())
            ->orderBy('ends_at')
            ->take(5)
            ->get();

        return view('super-admin.dashboard', compact(
            'stats', 'monthlyRevenue', 'planDistribution',
            'recentSchools', 'recentPayments', 'recentSubPayments',
            'recentActivities', 'expiringSubscriptions'
        ));
    }

    /**
     * Master School Directory & Management
     */
    public function schools(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = School::withCount(['users', 'students', 'staff', 'classes'])
            ->with('activeSubscription');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('subscription_status')) {
            $subStatus = $request->subscription_status;
            $query->whereHas('activeSubscription', function ($q) use ($subStatus) {
                $q->where('status', $subStatus);
            });
        }

        $schools = $query->latest()->paginate(15)->withQueryString();

        return view('super-admin.schools', compact('schools'));
    }

    /**
     * Toggle School Status (Activate / Suspend)
     */
    public function toggleSchoolStatus($id)
    {
        $this->authorizeSuperAdmin();

        $school = School::findOrFail($id);
        
        // Toggle user accounts status for this school
        $newStatus = ($school->trashed() || User::where('school_id', $school->id)->where('status', 'inactive')->exists()) ? 'active' : 'inactive';
        
        User::where('school_id', $school->id)->update(['status' => $newStatus]);

        $msg = ($newStatus === 'active') ? "School '{$school->name}' activated successfully." : "School '{$school->name}' suspended successfully.";

        return back()->with('success', $msg);
    }

    /**
     * Subscriptions Management
     */
    public function subscriptions(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = Subscription::with('school');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }
        if ($request->filled('search')) {
            $query->whereHas('school', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        $subscriptions = $query->latest()->paginate(15)->withQueryString();

        $summaryStats = [
            'active'  => Subscription::where('status', 'active')->count(),
            'expired' => Subscription::where('status', 'expired')->count(),
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
            'revenue_this_month' => SubscriptionPayment::where('status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        return view('super-admin.subscriptions.index', compact('subscriptions', 'summaryStats'));
    }

    /**
     * Subscription Detail
     */
    public function subscriptionDetail($id)
    {
        $this->authorizeSuperAdmin();

        $subscription = Subscription::with(['school', 'payments'])->findOrFail($id);

        return view('super-admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Update Subscription (change plan, extend, cancel)
     */
    public function updateSubscription(Request $request, $id)
    {
        $this->authorizeSuperAdmin();

        $subscription = Subscription::findOrFail($id);

        $request->validate([
            'plan'   => 'sometimes|string|in:Starter,Standard,Premium',
            'status' => 'sometimes|string|in:active,expired,cancelled',
            'ends_at'=> 'sometimes|date|after:today',
            'price'  => 'sometimes|numeric|min:0',
        ]);

        if ($request->filled('plan'))    $subscription->plan = $request->plan;
        if ($request->filled('status'))  $subscription->status = $request->status;
        if ($request->filled('ends_at')) $subscription->ends_at = $request->ends_at;
        if ($request->filled('price'))   $subscription->price = $request->price;
        $subscription->save();

        return back()->with('success', "Subscription for '{$subscription->school->name}' updated successfully.");
    }

    /**
     * Platform-wide Payments Ledger
     */
    public function payments(Request $request)
    {
        $this->authorizeSuperAdmin();

        $tab = $request->get('tab', 'school_fees');

        if ($tab === 'subscriptions') {
            $query = SubscriptionPayment::with(['subscription.school']);
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('search')) {
                $query->whereHas('subscription.school', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%");
                });
            }
            $payments = $query->latest()->paginate(20)->withQueryString();
        } else {
            $query = Payment::with(['student', 'invoice', 'schoolBranch']);
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('search')) {
                $query->whereHas('student', function ($q) use ($request) {
                    $q->where('first_name', 'like', "%{$request->search}%")
                      ->orWhere('last_name', 'like', "%{$request->search}%");
                });
            }
            $payments = $query->latest()->paginate(20)->withQueryString();
        }

        $paymentStats = [
            'total_collected'      => Payment::whereIn('status', ['successful', 'paid'])->sum('amount'),
            'sub_collected'        => SubscriptionPayment::where('status', 'paid')->sum('amount'),
            'pending'              => Payment::where('status', 'pending')->sum('amount'),
            'this_month'           => Payment::whereIn('status', ['successful', 'paid'])
                ->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount')
                + SubscriptionPayment::where('status', 'paid')
                ->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount'),
        ];

        return view('super-admin.payments.index', compact('payments', 'paymentStats', 'tab'));
    }

    /**
     * Global Live Activity & Audit Feed
     */
    public function activities(Request $request)
    {
        $this->authorizeSuperAdmin();

        $activities = AuditLog::with('user')
            ->latest()
            ->paginate(25);

        return view('super-admin.activities', compact('activities'));
    }

    /**
     * Platform-wide User Search
     */
    public function users(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = User::with(['school', 'role']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('super-admin.users', compact('users'));
    }

    /**
     * System Settings
     */
    public function systemSettings()
    {
        $this->authorizeSuperAdmin();

        $planPricing = [
            'Starter'  => ['monthly' => 5000, 'yearly' => 50000],
            'Standard' => ['monthly' => 15000, 'yearly' => 150000],
            'Premium'  => ['monthly' => 30000, 'yearly' => 300000],
        ];

        return view('super-admin.settings', compact('planPricing'));
    }

    /**
     * Login as School (Impersonation)
     */
    public function loginAsSchool($id)
    {
        $this->authorizeSuperAdmin();

        $school = School::findOrFail($id);
        $schoolOwner = User::where('school_id', $school->id)
            ->whereHas('role', function ($q) { $q->where('name', 'Owner'); })
            ->first();

        if (!$schoolOwner) {
            $schoolOwner = User::where('school_id', $school->id)->first();
        }

        if (!$schoolOwner) {
            return back()->with('error', "No user found for school '{$school->name}'.");
        }

        // Store super admin ID for returning
        session(['super_admin_id' => Auth::id(), 'impersonating' => true]);
        Auth::login($schoolOwner);

        return redirect()->route('dashboard')->with('success', "You are now viewing as '{$school->name}'.");
    }

    /**
     * Return from impersonation
     */
    public function stopImpersonation()
    {
        $this->authorizeSuperAdmin();

        if (session('impersonating') && session('super_admin_id')) {
            $superAdmin = User::find(session('super_admin_id'));
            if ($superAdmin) {
                Auth::login($superAdmin);
                session()->forget(['super_admin_id', 'impersonating']);
                return redirect()->route('super-admin.dashboard')->with('success', 'Returned to Super Admin portal.');
            }
        }

        return redirect()->route('super-admin.dashboard');
    }
}
