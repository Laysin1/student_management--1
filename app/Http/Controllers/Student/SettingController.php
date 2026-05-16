<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\User;

class SettingController extends Controller
{
    public function index()
    {
        /** @var Student $student */
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login.student');
        }

        $student->load(['user', 'class']);

        return view('student.setting.index', compact('student'));
    }

    public function update(Request $request)
    {
        /** @var Student $student */
        $student = Auth::user()->student;

        /** @var User $user */
        $user = Auth::user();

        if (!$student) {
            return redirect()->route('login.student');
        }

        $validated = $request->validate([
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        // Update phone number
        $student->phone_number = $validated['phone_number'] ?? null;
        $student->save();

        // Update password if entered
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->save();
        }

        return redirect()
            ->route('student.setting.index')
            ->with('success', 'Settings updated successfully!');
    }
}
