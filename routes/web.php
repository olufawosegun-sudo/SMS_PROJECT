<?php

use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\AdmissionApplicationController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssessmentAnswerController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AssessmentOptionController;
use App\Http\Controllers\AssessmentQuestionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CbtExamController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FeeCategoryController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\GuardianWaecController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OwnerWaecController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\PrincipalWaecController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PublicInvoiceController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SchoolProfileController;
use App\Http\Controllers\SchoolWebsiteController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentDocumentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\WaecRemittanceController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Public Admission Application Form
Route::get('/apply', [AdmissionApplicationController::class, 'showForm'])->name('apply');
Route::post('/apply', [AdmissionApplicationController::class, 'submitForm']);

// Individual School Public Website & Portals
Route::prefix('school/{slug}')->name('school.')->group(function () {
    Route::get('/', [SchoolWebsiteController::class, 'show'])->name('website');
    Route::get('/apply', [SchoolWebsiteController::class, 'apply'])->name('apply');
    Route::post('/apply', [SchoolWebsiteController::class, 'submitAdmission'])->name('apply.submit');
    Route::get('/careers', [SchoolWebsiteController::class, 'careers'])->name('careers');
    Route::post('/careers', [SchoolWebsiteController::class, 'submitJobApplication'])->name('careers.submit');
});

// Public Offer Acceptance/Decline
Route::get('/accept-offer/{offerId}', [AdmissionApplicationController::class, 'showOfferAcceptance'])->name('offer.show');
Route::post('/accept-offer/{offerId}', [AdmissionApplicationController::class, 'handleOfferResponse'])->name('offer.respond');

