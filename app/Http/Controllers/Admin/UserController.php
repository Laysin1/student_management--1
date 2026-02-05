<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Display all users
    public function index()
    {
        $users = User::all(); // fetch all users
        return view('admin.users.index', compact('users'));
    }

    // Show form to create a new user
    public function create()
    {
        return view('admin.users.create');
    }

    // Store a new user
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|string', // Add this line if you want to select role
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password), // Always hash!
        'role' => $request->role, // Set role here
    ]);

    return redirect()->route('users.index')->with('success', 'User created successfully.');
}

    // Show form to edit an existing user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Update an existing user
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => "required|email|unique:users,email,{$id}",
        'password' => 'nullable|string|min:6|confirmed',
        'role' => 'required|string', // Add this line if you want to select role
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role; // Set role here

    if ($request->password) {
        $user->password = bcrypt($request->password); // Always hash!
    }

    $user->save();

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
