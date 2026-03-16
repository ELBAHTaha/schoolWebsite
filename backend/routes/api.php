<?php

use App\Http\Controllers\Api\PublicAnnouncementController;
use App\Http\Controllers\Api\PublicClassController;
use App\Http\Controllers\Api\PublicFormController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AdminClassesController;
use App\Http\Controllers\Api\AdminPaymentsController;
use App\Http\Controllers\Api\AdminUsersController;
use App\Http\Controllers\Api\CommercialLeadsController;
use App\Http\Controllers\Api\ProfessorAssignmentsController;
use App\Http\Controllers\Api\SecretaryPaymentsController;
use App\Http\Controllers\Api\SecretaryStudentsController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'apiLogin'])
    ->middleware('web')
    ->name('api.login');

Route::post('/logout', [AuthController::class, 'apiLogout'])
    ->middleware('web')
    ->name('api.logout');

Route::get('/classes', [PublicClassController::class, 'index'])
    ->name('api.classes.index');

Route::get('/public-announcements', [PublicAnnouncementController::class, 'index'])
    ->name('api.public-announcements.index');

Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'send'])
    ->name('api.contact.store');

Route::post('/pre-registration', [PublicFormController::class, 'preRegistration'])
    ->name('api.pre-registration.store');

Route::post('/register', [PublicFormController::class, 'register'])
    ->name('api.register.store');

Route::middleware(['web', 'auth', 'role:admin,directeur'])
    ->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
            ->name('api.dashboard.admin');
        Route::get('/admin/users', [AdminUsersController::class, 'index'])
            ->name('api.admin.users.index');
        Route::post('/admin/users', [AdminUsersController::class, 'store'])
            ->name('api.admin.users.store');
        Route::put('/admin/users/{user}', [AdminUsersController::class, 'update'])
            ->name('api.admin.users.update');
        Route::delete('/admin/users/{user}', [AdminUsersController::class, 'destroy'])
            ->name('api.admin.users.destroy');
        Route::get('/admin/classes', [AdminClassesController::class, 'index'])
            ->name('api.admin.classes.index');
        Route::post('/admin/classes', [AdminClassesController::class, 'store'])
            ->name('api.admin.classes.store');
        Route::put('/admin/classes/{class}', [AdminClassesController::class, 'update'])
            ->name('api.admin.classes.update');
        Route::delete('/admin/classes/{class}', [AdminClassesController::class, 'destroy'])
            ->name('api.admin.classes.destroy');
        Route::get('/admin/payments', [AdminPaymentsController::class, 'index'])
            ->name('api.admin.payments.index');
    });

Route::middleware(['web', 'auth', 'role:student'])
    ->get('/dashboard/student', [DashboardController::class, 'student'])
    ->name('api.dashboard.student');

Route::middleware(['web', 'auth', 'role:professor'])
    ->get('/dashboard/professor', [DashboardController::class, 'professor'])
    ->name('api.dashboard.professor');

Route::middleware(['web', 'auth', 'role:professor'])
    ->prefix('professor')
    ->group(function () {
        Route::get('/assignments', [ProfessorAssignmentsController::class, 'index'])
            ->name('api.professor.assignments.index');
        Route::post('/assignments', [ProfessorAssignmentsController::class, 'store'])
            ->name('api.professor.assignments.store');
        Route::put('/assignments/{assignment}', [ProfessorAssignmentsController::class, 'update'])
            ->name('api.professor.assignments.update');
        Route::delete('/assignments/{assignment}', [ProfessorAssignmentsController::class, 'destroy'])
            ->name('api.professor.assignments.destroy');
    });

Route::middleware(['web', 'auth', 'role:secretary'])
    ->get('/dashboard/secretary', [DashboardController::class, 'secretary'])
    ->name('api.dashboard.secretary');

Route::middleware(['web', 'auth', 'role:secretary'])
    ->prefix('secretary')
    ->group(function () {
        Route::get('/students', [SecretaryStudentsController::class, 'index'])
            ->name('api.secretary.students.index');
        Route::post('/students', [SecretaryStudentsController::class, 'store'])
            ->name('api.secretary.students.store');
        Route::put('/students/{user}', [SecretaryStudentsController::class, 'update'])
            ->name('api.secretary.students.update');
        Route::delete('/students/{user}', [SecretaryStudentsController::class, 'destroy'])
            ->name('api.secretary.students.destroy');
        Route::get('/payments', [SecretaryPaymentsController::class, 'index'])
            ->name('api.secretary.payments.index');
        Route::post('/payments', [SecretaryPaymentsController::class, 'store'])
            ->name('api.secretary.payments.store');
        Route::put('/payments/{payment}', [SecretaryPaymentsController::class, 'update'])
            ->name('api.secretary.payments.update');
        Route::delete('/payments/{payment}', [SecretaryPaymentsController::class, 'destroy'])
            ->name('api.secretary.payments.destroy');
    });

Route::middleware(['web', 'auth', 'role:commercial'])
    ->group(function () {
        Route::get('/dashboard/commercial', [DashboardController::class, 'commercial'])
            ->name('api.dashboard.commercial');
        Route::get('/commercial/leads', [CommercialLeadsController::class, 'index'])
            ->name('api.commercial.leads.index');
        Route::patch('/commercial/leads/{lead}', [CommercialLeadsController::class, 'update'])
            ->name('api.commercial.leads.update');
    });
