<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class SettingController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login.student');
        }

        $student->load(['user', 'class']);

        return view('student.setting.index', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Auth::user()->student;
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other',
            'password' => 'nullable|min:8',
        ]);

        try {
            // Update user
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update password if provided
            if (!empty($validated['password'])) {
                $user->update(['password' => Hash::make($validated['password'])]);
            }

            // Update student
            $student->update([
                'phone_number' => $validated['phone_number'] ?? $student->phone_number,
                'gender' => $validated['gender'] ?? $student->gender,
            ]);

            return redirect()->route('student.setting.index')->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()])->withInput();
        }
    }
}
