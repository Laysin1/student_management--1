<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\RoleLoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use App\Http\Controllers\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Teacher\ScoreController as TeacherScoreController;
use App\Http\Controllers\Teacher\SettingController as TeacherSettingController;
use App\Http\Controllers\Teacher\ScheduleController as TeacherScheduleController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Role Selector (Public)
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('role-selector'))->name('role.selector');

/*
|--------------------------------------------------------------------------
| Login Routes (Public)
|--------------------------------------------------------------------------
*/
Route::get('/login/admin', [RoleLoginController::class, 'showAdminLogin'])->name('login.admin');
Route::get('/login/teacher', [RoleLoginController::class, 'showTeacherLogin'])->name('login.teacher');
Route::get('/login/student', [RoleLoginController::class, 'showStudentLogin'])->name('login.student');
Route::get('/login/parent', [RoleLoginController::class, 'showParentLogin'])->name('login.parent');
Route::post('/login/admin', [RoleLoginController::class, 'adminLogin'])->name('login.admin.submit');
Route::post('/login/teacher', [RoleLoginController::class, 'teacherLogin'])->name('login.teacher.submit');
Route::post('/login/student', [RoleLoginController::class, 'studentLogin'])->name('login.student.submit');
Route::post('/login/parent', [RoleLoginController::class, 'parentLogin'])->name('login.parent.submit');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (All Roles)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard role redirect
    Route::get('/dashboard', function () {
        switch (Auth::user()->role) {
            case 'admin':   return redirect()->route('dashboard.admin');
            case 'teacher': return redirect()->route('dashboard.teacher');
            case 'student': return redirect()->route('dashboard.student');
            case 'parent':  return redirect()->route('dashboard.parent');
            default:
                Auth::logout();
                return redirect()->route('role.selector');
        }
    })->name('dashboard');

    // Dashboard pages
    Route::get('/dashboard/admin', [DashboardController::class, 'index'])->name('dashboard.admin');
    Route::get('/dashboard/teacher', [TeacherDashboardController::class, 'teacherHome'])->name('dashboard.teacher');
    Route::get('/dashboard/student', fn() => view('dashboards.student'))->name('dashboard.student');
    Route::get('/dashboard/parent', fn() => view('dashboards.parent'))->name('dashboard.parent');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Logout
    Route::post('/logout', [RoleLoginController::class, 'logout'])->name('logout');

    // Admin CRUD
    Route::prefix('admin')->group(function () {

        // Teachers - custom routes BEFORE resource
        Route::get('teachers/filter', [TeacherController::class, 'filter'])->name('teachers.filter');
        Route::resource('teachers', TeacherController::class);

        // Classes - custom routes BEFORE resource
        Route::get('classes/delete-list', [ClassController::class, 'deleteList'])->name('classes.delete-list');
        Route::resource('classes', ClassController::class);

        // Students
        Route::resource('students', StudentController::class);

        // Schedules
        Route::resource('schedules', ScheduleController::class);

        // Settings
        Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
        Route::put('setting', [SettingController::class, 'update'])->name('setting.update');

    });

    // Teacher CRUD
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::resource('students', TeacherStudentController::class);

        // Classes routes with custom attendance and scores
        Route::get('classes', [TeacherClassController::class, 'index'])->name('classes.index');
        Route::get('classes/attend', [TeacherClassController::class, 'attend'])->name('classes.attend');
        Route::post('classes/saveAttend', [TeacherClassController::class, 'saveAttend'])->name('classes.saveAttend');
        Route::get('classes/scores', [TeacherClassController::class, 'scores'])->name('classes.scores');
        Route::post('classes/saveScores', [TeacherClassController::class, 'saveScores'])->name('classes.saveScores');
        Route::get('classes/attendanceReport', [TeacherClassController::class, 'attendanceReport'])->name('classes.attendanceReport');
        Route::get('classes/scoresReport', [TeacherClassController::class, 'scoresReport'])->name('classes.scoresReport');
        Route::resource('classes', TeacherClassController::class, ['except' => ['index']]);

        // Schedule routes
        Route::get('schedule', [TeacherScheduleController::class, 'index'])->name('schedule.index');

        // Setting routes
        Route::get('setting', [TeacherSettingController::class, 'index'])->name('setting.index');
        Route::put('setting', [TeacherSettingController::class, 'update'])->name('setting.update');

        Route::resource('scores', TeacherScoreController::class);
    });});

require __DIR__ . '/auth.php';
