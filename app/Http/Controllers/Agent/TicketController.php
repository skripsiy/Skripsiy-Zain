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
        
        $query = Ticket::query();
        
        // Filter by view type
        if ($view === 'today') {
            // Today Logs: tickets with Closed condition today
            $query->where('condition', 'Closed')
                  ->whereDate('datereport', today());
        } else {
            // Active Ticket: tickets with Open or In Progress condition
            $query->whereIn('condition', ['Open', 'In Progress'])
                  ->orWhereNull('condition');
        }
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('idTicket', 'like', "%{$search}%")
                  ->orWhere('namacust', 'like', "%{$search}%")
                  ->orWhere('regional', 'like', "%{$search}%")
                  ->orWhere('witel', 'like', "%{$search}%");
            });
        }
        
        $tickets = $query->orderBy('datereport', 'desc')->paginate(10);
        
        // Calculate stats
        $totalTickets = Ticket::count();
        $consumedTickets = Ticket::whereNotNull('datesolved')->count();
        $submittedTickets = Ticket::where('status', 'QUEUED')->count();
        $closedTickets = Ticket::where('condition', 'Closed')->count();
        
        return view('agent.tickets', compact('tickets', 'totalTickets', 'consumedTickets', 'submittedTickets', 'closedTickets', 'view'));
    }
}
