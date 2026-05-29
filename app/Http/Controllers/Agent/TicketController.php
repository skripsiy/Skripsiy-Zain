<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'active'); // active or today
        $agentName = auth()->user()->name;
        $agentEmail = auth()->user()->email;

        $query = Ticket::query();

        // Search functionality - global search
        if ($request->has('search') && $request->search != '') {
            $search = trim($request->search);
            
            // Jika formatnya IN diikuti angka (contoh: IN00000044), cari spesifik (Exact Match)
            if (preg_match('/^IN\d+$/i', $search)) {
                $exactId = (int) preg_replace('/^IN0*/i', '', $search);
                $query->where('idTicket', $exactId);
            } else {
                // Jika format bebas, cari berdasarkan nama, regional, atau witel secara parsial
                $query->where(function($q) use ($search) {
                    $q->where('namacust', 'like', "%{$search}%")
                      ->orWhere('regional', 'like', "%{$search}%")
                      ->orWhere('witel', 'like', "%{$search}%");
                });
            }
        } else {
            // Agent can only see tickets assigned to them or they've worked on
            $query->where(function($q) use ($agentName, $agentEmail) {
                $q->where('assignby', $agentName)
                  ->orWhere('solvedby', $agentName)
                  ->orWhere('solvedby', $agentEmail);
            });

            // Filter by view type only for assigned list
            if ($view === 'today') {
                // Today Logs: tickets with Closed condition today
                $query->where('condition', 'Closed')
                      ->whereDate('datereport', today());
            } else {
                // Active Ticket: tickets with Open or In Progress condition
                $query->whereIn('condition', ['Open', 'In Progress', 'QUEUED', 'ASSIGNED', 'Dispatched', 'DISPATCHED'])
                      ->orWhereNull('condition');
            }
        }

        $tickets = $query->orderBy('datereport', 'desc')->paginate(10);

        // Calculate stats (only for this agent)
        $totalTickets = Ticket::where('assignby', $agentName)
            ->orWhere('solvedby', $agentName)
            ->orWhere('solvedby', $agentEmail)
            ->count();

        $consumedTickets = Ticket::where('solvedby', $agentName)
            ->orWhere('solvedby', $agentEmail)
            ->count();

        $submittedTickets = Ticket::where('assignby', $agentName)
            ->whereIn('condition', ['QUEUED', 'ASSIGNED', 'Open'])
            ->count();

        $closedTickets = Ticket::where(function($q) use ($agentName, $agentEmail) {
            $q->where('solvedby', $agentName)
              ->orWhere('solvedby', $agentEmail);
        })->where('condition', 'Closed')->count();

        $dispatchedTickets = Ticket::where('assignby', $agentName)
            ->whereIn('condition', ['Dispatched', 'DISPATCHED'])
            ->count();

        return view('agent.tickets', compact('tickets', 'totalTickets', 'consumedTickets', 'submittedTickets', 'closedTickets', 'dispatchedTickets', 'view'));
    }
}
