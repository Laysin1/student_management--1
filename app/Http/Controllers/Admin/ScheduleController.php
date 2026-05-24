<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
{
    $query = Schedule::with(['class', 'teacher.subject']);

    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhereHas('class', function ($cq) use ($search) {
                  $cq->where('name', 'like', "%{$search}%")
                     ->orWhere('grade_level', 'like', "%{$search}%");
              })
              ->orWhereHas('teacher', function ($tq) use ($search) {
                  $tq->where('first_name', 'like', "%{$search}%")
                     ->orWhere('last_name', 'like', "%{$search}%");
              })
              ->orWhereHas('teacher.subject', function ($sq) use ($search) {
                  $sq->where('name', 'like', "%{$search}%");
              });
        });
    }

    if ($request->get('type') === 'class') {
        $query->whereNotNull('class_id');
    }

    if ($request->get('type') === 'teacher') {
        $query->whereNotNull('teacher_id');
    }

    $schedules = $query
        ->orderByDesc('id')
        ->paginate(15)
        ->withQueryString();

    return view('admin.schedules.index', compact('schedules'));
}

    public function create()
    {
        $classes = SchoolClass::orderBy('grade_level')->get();
        $teachers = Teacher::with('subject')->orderBy('first_name')->get();
        return view('admin.schedules.create', compact('classes','teachers'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'nullable|string|max:150',
        'type' => 'required|in:class,teacher',
        'class_id' => 'required_if:type,class|nullable|exists:school_classes,id',
        'teacher_id' => 'required_if:type,teacher|nullable|exists:teachers,id',
        'photo' => 'required|image|mimes:jpeg,png,jpg|max:4096',
    ]);

    try {
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('schedules', 'public');
        }

        Schedule::create([
            'title' => $validated['title'] ?? null,
            'type' => $validated['type'],
            'class_id' => $validated['type'] === 'class' ? $validated['class_id'] : null,
            'teacher_id' => $validated['type'] === 'teacher' ? $validated['teacher_id'] : null,
            'photo_path' => $photoPath,
        ]);

        return redirect()
    ->route('admin.schedules.index', [
        'type' => $validated['type']
    ])
    ->with('success', 'Schedule uploaded successfully!');

    } catch (\Exception $e) {
        return back()
            ->withErrors(['error' => 'Failed to upload schedule: ' . $e->getMessage()])
            ->withInput();
    }
}

    public function show(\App\Models\Schedule $schedule)
    {
        $schedule->load(['teacher','class']);
        return view('admin.schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $classes = SchoolClass::orderBy('grade_level')->get();
        $teachers = Teacher::with('subject')->orderBy('first_name')->get();
        return view('admin.schedules.edit', compact('schedule','classes','teachers'));
    }

    public function update(Request $r, Schedule $schedule)
    {
        $data = $r->validate([
            'title'      => 'nullable|string|max:150',
            'type'       => 'required|in:class,teacher',
            'class_id'   => 'nullable|exists:school_classes,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'photo'      => 'nullable|image|max:4096',
        ]);

        if ($data['type'] === 'class' && empty($data['class_id'])) {
            return back()->withErrors(['class_id' => 'Class is required for class schedule'])->withInput();
        }
        if ($data['type'] === 'teacher' && empty($data['teacher_id'])) {
            return back()->withErrors(['teacher_id' => 'Teacher is required for teacher schedule'])->withInput();
        }

        $schedule->title = $data['title'] ?? null;
        $schedule->type = $data['type'];
        $schedule->class_id = $data['type'] === 'class' ? ($data['class_id'] ?? null) : null;
        $schedule->teacher_id = $data['type'] === 'teacher' ? ($data['teacher_id'] ?? null) : null;

        if ($r->hasFile('photo')) {
            $schedule->photo_path = $r->file('photo')->store('schedule-photos', 'public');
        }

        $schedule->save();

        return redirect()
    ->route('admin.schedules.index', [
        'type' => $schedule->type
    ])
    ->with('success','Schedule updated.');
    }

    public function destroy(Schedule $schedule)
{
    $type = $schedule->type;

    $schedule->delete();

    return redirect()
        ->route('admin.schedules.index', [
            'type' => $type
        ])
        ->with('success', 'Schedule deleted.');
}
}
