<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AgentWorkSession;

class AgentWorkSessionPolicy
{
    /**
     * Determine whether the user can view any work sessions.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'team_leader']);
    }

    /**
     * Determine whether the user can view the work session.
     */
    public function view(User $user, AgentWorkSession $workSession): bool
    {
        // Admin and team leader can view all
        if ($user->role === 'admin' || $user->role === 'team_leader') {
            return true;
        }

        // Agent can only view their own work session
        return $workSession->user_id == $user->id;
    }

    /**
     * Determine whether the user can create work sessions.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the work session.
     */
    public function update(User $user, AgentWorkSession $workSession): bool
    {
        return $user->role === 'admin' || $workSession->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the work session.
     */
    public function delete(User $user, AgentWorkSession $workSession): bool
    {
        return $user->role === 'admin';
    }
}
