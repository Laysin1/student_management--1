<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        return view('teacher.setting.index', compact('user', 'teacher'));
    }

    public function create()
    {
        return view('teacher.setting.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('teacher.setting.index');
    }

    public function show($id)
    {
        return view('teacher.setting.show');
    }

    public function edit($id)
    {
        return view('teacher.setting.edit');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $user->update($validated);

        return redirect()->route('teacher.setting.index')->with('success', 'Settings updated successfully');
    }

    public function destroy($id)
    {
        return redirect()->route('teacher.setting.index');
    }
}
