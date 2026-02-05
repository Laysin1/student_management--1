<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['class','teacher'])->orderByDesc('id')->paginate(15);
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

            if ($validated['type'] === 'class') {
                \App\Models\Schedule::create([
                    'class_id' => $validated['class_id'],
                    'photo_path' => $photoPath,
                ]);
            } else {
                \App\Models\Schedule::create([
                    'teacher_id' => $validated['teacher_id'],
                    'photo_path' => $photoPath,
                ]);
            }

            return redirect()->route('schedules.index')->with('success', 'Schedule uploaded successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to upload schedule: ' . $e->getMessage()])->withInput();
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

        return redirect()->route('schedules.index')->with('success','Schedule updated.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success','Schedule deleted.');
    }
}
