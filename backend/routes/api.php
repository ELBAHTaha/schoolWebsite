<?php

use App\Http\Controllers\Api\PublicAnnouncementController;
use App\Http\Controllers\Api\PublicClassController;
use App\Http\Controllers\Api\PublicFormController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AdminClassesController;
use App\Http\Controllers\Api\AdminPaymentsController;
use App\Http\Controllers\Api\AdminAnnouncementsController;
use App\Http\Controllers\Api\AdminRoomsController;
use App\Http\Controllers\Api\AdminUsersController;
use App\Http\Controllers\Api\CommercialLeadsController;
use App\Http\Controllers\Api\ProfessorAssignmentsController;
use App\Http\Controllers\Api\ProfessorMaterialsController;
use App\Http\Controllers\Api\ProfessorScheduleController;
use App\Http\Controllers\Api\SecretaryClassesController;
use App\Http\Controllers\Api\SecretaryPaymentsController;
use App\Http\Controllers\Api\SecretaryProfessorsController;
use App\Http\Controllers\Api\SecretaryRoomsController;
use App\Http\Controllers\Api\SecretaryStudentsController;
use App\Http\Controllers\Api\StudentAssignmentsController;
use App\Http\Controllers\Api\StudentMaterialsController;
use App\Http\Controllers\Api\StudentScheduleController;
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
        Route::get('/admin/rooms', [AdminRoomsController::class, 'index'])
            ->name('api.admin.rooms.index');
        Route::post('/admin/rooms', [AdminRoomsController::class, 'store'])
            ->name('api.admin.rooms.store');
        Route::put('/admin/rooms/{room}', [AdminRoomsController::class, 'update'])
            ->name('api.admin.rooms.update');
        Route::delete('/admin/rooms/{room}', [AdminRoomsController::class, 'destroy'])
            ->name('api.admin.rooms.destroy');
        Route::post('/admin/announcements', [AdminAnnouncementsController::class, 'store'])
            ->name('api.admin.announcements.store');
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
        Route::post('/admin/payments', [AdminPaymentsController::class, 'store'])
            ->name('api.admin.payments.store');
    });

Route::middleware(['web', 'auth', 'role:student'])
    ->get('/dashboard/student', [DashboardController::class, 'student'])
    ->name('api.dashboard.student');

Route::middleware(['web', 'auth', 'role:student'])
    ->prefix('student')
    ->group(function () {
        Route::get('/materials', [StudentMaterialsController::class, 'index'])
            ->name('api.student.materials.index');
        Route::get('/materials/{material}/download', [StudentMaterialsController::class, 'download'])
            ->name('api.student.materials.download');
        Route::get('/assignments', [StudentAssignmentsController::class, 'index'])
            ->name('api.student.assignments.index');
        Route::get('/assignments/{assignment}/download', [StudentAssignmentsController::class, 'download'])
            ->name('api.student.assignments.download');
        Route::get('/timetable', [StudentScheduleController::class, 'index'])
            ->name('api.student.timetable.index');
    });

Route::middleware(['web', 'auth', 'role:professor'])
    ->get('/dashboard/professor', [DashboardController::class, 'professor'])
    ->name('api.dashboard.professor');

Route::middleware(['web', 'auth', 'role:professor'])
    ->prefix('professor')
    ->group(function () {
        Route::get('/classes', [ProfessorAssignmentsController::class, 'classes'])
            ->name('api.professor.classes.index');
        Route::get('/timetable', [ProfessorScheduleController::class, 'index'])
            ->name('api.professor.timetable.index');
        Route::get('/assignments', [ProfessorAssignmentsController::class, 'index'])
            ->name('api.professor.assignments.index');
        Route::post('/assignments', [ProfessorAssignmentsController::class, 'store'])
            ->name('api.professor.assignments.store');
        Route::put('/assignments/{assignment}', [ProfessorAssignmentsController::class, 'update'])
            ->name('api.professor.assignments.update');
        Route::delete('/assignments/{assignment}', [ProfessorAssignmentsController::class, 'destroy'])
            ->name('api.professor.assignments.destroy');
        Route::get('/materials', [ProfessorMaterialsController::class, 'index'])
            ->name('api.professor.materials.index');
        Route::post('/materials', [ProfessorMaterialsController::class, 'store'])
            ->name('api.professor.materials.store');
        Route::delete('/materials/{material}', [ProfessorMaterialsController::class, 'destroy'])
            ->name('api.professor.materials.destroy');
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
        Route::put('/students/{student}', [SecretaryStudentsController::class, 'update'])
            ->name('api.secretary.students.update');
        Route::delete('/students/{student}', [SecretaryStudentsController::class, 'destroy'])
            ->name('api.secretary.students.destroy');
        Route::get('/classes', [SecretaryClassesController::class, 'index'])
            ->name('api.secretary.classes.index');
        Route::post('/classes', [SecretaryClassesController::class, 'store'])
            ->name('api.secretary.classes.store');
        Route::get('/rooms', [SecretaryRoomsController::class, 'index'])
            ->name('api.secretary.rooms.index');
        Route::get('/professors', [SecretaryProfessorsController::class, 'index'])
            ->name('api.secretary.professors.index');
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
