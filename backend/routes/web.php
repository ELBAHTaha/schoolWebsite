<?php

use App\Http\Controllers\Admin\ClassManagementController as AdminClassManagementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FinancialReportController as AdminFinancialController;
use App\Http\Controllers\Admin\PublicAnnouncementController as AdminPublicAnnouncementController;
use App\Http\Controllers\Admin\RoomManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Professor\AnnouncementController as ProfessorAnnouncementController;
use App\Http\Controllers\Professor\AssignmentController as ProfessorAssignmentController;
use App\Http\Controllers\Professor\DashboardController as ProfessorDashboardController;
use App\Http\Controllers\Professor\MaterialController as ProfessorMaterialController;
use App\Http\Controllers\Professor\ProfileController as ProfessorProfileController;
use App\Http\Controllers\Professor\ScheduleController as ProfessorScheduleController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Secretary\DashboardController as SecretaryDashboardController;
use App\Http\Controllers\Secretary\PublicAnnouncementController as SecretaryPublicAnnouncementController;
use App\Http\Controllers\Secretary\ProfessorManagementController as SecretaryProfessorManagementController;
use App\Http\Controllers\Secretary\PaymentController as SecretaryPaymentController;
use App\Http\Controllers\Secretary\StudentController as SecretaryStudentController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\CourseContentController as StudentCourseContentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\ScheduleController as StudentScheduleController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

// Visitor/public routes
Route::get('/', [VisitorController::class, 'home'])->name('home');
Route::get('/public/stats', [VisitorController::class, 'stats'])->name('visitor.stats');
Route::get('/contact', [VisitorController::class, 'contactForm'])->name('visitor.contact');
Route::post('/contact', [VisitorController::class, 'sendContact'])->name('visitor.contact.send');
Route::get('/create-account', [VisitorController::class, 'createAccountForm'])->name('visitor.create-account');
Route::post('/create-account', [VisitorController::class, 'createAccount'])->name('visitor.create-account.store');
Route::get('/pre-registration', [VisitorController::class, 'preRegistrationForm'])->name('visitor.pre-registration');
Route::post('/pre-registration', [VisitorController::class, 'preRegistration'])->name('visitor.pre-registration.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])
    ->prefix('dashboard/admin')
    ->as('admin.')
    ->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserManagementController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('classes', AdminClassManagementController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('rooms', RoomManagementController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('announcements', AdminPublicAnnouncementController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);

    Route::get('payments', [AdminFinancialController::class, 'index'])->name('payments.index');
});

Route::middleware(['auth', 'role:secretary'])
    ->prefix('dashboard/secretary')
    ->as('secretary.')
    ->group(function () {
    Route::get('/', [SecretaryDashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', SecretaryStudentController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::resource('professors', SecretaryProfessorManagementController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('payments', SecretaryPaymentController::class);
    Route::resource('announcements', SecretaryPublicAnnouncementController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
});

Route::middleware(['auth', 'role:professor'])
    ->prefix('dashboard/professor')
    ->as('professor.')
    ->group(function () {
    Route::get('/', [ProfessorDashboardController::class, 'index'])->name('dashboard');

    Route::resource('assignments', ProfessorAssignmentController::class);
    Route::resource('materials', ProfessorMaterialController::class);
    Route::resource('announcements', ProfessorAnnouncementController::class);
    Route::get('schedules', [ProfessorScheduleController::class, 'index'])->name('schedules.index');
    Route::get('profile', [ProfessorProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfessorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfessorProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:student'])
    ->prefix('dashboard/student')
    ->as('student.')
    ->group(function () {
    Route::get('/', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::get('schedule', [StudentScheduleController::class, 'index'])->name('schedule.index');
    Route::get('assignments', [StudentAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('course-content', [StudentCourseContentController::class, 'index'])->name('course-content.index');

    Route::get('profile', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [StudentProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:student'])->get('pdfs/{pdf}/download', [PdfController::class, 'download'])->name('pdfs.download');
