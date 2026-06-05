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

        // Check for CSV Export
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportCsv($query->orderBy('created_at', 'desc')->get());
        }

        // 3. Calculate Global Stats (Before Pagination)
        $totalTickets = (clone $query)->count();
        $queuedTickets = (clone $query)->where('status', 'QUEUED')->count();
        $assignedTickets = (clone $query)->where('status', 'ASSIGNED')->count();
        $closedTickets = (clone $query)->whereIn('condition', ['Closed', 'Saltik'])->count();
        $dispatchedTickets = (clone $query)->where(function($q) {
            $q->whereIn('condition', ['Dispatched', 'DISPATCHED'])
              ->orWhereIn('status', ['DISPATCHED', 'Dispatched']);
        })->count();

        // 4. Get Paginated Tickets
        $tickets = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Append query strings so pagination links work with filters
        $tickets->appends($request->all());

        return view('team-leader.tickets', compact(
            'tickets', 'timeFilter', 'totalTickets', 'queuedTickets', 'assignedTickets', 'closedTickets', 'dispatchedTickets'
        ));
    }

    private function exportCsv($tickets)
    {
        $fileName = 'tickets_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        
        $columns = array(
            'Ticket Code', 'Customer Name', 'Customer Phone', 'Date Report', 
            'Status', 'Condition', 'Assigned To', 'Regional', 'Witel', 
            'Topic', 'Topic Detail', 'No SC', 'Status SC', 'Validate Close'
        );
        
        $callback = function() use($tickets, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns, ';');
            
            foreach ($tickets as $ticket) {
                fputcsv($file, array(
                    'TK' . str_pad($ticket->idTicket, 6, '0', STR_PAD_LEFT),
                    $ticket->namacust ?? '-',
                    $ticket->notelpCust ?? '-',
                    $ticket->datereport ? $ticket->datereport->format('Y-m-d') : '-',
                    $ticket->status,
                    $ticket->condition ?? 'Open',
                    $ticket->assignby ?? '-',
                    $ticket->regional ?? '-',
                    $ticket->witel ?? '-',
                    $ticket->topic ?? '-',
                    $ticket->topicDetail ?? '-',
                    $ticket->noSC ?? '-',
                    $ticket->statusSC ?? '-',
                    $ticket->validateClose ?? '-'
                ), ';');
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
