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
            'campaign' => [
                'nullable',
                Rule::requiredIf(fn() => in_array($request->role, ['agent', 'team_leader'])),
                Rule::in(array_keys(config('divisions'))),
            ],
            'area' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if (in_array($validated['role'], ['agent', 'team_leader'])) {
            $validated['area'] = strtoupper($validated['campaign']);
        } else {
            $validated['area'] = null;
            $validated['campaign'] = null;
        }

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
            'campaign' => [
                'nullable',
                Rule::requiredIf(fn() => in_array($request->role, ['agent', 'team_leader'])),
                Rule::in(array_keys(config('divisions'))),
            ],
            'site' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspend',
        ]);

        if (in_array($validated['role'], ['agent', 'team_leader'])) {
            $validated['area'] = strtoupper($validated['campaign']);
        } else {
            $validated['area'] = null;
            $validated['campaign'] = null;
        }

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

        // Cegah admin menonaktifkan akun sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        // Kalau user sudah 'inactive', kembalikan pesan info tanpa mengubah apa-apa
        if ($user->status === 'inactive') {
            return redirect()->route('admin.users.index')
                ->with('info', 'User sudah dalam status nonaktif.');
        }

        try {
            $user->update(['status' => 'inactive']);
            return redirect()->route('admin.users.index')
                ->with('success', "User {$user->name} berhasil dinonaktifkan.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('User deactivation failed', [
                'admin_id'       => auth()->id(),
                'target_user_id' => $user->id,
                'error'          => $e->getMessage(),
            ]);
            return redirect()->route('admin.users.index')
                ->with('error', 'Gagal menonaktifkan user. Silakan coba lagi.');
        }
    }
}
