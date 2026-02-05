<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleLoginController extends Controller
{
    /** Show login form for Admin */
    public function showAdminLogin()
    {
        return view('auth.login-admin');
    }

    /** Show login form for Teacher */
    public function showTeacherLogin()
    {
        return view('auth.login-teacher');
    }

    /** Show login form for Student */
    public function showStudentLogin()
    {
        return view('auth.login-student');
    }

    /** Show login form for Parent */
    public function showParentLogin()
    {
        return view('auth.login-parent');
    }

    /** Handle login for any role */
    private function loginRole(Request $request, $role, $redirectRoute)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = $role;

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route($redirectRoute);
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    /** Admin login */
   public function adminLogin(Request $request)
        {
            $credentials = $request->only('email', 'password');
            $credentials['role'] = 'admin';

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('dashboard.admin'); // <-- goes to admin homepage
            }

            return back()->withErrors(['email' => 'Invalid credentials']);
        }



    /** Teacher login */
    public function teacherLogin(Request $request)
    {
        return $this->loginRole($request, 'teacher', 'dashboard.teacher');
    }

    /** Student login */
    public function studentLogin(Request $request)
    {
        return $this->loginRole($request, 'student', 'dashboard.student');
    }

    /** Parent login */
    public function parentLogin(Request $request)
    {
        return $this->loginRole($request, 'parent', 'dashboard.parent');
    }

    /** Logout for all roles */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('role.selector');
    }
}
