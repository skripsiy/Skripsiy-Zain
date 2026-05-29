<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ticket;

class TicketPolicy
{
    /**
     * Determine whether the user can view the ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // Admin and team leader can view all tickets
        if ($user->role === 'admin' || $user->role === 'team_leader') {
            return true;
        }

        // Agent can only view tickets assigned to them
        return $ticket->assigned_to == $user->id;
    }

    /**
     * Determine whether the user can update the ticket.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Admin and team leader can update all tickets
        if ($user->role === 'admin' || $user->role === 'team_leader') {
            return true;
        }

        // Agent can only update tickets assigned to them
        return $ticket->assigned_to == $user->id;
    }

    /**
     * Determine whether the user can delete the ticket.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->role === 'admin';
    }
}
