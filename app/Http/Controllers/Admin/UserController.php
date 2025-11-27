<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,team_leader,agent',
            'campaign' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Update the specified user's role
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,team_leader,agent',
            'campaign' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User role updated successfully.');
    }

    /**
     * Update the specified user's status
     */
    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspend',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User status updated successfully.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
