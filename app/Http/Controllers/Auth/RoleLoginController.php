<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleLoginController extends Controller
{
    /**
     * Show login form for Admin
     */
    public function showAdminLogin()
    {
        return view('auth.login-admin');
    }

    /**
     * Show login form for Teacher
     */
    public function showTeacherLogin()
    {
        return view('auth.login-teacher');
    }

    /**
     * Show login form for Student
     */
    public function showStudentLogin()
    {
        return view('auth.login-student');
    }

    /**
     * Show login form for Parent
     */
    public function showParentLogin()
    {
        return view('auth.login-parent');
    }

    /**
     * Admin login
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'admin';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Teacher login
     */
    public function teacherLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'teacher';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('teacher.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Student login
     */
    public function studentLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'student';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('student.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Parent login
     */
    public function parentLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'parent';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('parent.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Logout for all roles
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('role.selector');
    }
}
