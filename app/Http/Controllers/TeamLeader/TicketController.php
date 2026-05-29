<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();

        // 1. Apply Search Filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('idTicket', 'like', "%{$search}%")
                  ->orWhere('namacust', 'like', "%{$search}%")
                  ->orWhere('idlaporan', 'like', "%{$search}%")
                  ->orWhere('topic', 'like', "%{$search}%");
            });
        }

        // 2. Apply Time Filter
        $timeFilter = $request->input('time_filter', 'all');
        $now = \Carbon\Carbon::now();
        if ($timeFilter == 'today') {
            $query->whereDate('created_at', $now->today());
        } elseif ($timeFilter == 'week') {
            $query->where('created_at', '>=', $now->startOfWeek());
        }

        // 3. Calculate Global Stats (Before Pagination)
        $totalTickets = (clone $query)->count();
        $queuedTickets = (clone $query)->where('status', 'QUEUED')->count();
        $assignedTickets = (clone $query)->where('status', 'ASSIGNED')->count();
        $closedTickets = (clone $query)->where('condition', 'Closed')->count();
        $dispatchedTickets = (clone $query)->whereIn('condition', ['Dispatched', 'DISPATCHED'])
            ->orWhereIn('status', ['DISPATCHED', 'Dispatched'])
            ->count();

        // 4. Get Paginated Tickets
        $tickets = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Append query strings so pagination links work with filters
        $tickets->appends($request->all());

        return view('team-leader.tickets', compact(
            'tickets', 'timeFilter', 'totalTickets', 'queuedTickets', 'assignedTickets', 'closedTickets', 'dispatchedTickets'
        ));
    }
}