// Public Invoice Payment & Instant Receipt
Route::get('/invoices/pay/{uuid}', [PublicInvoiceController::class, 'show'])->name('invoices.public.pay');
Route::post('/invoices/pay/{uuid}/process', [PublicInvoiceController::class, 'processPayment'])->name('invoices.public.process');
Route::get('/invoices/pay/{uuid}/receipt', [PublicInvoiceController::class, 'receipt'])->name('invoices.public.receipt');

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
    Route::get('/logout', 'logout')->middleware('auth');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/dashboard')->with('success', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent to your email!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Password Reset Routes
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('success', 'Password reset link sent to your email!')
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->setRememberToken(Str::random(60));
            $user->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Password reset successfully!')
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management Routes
    Route::resource('teachers', TeacherController::class);
    Route::resource('staff-attendance', StaffAttendanceController::class);
    Route::resource('payroll', PayrollController::class);
    Route::resource('students', StudentController::class);
    Route::resource('guardians', GuardianController::class);
    Route::resource('principals', PrincipalController::class);
    Route::resource('departments', DepartmentController::class);

    // Staff Job Applications / Recruitment
    Route::resource('job-applications', JobApplicationController::class);
    Route::post('job-applications/{id}/status', [JobApplicationController::class, 'updateStatus'])->name('job-applications.status');
    Route::get('job-applications/{id}/download-resume', [JobApplicationController::class, 'downloadResume'])->name('job-applications.download-resume');
    Route::get('job-applications/{id}/download-certificates', [JobApplicationController::class, 'downloadCertificates'])->name('job-applications.download-certificates');

    // AJAX endpoint for class arms (standalone to avoid resource route conflict)
    Route::get('get-class-arms/{classId}', [AdmissionController::class, 'getArmsForEnroll'])->name('admissions.arms-for-enroll');

    // Dynamic Admin/Academic modules
    Route::resource('admissions', AdmissionController::class);
    Route::post('admissions/{id}/send-offer', [AdmissionController::class, 'sendOffer'])->name('admissions.send-offer');
    Route::get('admissions/{id}/download-offer', [AdmissionController::class, 'downloadOffer'])->name('admissions.download-offer');
    Route::post('admissions/{id}/enroll', [AdmissionController::class, 'enrollStudent'])->name('admissions.enroll');
    Route::resource('promotions', PromotionController::class);
    Route::resource('transfers', TransferController::class);
    Route::resource('alumni', AlumniController::class);
    Route::resource('attendance', AttendanceController::class);
    Route::resource('sessions', AcademicSessionController::class);
    Route::post('sessions/{id}/set-active', [AcademicSessionController::class, 'setActive'])->name('sessions.set-active');
    Route::resource('terms', AcademicTermController::class);
    Route::resource('classes', ClassController::class);
    Route::get('classes/{class}/arms', [ClassController::class, 'getArms'])->name('classes.arms');
    Route::post('class-arms', [ClassController::class, 'storeArm'])->name('class-arms.store');
    Route::put('class-arms/{arm}', [ClassController::class, 'updateArm'])->name('class-arms.update');
    Route::delete('class-arms/{arm}', [ClassController::class, 'destroyArm'])->name('class-arms.destroy');
    Route::resource('subjects', SubjectController::class);
    Route::resource('timetables', TimetableController::class);

    // Examination & Grading
    Route::resource('assessments', AssessmentController::class);

    // Assessment Sub-modules
    Route::resource('continuous-assessments', AssessmentController::class)->names([
        'index' => 'continuous-assessments.index',
        'create' => 'continuous-assessments.create',
        'store' => 'continuous-assessments.store',
        'show' => 'continuous-assessments.show',
        'edit' => 'continuous-assessments.edit',
        'update' => 'continuous-assessments.update',
        'destroy' => 'continuous-assessments.destroy',
    ]);

    Route::prefix('assessment-questions')->name('assessment-questions.')->group(function () {
        Route::get('/', [AssessmentQuestionController::class, 'index'])->name('index');
        Route::post('/', [AssessmentQuestionController::class, 'store'])->name('store');
        Route::delete('/{id}', [AssessmentQuestionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('assessment-options')->name('assessment-options.')->group(function () {
        Route::get('/', [AssessmentOptionController::class, 'index'])->name('index');
        Route::post('/', [AssessmentOptionController::class, 'store'])->name('store');
        Route::delete('/{id}', [AssessmentOptionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('assessment-answers')->name('assessment-answers.')->group(function () {
        Route::get('/', [AssessmentAnswerController::class, 'index'])->name('index');
        Route::post('/', [AssessmentAnswerController::class, 'store'])->name('store');
        Route::delete('/{id}', [AssessmentAnswerController::class, 'destroy'])->name('destroy');
    });

    Route::resource('cbt-exams', CbtExamController::class);
    Route::post('cbt-exams/{id}/submit-for-approval', [CbtExamController::class, 'submitForApproval'])->name('cbt-exams.submit-for-approval');
    Route::post('cbt-exams/{id}/approve', [CbtExamController::class, 'approve'])->name('cbt-exams.approve');
    Route::post('cbt-exams/{id}/return-for-revision', [CbtExamController::class, 'returnForRevision'])->name('cbt-exams.return-for-revision');
    Route::post('results/batch-approve', [ResultController::class, 'batchApprove'])->name('results.batch-approve');
    Route::post('results/{id}/approve', [ResultController::class, 'approve'])->name('results.approve');
    Route::resource('results', ResultController::class);
    Route::resource('report-cards', ReportCardController::class);
    Route::post('report-cards/{id}/publish', [ReportCardController::class, 'publish'])->name('report-cards.publish');

    // Finance (Owner Only)
    Route::resource('fee-categories', FeeCategoryController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::get('financial-reports', [FinancialReportController::class, 'index'])->name('financial-reports.index');

    // Communication
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('messages', MessageController::class);
    Route::resource('sms', SmsController::class);
    Route::resource('email', EmailController::class);

    // Student Documents
    Route::prefix('student-documents')->name('student-documents.')->group(function () {
        Route::get('/', [StudentDocumentController::class, 'all'])->name('all'); // All documents page
        Route::get('student/{studentId}', [StudentDocumentController::class, 'index'])->name('index');
        Route::post('upload', [StudentDocumentController::class, 'store'])->name('store');
        Route::get('{id}/view', [StudentDocumentController::class, 'view'])->name('view');
        Route::get('{id}/download', [StudentDocumentController::class, 'download'])->name('download');
        Route::delete('{id}', [StudentDocumentController::class, 'destroy'])->name('destroy');
    });
    Route::resource('sms', SmsController::class);
    Route::resource('email', EmailController::class);

    // Settings (Owner Only)
    Route::get('school-profile', [SchoolProfileController::class, 'index'])->name('school-profile.index');
    Route::post('school-profile', [SchoolProfileController::class, 'update'])->name('school-profile.update');
    Route::get('users-roles', [UserRoleController::class, 'index'])->name('users-roles.index');
    Route::get('system-settings', [SystemSettingController::class, 'index'])->name('system-settings.index');
    Route::post('system-settings', [SystemSettingController::class, 'update'])->name('system-settings.update');

    // Database Backup Routes (Owner Only)
    Route::prefix('database-backup')->name('database-backup.')->group(function () {
        Route::get('/', [DatabaseBackupController::class, 'index'])->name('index');
        Route::post('/create', [DatabaseBackupController::class, 'create'])->name('create');
        Route::get('/{id}/download', [DatabaseBackupController::class, 'download'])->name('download');
        Route::delete('/{id}', [DatabaseBackupController::class, 'destroy'])->name('destroy');
    });

    // Super Admin Master Portal Routes (Master System Controller)
    Route::prefix('super-admin')->name('super-admin.')->controller(SuperAdminController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
        Route::get('/schools', 'schools')->name('schools');
        Route::post('/schools/{id}/toggle-status', 'toggleSchoolStatus')->name('schools.toggle-status');
        Route::post('/schools/{id}/login-as', 'loginAsSchool')->name('schools.login-as');
        Route::get('/stop-impersonation', 'stopImpersonation')->name('stop-impersonation');
        Route::get('/subscriptions', 'subscriptions')->name('subscriptions');
        Route::get('/subscriptions/{id}', 'subscriptionDetail')->name('subscriptions.show');
        Route::put('/subscriptions/{id}', 'updateSubscription')->name('subscriptions.update');
        Route::get('/payments', 'payments')->name('payments');
        Route::get('/activities', 'activities')->name('activities');
        Route::get('/users', 'users')->name('users');
        Route::get('/settings', 'systemSettings')->name('settings');
    });

    // ============================================================================
    // WAEC PAYMENT & CANDIDATE MANAGEMENT ROUTES
    // ============================================================================

    // Guardian/Student WAEC Routes
    Route::prefix('waec')->name('waec.')->group(function () {
        Route::get('/candidates', [GuardianWaecController::class, 'candidates'])->name('candidates');
        Route::get('/payments', [GuardianWaecController::class, 'payments'])->name('payments');
        Route::get('/payments/create', [GuardianWaecController::class, 'createPayment'])->name('payments.create');
        Route::post('/payments', [GuardianWaecController::class, 'submitPayment'])->name('payments.submit');
        Route::get('/payments/{payment}', [GuardianWaecController::class, 'showPayment'])->name('payments.show');
        Route::get('/payments/{payment}/receipt', [GuardianWaecController::class, 'downloadReceipt'])->name('payments.receipt');
    });

    // Principal WAEC Routes
    Route::prefix('principal/waec')->name('principal.waec.')->group(function () {
        // Candidates Management
        Route::get('/candidates', [PrincipalWaecController::class, 'candidates'])->name('candidates');
        Route::get('/candidates/create', [PrincipalWaecController::class, 'createCandidate'])->name('candidates.create');
        Route::post('/candidates', [PrincipalWaecController::class, 'storeCandidate'])->name('candidates.store');
        Route::get('/candidates/{candidate}', [PrincipalWaecController::class, 'showCandidate'])->name('candidates.show');
        Route::delete('/candidates/{candidate}', [PrincipalWaecController::class, 'destroyCandidate'])->name('candidates.destroy');

        // Payments Management
        Route::get('/payments', [PrincipalWaecController::class, 'payments'])->name('payments');
        Route::get('/payments/pending', [PrincipalWaecController::class, 'pendingPayments'])->name('payments.pending');
        Route::get('/payments/{payment}', [PrincipalWaecController::class, 'showPayment'])->name('payments.show');
        Route::post('/payments/{payment}/approve', [PrincipalWaecController::class, 'approvePayment'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [PrincipalWaecController::class, 'rejectPayment'])->name('payments.reject');

        // WAEC Remittance / Payment to WAEC
        Route::get('/remittance', [WaecRemittanceController::class, 'index'])->name('remittance.index');
        Route::get('/remittance/create', [WaecRemittanceController::class, 'create'])->name('remittance.create');
        Route::post('/remittance', [WaecRemittanceController::class, 'store'])->name('remittance.store');
        Route::get('/remittance/{id}', [WaecRemittanceController::class, 'show'])->name('remittance.show');
    });

    // Owner WAEC Routes
    Route::prefix('owner/waec')->name('owner.waec.')->group(function () {
        // Reports
        Route::get('/reports', [OwnerWaecController::class, 'reports'])->name('reports');
        Route::get('/reports/financial', [OwnerWaecController::class, 'financial'])->name('reports.financial');
        Route::get('/reports/export', [OwnerWaecController::class, 'export'])->name('reports.export');

        // Fee Configuration
        Route::get('/fees/configuration', [OwnerWaecController::class, 'feeConfiguration'])->name('fees.configuration');
        Route::post('/fees/configuration', [OwnerWaecController::class, 'updateFeeConfiguration'])->name('fees.update');

        // Oversight
        Route::get('/candidates', [OwnerWaecController::class, 'candidates'])->name('candidates');
        Route::get('/payments', [OwnerWaecController::class, 'payments'])->name('payments');

        // WAEC Remittance / Payment to WAEC
        Route::get('/remittance', [WaecRemittanceController::class, 'index'])->name('remittance.index');
        Route::get('/remittance/create', [WaecRemittanceController::class, 'create'])->name('remittance.create');
        Route::post('/remittance', [WaecRemittanceController::class, 'store'])->name('remittance.store');
        Route::get('/remittance/{id}', [WaecRemittanceController::class, 'show'])->name('remittance.show');
    });
});

// Debug routes
if (config('app.debug')) {
    require __DIR__.'/debug_reports.php';
}
