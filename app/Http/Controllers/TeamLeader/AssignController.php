<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Http\Request;

class AssignController extends Controller
{
    public function index()
    {
        // Get only super emergency tickets that haven't been assigned/worked on by any agent
        // Status QUEUED means ticket is in queue and not yet taken by any agent
        $tickets = Ticket::where('status', 'QUEUED')
            ->where(function($query) {
                $query->where('reportedpriority', 'LIKE', '%super%emergency%')
                      ->orWhere('reportedpriority', 'LIKE', '%super emergency%')
                      ->orWhere('reportedpriority', 'LIKE', '%emergency%');
            })
            ->whereNull('assignby')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all active agents (users with role 'agent' and status 'active')
        $agents = User::where('role', 'agent')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('team-leader.assign', compact('tickets', 'agents'));
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id'
        ]);

        $agent = User::find($request->agent_id);

        $ticket->update([
            'assignby' => $agent->name,
            'status' => 'ASSIGNED'
        ]);

        // Broadcast event to the assigned agent
        event(new \App\Events\TicketAssigned($ticket, $agent->id));

        // Send notification to the agent
        $agent->notify(new TicketAssignedNotification($ticket, $agent));

        return redirect()->back()->with('success', 'Ticket successfully assigned to ' . $agent->name);
    }
}
