<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Subject;

class SettingController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $teacher = Teacher::with(['classes'])
        ->where('user_id', $user->id)
        ->firstOrFail();

    $teacherSubject = Subject::find($teacher->subject_id);

    return view('teacher.setting.index', compact('user', 'teacher', 'teacherSubject'));
}

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $teacher = Teacher::where('user_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:30',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'password' => 'nullable|min:8',
        ]);

        // Update user
        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update teacher
        $teacher->phone_number = $request->phone_number;
        $teacher->gender = $request->gender;
        $teacher->save();

        return redirect()
            ->route('teacher.setting.index')
            ->with('success', 'Settings updated successfully');
    }
}
