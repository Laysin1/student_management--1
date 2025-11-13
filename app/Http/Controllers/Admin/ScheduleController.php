<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\SchoolClass; // <- import your class model
use App\Models\User;        // <- import your teacher model

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Get all classes
        $classes = SchoolClass::all();

        // Default selected class
        $selectedClass = $request->class_id ?? $classes->first()->id ?? null;

        // Get schedules for selected class
        $schedules = Schedule::where('class_id', $selectedClass)->get();

        $timeSlots = ['7:00-8:00', '8:00-9:00', '9:00-10:00', '10:00-11:00', '1:00-2:00', '2:00-3:00', '3:00-4:00'];
        $days = ['Mon','Tue','Wed','Thu','Fri','Sat'];

        $scheduleData = [];
        foreach ($timeSlots as $slot) {
            foreach ($days as $day) {
                $schedule = $schedules->where('day', $day)
                                      ->where('time_slot', $slot)
                                      ->first();
                // Show subject with teacher if exists
                $scheduleData[$slot][$day] = $schedule ? $schedule->subject.' ('.$schedule->teacher->name.')' : '';
            }
        }

        return view('admin.schedules.index', compact('scheduleData', 'timeSlots', 'days', 'classes', 'selectedClass'));
    }

    public function create()
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $timeSlots = ['7:00-8:00','8:00-9:00','9:00-10:00','10:00-11:00','1:00-2:00','2:00-3:00','3:00-4:00'];
        $subjects = ['MTH', 'KH', 'P', 'C', 'H', 'I', 'G', 'ENG', 'ES', 'B', 'M'];
        $classes = SchoolClass::all();
        $teachers = User::where('role', 'teacher')->get(); // assuming 'role' column

        return view('admin.schedules.create', compact('days', 'timeSlots', 'subjects', 'classes', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'day' => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat',
            'time_slot' => 'required|string',
            'subject' => 'required|string',
            'class_id' => 'required|exists:school_classes,id',
            'teacher_id' => 'required|exists:users,id',
        ]);

        $existingSchedule = Schedule::where('day', $request->day)
                                    ->where('time_slot', $request->time_slot)
                                    ->where('class_id', $request->class_id)
                                    ->first();

        if ($existingSchedule) {
            return redirect()->back()->with('error', 'Schedule already exists for this time slot!');
        }

        Schedule::create($request->all());

        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully!');
    }

    public function edit(Schedule $schedule)
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $timeSlots = ['7:00-8:00','8:00-9:00','9:00-10:00','10:00-11:00','1:00-2:00','2:00-3:00','3:00-4:00'];
        $subjects = ['MTH', 'KH', 'P', 'C', 'H', 'I', 'G', 'ENG', 'ES', 'B', 'M'];
        $classes = SchoolClass::all();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.schedules.edit', compact('schedule','days','timeSlots','subjects','classes','teachers'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'day' => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat',
            'time_slot' => 'required|string',
            'subject' => 'required|string',
            'class_id' => 'required|exists:school_classes,id',
            'teacher_id' => 'required|exists:users,id',
        ]);

        $existingSchedule = Schedule::where('day', $request->day)
                                    ->where('time_slot', $request->time_slot)
                                    ->where('class_id', $request->class_id)
                                    ->where('id', '!=', $schedule->id)
                                    ->first();

        if ($existingSchedule) {
            return redirect()->back()->with('error', 'Schedule already exists for this time slot!');
        }

        $schedule->update($request->all());

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully!');
    }
}
