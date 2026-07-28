<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\AdmissionApplicationController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\CbtExamController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\FeeCategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\SchoolProfileController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\StudentDocumentController;
use Illuminate\Support\Facades\Route;

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

// Public Offer Acceptance/Decline
Route::get('/accept-offer/{offerId}', [AdmissionApplicationController::class, 'showOfferAcceptance'])->name('offer.show');
Route::post('/accept-offer/{offerId}', [AdmissionApplicationController::class, 'handleOfferResponse'])->name('offer.respond');

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

    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('success', 'Email verified successfully!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent to your email!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Password Reset Routes
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = \Illuminate\Support\Facades\Password::sendResetLink(
        $request->only('email')
    );
    return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
        ? back()->with('success', 'Password reset link sent to your email!')
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
    $status = \Illuminate\Support\Facades\Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => \Illuminate\Support\Facades\Hash::make($password)
            ])->setRememberToken(\Illuminate\Support\Str::random(60));
            $user->save();
        }
    );
    return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
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
        Route::get('/', [\App\Http\Controllers\AssessmentQuestionController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\AssessmentQuestionController::class, 'store'])->name('store');
        Route::delete('/{id}', [\App\Http\Controllers\AssessmentQuestionController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('assessment-options')->name('assessment-options.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AssessmentOptionController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\AssessmentOptionController::class, 'store'])->name('store');
        Route::delete('/{id}', [\App\Http\Controllers\AssessmentOptionController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('assessment-answers')->name('assessment-answers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AssessmentAnswerController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\AssessmentAnswerController::class, 'store'])->name('store');
        Route::delete('/{id}', [\App\Http\Controllers\AssessmentAnswerController::class, 'destroy'])->name('destroy');
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
    Route::prefix('super-admin')->name('super-admin.')->controller(\App\Http\Controllers\SuperAdminController::class)->group(function () {
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
});

// Debug routes
if (config('app.debug')) {
    require __DIR__.'/debug_reports.php';
}
