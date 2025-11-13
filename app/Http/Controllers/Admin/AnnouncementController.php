<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // List all announcements
    public function index()
    {
        return view('admin.announcements.index');
    }

    // Show create form
    public function create()
    {
        return view('admin.announcements.create');
    }

    // Save announcement
    public function store(Request $request)
    {
        // Validation + store logic
        return redirect()->route('announcements.index')->with('success', 'Announcement created!');
    }

    // Show one announcement
    public function show($id)
    {
        return view('admin.announcements.show', compact('id'));
    }

    // Edit form
    public function edit($id)
    {
        return view('admin.announcements.edit', compact('id'));
    }

    // Update announcement
    public function update(Request $request, $id)
    {
        // Update logic
        return redirect()->route('announcements.index')->with('success', 'Announcement updated!');
    }

    // Delete announcement
    public function destroy($id)
    {
        // Delete logic
        return redirect()->route('announcements.index')->with('success', 'Announcement deleted!');
    }
}
