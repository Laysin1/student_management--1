<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // List all subjects
    public function index()
    {
        return view('admin.subjects.index');
    }

    // Show form to add subject
    public function create()
    {
        return view('admin.subjects.create');
    }

    // Store subject
    public function store(Request $request)
    {
        // Validation + save logic
        return redirect()->route('subjects.index')->with('success', 'Subject created!');
    }

    // Show specific subject
    public function show($id)
    {
        return view('admin.subjects.show', compact('id'));
    }

    // Edit subject
    public function edit($id)
    {
        return view('admin.subjects.edit', compact('id'));
    }

    // Update subject
    public function update(Request $request, $id)
    {
        // Update logic
        return redirect()->route('subjects.index')->with('success', 'Subject updated!');
    }

    // Delete subject
    public function destroy($id)
    {
        // Delete logic
        return redirect()->route('subjects.index')->with('success', 'Subject deleted!');
    }
}
