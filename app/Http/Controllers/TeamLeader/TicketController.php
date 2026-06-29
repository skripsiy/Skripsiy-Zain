<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Exports\TicketsExport;
use Maatwebsite\Excel\Facades\Excel;

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

        // 2. Apply Time Filter & Date Range Filter
        $timeFilter = $request->input('time_filter', 'all');
        $now = \Carbon\Carbon::now();
        
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $timeFilter = 'custom';
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
        } else {
            if ($timeFilter == 'today') {
                $query->whereDate('created_at', $now->today());
            } elseif ($timeFilter == 'week') {
                $query->where('created_at', '>=', $now->startOfWeek());
            }
        }

        // Check for Excel Export
        if ($request->has('export') && ($request->export === 'excel' || $request->export === 'csv')) {
            $tickets = $query->orderBy('created_at', 'desc')->get();
            return Excel::download(
                new TicketsExport([], $tickets),
                'tickets_report_' . now()->format('Y-m-d_His') . '.xlsx'
            );
        }

        // Fix P-2: Gunakan DB aggregation untuk mengambil seluruh data statistik sekaligus
        // Sebelumnya: memicu 5 query COUNT terpisah
        $statsRow = (clone $query)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'QUEUED' THEN 1 ELSE 0 END) as queued,
                SUM(CASE WHEN status = 'ASSIGNED' THEN 1 ELSE 0 END) as assigned,
                SUM(CASE WHEN LOWER(`condition`) IN ('closed', 'saltik') THEN 1 ELSE 0 END) as closed,
                SUM(CASE WHEN LOWER(`condition`) IN ('dispatched', 'dispatched') OR LOWER(status) IN ('dispatched', 'dispatched') THEN 1 ELSE 0 END) as dispatched
            ")
            ->first();

        $totalTickets      = (int) ($statsRow->total ?? 0);
        $queuedTickets     = (int) ($statsRow->queued ?? 0);
        $assignedTickets   = (int) ($statsRow->assigned ?? 0);
        $closedTickets     = (int) ($statsRow->closed ?? 0);
        $dispatchedTickets = (int) ($statsRow->dispatched ?? 0);

        // 4. Get Paginated Tickets
        // Fix P-1: Tambahkan eager loading untuk relasi assignedTo
        $tickets = $query->with(['assignedTo'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Append query strings so pagination links work with filters
        $tickets->appends($request->all());

        return view('team-leader.tickets', compact(
            'tickets', 'timeFilter', 'totalTickets', 'queuedTickets', 'assignedTickets', 'closedTickets', 'dispatchedTickets'
        ));
    }
}
