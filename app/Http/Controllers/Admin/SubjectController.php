<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->paginate(15);
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:subjects,name',
            'code'        => 'nullable|string|max:20|unique:subjects,code',
            'description' => 'nullable|string|max:500',
        ]);

        Subject::create($data);

        return redirect()->route('subjects.index')->with('success', 'Subject created!');
    }

    public function show(Subject $subject)
    {
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:subjects,name,' . $subject->id,
            'code'        => 'nullable|string|max:20|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string|max:500',
        ]);

        $subject->update($data);

        return redirect()->route('subjects.index')->with('success', 'Subject updated!');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted!');
    }
}
