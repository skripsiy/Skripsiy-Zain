<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        Gate::authorize('create', User::class);

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
     * Update the specified user's role and status
     */
    public function updateRole(Request $request, User $user)
    {
        Gate::authorize('updateRole', $user);

        $validated = $request->validate([
            'role' => 'required|in:admin,team_leader,agent',
            'campaign' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspend',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User profile and status updated successfully.');
    }

    /**
     * Update the specified user's status
     */
    public function updateStatus(Request $request, User $user)
    {
        Gate::authorize('updateStatus', $user);

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
        Gate::authorize('delete', $user);

        // UC-12: Prevent admin from deleting themselves
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Security Alert: You cannot delete your own admin account.');
        }

        // UC-12: Prevent deleting users that have tickets assigned
        $hasTickets = \App\Models\Ticket::where(function($q) use ($user) {
                $q->where('assignby', $user->email)->orWhere('assignby', $user->name);
            })
            ->orWhere(function($q) use ($user) {
                $q->where('solvedby', $user->email)->orWhere('solvedby', $user->name);
            })
            ->exists();

        if ($hasTickets) {
            return redirect()->route('admin.users.index')
                ->with('error', "Cannot delete {$user->name} because they have associated tickets in the database.");
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
