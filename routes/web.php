<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RoleLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SubjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group.
|
*/

// ---------------------------------------------------------
// 🌐 Welcome / Role Selector Page
// ---------------------------------------------------------
Route::get('/', function () {
    return view('role-selector');
})->name('role.selector');

// ---------------------------------------------------------
// 🔐 Role-Based Login Pages
// ---------------------------------------------------------
Route::get('/login/admin', [RoleLoginController::class, 'showAdminLogin'])->name('login.admin');
Route::get('/login/teacher', [RoleLoginController::class, 'showTeacherLogin'])->name('login.teacher');
Route::get('/login/student', [RoleLoginController::class, 'showStudentLogin'])->name('login.student');
Route::get('/login/parent', [RoleLoginController::class, 'showParentLogin'])->name('login.parent');

// ---------------------------------------------------------
// 🚀 Role-Based Login Submit Routes
// ---------------------------------------------------------
Route::post('/login/admin', [RoleLoginController::class, 'adminLogin'])->name('login.admin.submit');
Route::post('/login/teacher', [RoleLoginController::class, 'teacherLogin'])->name('login.teacher.submit');
Route::post('/login/student', [RoleLoginController::class, 'studentLogin'])->name('login.student.submit');
Route::post('/login/parent', [RoleLoginController::class, 'parentLogin'])->name('login.parent.submit');

// ---------------------------------------------------------
// 📊 Role-Based Dashboards (Protected by Auth Middleware)
// ---------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // -------------------------
    // 🧭 ADMIN DASHBOARD
    // -------------------------
    Route::get('/admin/dashboard', function () {
        return view('dashboards.admin');
    })->name('admin.dashboard');

    // -------------------------
    // 🧭 TEACHER DASHBOARD
    // -------------------------
    Route::get('/teacher/dashboard', function () {
        return view('dashboards.teacher');
    })->name('teacher.dashboard');

    // -------------------------
    // 🧭 STUDENT DASHBOARD
    // -------------------------
    Route::get('/student/dashboard', function () {
        return view('dashboards.student');
    })->name('student.dashboard');

    // -------------------------
    // 🧭 PARENT DASHBOARD
    // -------------------------
    Route::get('/parent/dashboard', function () {
        return view('dashboards.parent');
    })->name('parent.dashboard');

    // -------------------------
    // 🛠️ ADMIN CRUD ROUTES
    // -------------------------
    Route::resource('admins', AdminController::class); // manage admins
    Route::resource('users', UserController::class); // manage teachers, students, parents
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('subjects', SubjectController::class);

    // -----------------------------------------------------
    // 👤 Profile Management (for all authenticated users)
    // -----------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---------------------------------------------------------
// 🏠 Default Dashboard Redirect
// ---------------------------------------------------------
Route::get('/dashboard', function () {
    return redirect()->route('role.selector');
})->middleware(['auth', 'verified'])->name('dashboard');

// ---------------------------------------------------------
// 🔧 Include Laravel Auth Routes (register, login, reset, etc.)
// ---------------------------------------------------------
require __DIR__ . '/auth.php';
