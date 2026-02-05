<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::select('id','name','grade_level')->get();

        // Fixed grade labels
        $grades = ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];

        // Group classes by grade_level
        $byGrade = $classes->groupBy('grade_level');

        // Build summary for each fixed grade
        $summary = collect($grades)->map(function ($grade) use ($byGrade) {
            $items = $byGrade->get($grade, collect());
            return [
                'grade_level'   => $grade,
                'classes_count' => $items->count(),
                'sections'      => $items->pluck('name')->values()->all(),
                'first_id'      => optional($items->first())->id,
            ];
        });

        return view('admin.class.index', ['gradeSummary' => $summary]);
    }

    public function create()
    {
        $grades = ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];
        $schedules = Schedule::where('type', 'class')->orderBy('title')->get();
        return view('admin.class.create', compact('grades','schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'schedule_id' => 'nullable|exists:schedules,id',
        ]);

        $class = SchoolClass::create([
            'name'        => $validated['name'],
            'grade_level' => $validated['grade_level'],
        ]);

        // Link schedule to this class
        if (!empty($validated['schedule_id'])) {
            Schedule::where('id', $validated['schedule_id'])->update(['class_id' => $class->id]);
        }

        return redirect()->route('classes.index')->with('success', 'Class added successfully!');
    }

    public function show(SchoolClass $class)
    {
        $students = Student::with(['user','class'])
            ->where('class_id', $class->id)
            ->orderBy('last_name')
            ->paginate(12);

        $classes = SchoolClass::select('id','name','grade_level')
            ->orderBy('grade_level')
            ->get();

        return view('admin.students.index', compact('class','students','classes'));
    }

    public function edit(SchoolClass $class)
    {
        $grades = ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];
        $schedules = Schedule::where('type', 'class')->orderBy('title')->get();
        $class->load('schedules'); // Load current schedules
        return view('admin.class.edit', compact('class','grades','schedules'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'schedule_id' => 'nullable|exists:schedules,id',
        ]);

        $class->update([
            'name'        => $validated['name'],
            'grade_level' => $validated['grade_level'],
        ]);

        // Update schedule link
        if (!empty($validated['schedule_id'])) {
            // Remove old class_id from schedules that were linked
            Schedule::where('class_id', $class->id)->update(['class_id' => null]);
            // Link new schedule
            Schedule::where('id', $validated['schedule_id'])->update(['class_id' => $class->id]);
        }

        return redirect()->route('classes.index')->with('success', 'Class updated successfully!');
    }

    public function destroy(SchoolClass $class)
    {
        // Unlink schedules before deleting
        Schedule::where('class_id', $class->id)->update(['class_id' => null]);
        $class->delete();
        return redirect()->route('classes.index')->with('success', 'Class deleted successfully!');
    }

    public function deleteList()
    {
        $classes = SchoolClass::withCount(['students','teachers'])->orderBy('name')->paginate(15);
        return view('admin.class.show', compact('classes'));
    }
}
