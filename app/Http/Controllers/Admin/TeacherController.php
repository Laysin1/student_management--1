<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class TeacherController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
{
    $q = \App\Models\Teacher::with(['user','class','subject'])->orderBy('last_name');

    if ($search = $request->get('search')) {
        $q->where(function ($w) use ($search) {
            $w->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$search}%"));
        });
    }

    if ($subjectId = $request->get('subject_id')) {
        $q->where('subject_id', $subjectId);
    }

    $teachers = $q->paginate(12)->withQueryString();
    $subjects = \App\Models\Subject::select('id','name')->orderBy('name')->get();

    return view('admin.teachers.index', compact('teachers','subjects'));
}

    public function create()
    {
        $classes = SchoolClass::select('id','name','grade_level')
            ->orderBy('grade_level')
            ->get();

        $subjects = Subject::orderBy('name')->get();

        return view('admin.teachers.create', compact('classes','subjects'));
    }


public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'phone_number' => 'required|string|max:20',
        'gender' => 'required|in:Male,Female,Other',
        'subject_id' => 'required|exists:subjects,id',
        'password' => 'required|min:8|confirmed',
        'class_ids' => 'nullable|array',
        'class_ids.*' => 'exists:school_classes,id',
    ]);

    try {
        DB::beginTransaction();

        // Create user
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'teacher',
        ]);

        // Create teacher
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone_number' => $validated['phone_number'],
            'gender' => $validated['gender'],
            'subject_id' => $validated['subject_id'],
        ]);

        // Assign classes
        if (isset($validated['class_ids'])) {
            foreach ($validated['class_ids'] as $classId) {
                SchoolClass::find($classId)->update(['teacher_id' => $teacher->id]);
            }
        }

        DB::commit();
        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully');
    } catch (Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Failed to create teacher: ' . $e->getMessage()]);
    }
}

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'subject', 'classes.students']);
        return view('admin.teachers.show', compact('teacher'));

    }

    public function edit(Teacher $teacher)
    {
        $teacher->load(['user', 'subject', 'classes']);

        $classes = \App\Models\SchoolClass::all();
        $subjects = \App\Models\Subject::all();

        return view('admin.teachers.edit', compact('teacher', 'classes', 'subjects'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other',
            'subject_id' => 'nullable|exists:subjects,id',
            'password' => 'nullable|min:8',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'nullable|exists:school_classes,id',
        ]);

        try {
            DB::beginTransaction();

            // Update user
            $teacher->user->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
            ]);

            if (!empty($validated['password'])) {
                $teacher->user->update(['password' => bcrypt($validated['password'])]);
            }

            // Update teacher
            $teacher->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone_number' => $validated['phone_number'],
                'gender' => $validated['gender'],
                'subject_id' => $validated['subject_id'],
            ]);

            // Get selected class IDs, filter out empty values
            $selectedClassIds = array_filter($validated['class_ids'] ?? []);

            // First, unassign all classes from this teacher
            SchoolClass::where('teacher_id', $teacher->id)
                ->update(['teacher_id' => null]);

            // Then assign the selected classes to this teacher
            if (!empty($selectedClassIds)) {
                SchoolClass::whereIn('id', $selectedClassIds)
                    ->update(['teacher_id' => $teacher->id]);
            }

            DB::commit();
            return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update teacher: ' . $e->getMessage()])->withInput();
        }
    }    public function destroy(Teacher $teacher)
    {
        $user = $teacher->user; // preserve before delete
        $teacher->delete();
        if ($user) {
            $user->delete();
        }
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted.');
    }

    // AJAX filter endpoint for index page
public function filter(\Illuminate\Http\Request $request)
{
    $q = \App\Models\Teacher::with(['user','class','subject'])->orderBy('last_name');

    if ($search = $request->get('search')) {
        $q->where(function ($w) use ($search) {
            $w->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$search}%"));
        });
    }

    if ($subjectId = $request->get('subject_id')) {
        $q->where('subject_id', $subjectId);
    }

    return response()->json(['teachers' => $q->limit(50)->get()]);
}

}
