<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    public function index()
{
    $admins = Admin::all();
    return view('admin.admins.index', compact('admins'));
}

public function create()
{
    return view('admin.admins.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:admins,email',
        'password' => 'required|string|min:6',
    ]);

    Admin::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
}
}
