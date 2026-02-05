<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'class'])->orderBy('last_name');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('email', 'like', "%{$search}%"));
            });
        }

        if ($classId = $request->get('class_id')) {
            $query->where('class_id', $classId);
        }

        $students = $query->paginate(12)->withQueryString();
        $classes = SchoolClass::select('id','name','grade_level')->orderBy('grade_level')->get();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function show(Student $student)
    {
        $student->load(['user','class']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load(['user','class']);
        $classes = SchoolClass::select('id','name','grade_level')->orderBy('grade_level')->get();
        return view('admin.students.edit', compact('student','classes'));
    }
    public function create()
{
    $classes = \App\Models\SchoolClass::select('id','name','grade_level')->orderBy('grade_level')->get();
    return view('admin.students.create', compact('classes'));
}

public function update(Request $request, Student $student)
{
    $validated = $request->validate([
        'student_id'    => 'nullable|string|max:50|unique:students,student_id,' . $student->id,
        'first_name'    => 'required|string|max:100',
        'last_name'     => 'nullable|string|max:100',
        'date_of_birth' => 'required|date|before:today',
        'phone_number'  => 'nullable|string|max:30',
        'parent_number' => 'nullable|string|max:30',
        'class_id'      => 'nullable|exists:school_classes,id',
        'gender'        => 'nullable|in:Male,Female,Other',
        'profile_photo' => 'nullable|image|max:2048',
    ]);

    $student->student_id    = $validated['student_id'] ?? $student->student_id;
    $student->first_name    = $validated['first_name'];
    $student->last_name     = $validated['last_name'] ?? $student->last_name;
    $student->date_of_birth = $validated['date_of_birth'];
    $student->phone_number  = $validated['phone_number'] ?? $student->phone_number;
    $student->parent_number = $validated['parent_number'] ?? $student->parent_number;
    $student->class_id      = $validated['class_id'] ?? $student->class_id;
    $student->gender        = $validated['gender'] ?? $student->gender;

    if ($request->hasFile('profile_photo')) {
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $student->profile_photo_path = $path;
    }

    $student->save();

    return redirect()->route('students.show', $student->id)->with('success', 'Student updated successfully.');
}
public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        'date_of_birth' => 'nullable|date',
        'phone_number' => 'nullable|string|max:20',
        'parent_number' => 'nullable|string|max:20',
        'student_id' => 'nullable|unique:students',
        'class_id' => 'nullable|exists:school_classes,id',
        'gender' => 'nullable|in:Male,Female,Other',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    try {
        DB::beginTransaction();

        // Create user account
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
        ]);

        // Handle profile photo
        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Create student record
        Student::create([
            'user_id' => $user->id,
            'student_id' => $validated['student_id'],
            'date_of_birth' => $validated['date_of_birth'],
            'phone_number' => $validated['phone_number'],
            'parent_number' => $validated['parent_number'],
            'class_id' => $validated['class_id'],
            'gender' => $validated['gender'],
            'profile_photo' => $photoPath,
        ]);

        DB::commit();
        return redirect()->route('students.index')->with('success', 'Student created successfully');
    } catch (Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Failed to create student: ' . $e->getMessage()]);
    }
}
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted.');
    }
}
