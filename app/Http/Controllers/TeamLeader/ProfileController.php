<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the team leader's profile.
     */
    public function show()
    {
        return view('team-leader.profile', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the team leader's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()->route('team-leader.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the team leader's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()->route('team-leader.profile')->with('success', 'Password updated successfully!');
    }
}
