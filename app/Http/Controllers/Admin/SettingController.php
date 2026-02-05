<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class SettingController extends Controller
{
    // Show the profile page
    public function index()
    {
        $user = auth()->user(); // get current logged-in admin
        return view('admin.setting.index', compact('user'));
    }

    // Update profile
    public function update(Request $request)
    {
        // Validate the input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'contact_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        /** @var User $user */
        $user = auth()->user(); // <- here you get the logged-in admin

        // Update fields
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->contact_number = $request->contact_number;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save(); // ✅ save to database

        return redirect()->route('setting.index')->with('success', 'Profile updated successfully.');
    }
}
