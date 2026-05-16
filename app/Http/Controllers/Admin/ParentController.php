<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentUser;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $parents = ParentUser::with(['user', 'students'])
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('occupation', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        })
        ->latest()
        ->paginate(15);

    $parents->appends($request->query());

    return view('admin.parents.index', compact('parents'));
}

    public function create()
    {
        $students = Student::all();
        return view('admin.parents.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'occupation' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'student_ids' => 'array',
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'parent',
        ]);

        $parent = ParentUser::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'occupation' => $validated['occupation'] ?? null,
        ]);

        if ($request->has('student_ids')) {
            $parent->students()->sync($validated['student_ids']);
        }

        return redirect()->route('parents.index')->with('success', 'Parent created successfully');
    }

    public function edit(ParentUser $parent)
    {
        $students = Student::all();
        return view('admin.parents.edit', compact('parent', 'students'));
    }

    public function update(Request $request, ParentUser $parent)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $parent->user_id,
            'phone' => 'required|string|max:20',
            'occupation' => 'nullable|string',
            'student_ids' => 'array',
        ]);

        $parent->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'occupation' => $validated['occupation'] ?? null,
        ]);

        $parent->user->update([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
        ]);

        if ($request->has('student_ids')) {
            $parent->students()->sync($validated['student_ids']);
        }

        return redirect()->route('parents.index')->with('success', 'Parent updated successfully');
    }

    public function destroy(ParentUser $parent)
    {
        $parent->user->delete();
        $parent->delete();
        return redirect()->route('parents.index')->with('success', 'Parent deleted successfully');
    }
}
