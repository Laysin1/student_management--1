<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    private array $grades = [
        'Grade 7',
        'Grade 8',
        'Grade 9',
        'Grade 10',
        'Grade 11',
        'Grade 12',
    ];

    public function index()
    {
        $classes = SchoolClass::withCount([
            'students',
            'teachers',
        ])
            ->select('id', 'name', 'grade_level')
            ->orderBy('grade_level')
            ->get();

        $byGrade = $classes->groupBy('grade_level');

        $summary = collect($this->grades)->map(function ($grade) use ($byGrade) {
            $items = $byGrade->get($grade, collect());

            return [
                'grade_level' => $grade,
                'classes_count' => $items->count(),
                'sections' => $items->pluck('name')->values()->all(),
                'first_id' => optional($items->first())->id,
            ];
        });

        return view('admin.class.index', [
            'gradeSummary' => $summary,
        ]);
    }

    public function create()
    {
        $grades = $this->grades;

        return view('admin.class.create', compact('grades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
        ]);

        SchoolClass::create([
            'name' => $validated['name'],
            'grade_level' => $validated['grade_level'],
        ]);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Class added successfully!');
    }

    public function show(SchoolClass $class)
    {
        $search = request('search');

        $students = Student::with(['user', 'class'])
            ->where('class_id', $class->id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('last_name')
            ->paginate(12)
            ->withQueryString();

        $classes = SchoolClass::select('id', 'name', 'grade_level')->get();

        return view('admin.students.index', compact(
            'class',
            'students',
            'classes'
        ));
    }

    public function edit($id)
    {
        $grades = $this->grades;

        $class = SchoolClass::with([
            'teachers.subject',
        ])->findOrFail($id);

        $search = request('search');

        $students = Student::with(['user', 'class'])
            ->where('class_id', $class->id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_id', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('first_name')
            ->paginate(10)
            ->withQueryString();

        $studentCount = Student::where('class_id', $class->id)->count();

        $availableClasses = SchoolClass::where('id', '!=', $class->id)
            ->select('id', 'name', 'grade_level')
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        return view('admin.class.edit', compact(
            'class',
            'grades',
            'students',
            'studentCount',
            'availableClasses'
        ));
    }

    public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
        ]);

        $class->update([
            'name' => $validated['name'],
            'grade_level' => $validated['grade_level'],
        ]);

        return redirect()
            ->route('admin.classes.edit', $class->id)
            ->with('success', 'Class updated successfully!');
    }

    public function moveStudents(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        $validated = $request->validate([
            'target_class_id' => 'nullable|exists:school_classes,id',
        ]);

        Student::where('class_id', $class->id)
            ->update([
                'class_id' => $validated['target_class_id'] ?? null,
            ]);

        return redirect()
            ->route('admin.classes.edit', $class->id)
            ->with('success', 'Students moved successfully. You can delete this class now if it has no students.');
    }

    public function destroy($id)
    {
        $class = SchoolClass::findOrFail($id);

        DB::beginTransaction();

        try {
            $studentCount = Student::where('class_id', $class->id)->count();

            if ($studentCount > 0) {
                DB::rollBack();

                return back()->with(
                    'error',
                    'Cannot delete class because students are still assigned. Move all students first.'
                );
            }

            DB::table('class_teacher')
                ->where('class_id', $class->id)
                ->delete();

            DB::table('attendances')
                ->where('class_id', $class->id)
                ->delete();

            DB::table('scores')
                ->where('class_id', $class->id)
                ->delete();

            $class->delete();

            DB::commit();

            return redirect()
                ->route('admin.classes.index')
                ->with('success', 'Class deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with(
                'error',
                'Delete failed: ' . $e->getMessage()
            );
        }
    }

    public function deleteList()
    {
        $classes = SchoolClass::withCount([
            'students',
            'teachers',
        ])
            ->orderBy('grade_level')
            ->paginate(15);

        return view('admin.class.show', compact('classes'));
    }
}
