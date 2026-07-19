<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ticket;
use App\Exports\TicketsExport;
use App\Exports\UserReportsExport;
use App\Exports\UserTicketsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display user reports listing (Data Harian)
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $from = $dateFrom ?: Carbon::today()->toDateString();
        $to = $dateTo ?: Carbon::today()->toDateString();

        // Inbox = tickets that entered the agent's queue today (activity range), while solved = resolved/closed tickets in the selected range.
        $users = User::select('users.*')
            ->selectRaw("COUNT(DISTINCT CASE WHEN tickets.assigned_to_user_id = users.id AND tickets.condition NOT IN ('Dispatched', 'Closed') AND ((DATE(tickets.created_at) >= ? AND DATE(tickets.created_at) <= ?) OR (DATE(tickets.updated_at) >= ? AND DATE(tickets.updated_at) <= ?)) THEN tickets.idTicket END) as assigned_tickets", [$from, $to, $from, $to])
            ->selectRaw("COUNT(DISTINCT CASE WHEN tickets.solved_by_user_id = users.id AND DATE(tickets.datesolved) >= ? AND DATE(tickets.datesolved) <= ? THEN tickets.idTicket END) as solved_tickets", [$from, $to])
            ->selectRaw("COUNT(DISTINCT CASE WHEN tickets.assigned_to_user_id = users.id AND ((DATE(tickets.created_at) >= ? AND DATE(tickets.created_at) <= ?) OR (DATE(tickets.updated_at) >= ? AND DATE(tickets.updated_at) <= ?)) THEN tickets.idTicket END) as inbox_tickets", [$from, $to, $from, $to])
            ->leftJoin('tickets', function($join) {
                $join->on('tickets.assigned_to_user_id', '=', 'users.id')
                     ->orOn('tickets.solved_by_user_id', '=', 'users.id');
            })
            ->groupBy('users.id', 'users.name', 'users.email', 'users.password', 'users.role', 'users.status', 'users.campaign', 'users.area', 'users.site', 'users.username', 'users.phone', 'users.email_verified_at', 'users.remember_token', 'users.created_at', 'users.updated_at')
            ->orderBy('users.name')
            ->get();

        return view('admin.reports.index', compact('users'));
    }

    /**
     * Get user ticket details (Data Harian)
     */
    public function getUserTickets(User $user, Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $from = $dateFrom ?: Carbon::today()->toDateString();
        $to = $dateTo ?: Carbon::today()->toDateString();

        $activityRange = function ($query) use ($from, $to) {
            $query->where(function ($range) use ($from, $to) {
                $range->where(function ($createdRange) use ($from, $to) {
                    $createdRange->whereDate('created_at', '>=', $from);
                    $createdRange->whereDate('created_at', '<=', $to);
                })->orWhere(function ($updatedRange) use ($from, $to) {
                    $updatedRange->whereDate('updated_at', '>=', $from);
                    $updatedRange->whereDate('updated_at', '<=', $to);
                });
            });
        };

        $assignedTickets = Ticket::where('assigned_to_user_id', $user->id)
            ->whereNotIn('condition', ['Dispatched', 'Closed'])
            ->where(function ($q) use ($activityRange) {
                $activityRange($q);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $dispatchedTickets = Ticket::where('assigned_to_user_id', $user->id)
            ->where('condition', 'Dispatched')
            ->where(function ($q) use ($activityRange) {
                $activityRange($q);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $solvedTickets = Ticket::where('solved_by_user_id', $user->id)
            ->whereBetween('datesolved', [$from, $to])
            ->orderBy('updated_at', 'desc')
            ->get();

        $inboxTickets = Ticket::where('assigned_to_user_id', $user->id)
            ->where(function ($q) use ($activityRange) {
                $activityRange($q);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $statusCounts = Ticket::where('assigned_to_user_id', $user->id)
            ->where(function ($q) use ($activityRange) {
                $activityRange($q);
            })
            ->select('condition', DB::raw('count(*) as count'))
            ->groupBy('condition')
            ->pluck('count', 'condition')
            ->toArray();

        return response()->json([
            'user' => $user,
            'assigned_count' => $assignedTickets->count(),
            'solved_count' => $solvedTickets->count(),
            'inbox_count' => $inboxTickets->count(),
            'dispatched_count' => $dispatchedTickets->count(),
            'status_counts' => $statusCounts,
            'assigned_tickets' => $assignedTickets,
            'solved_tickets' => $solvedTickets,
            'inbox_tickets' => $inboxTickets,
            'dispatched_tickets' => $dispatchedTickets,
        ]);
    }

    /**
     * Tampilkan laporan tiket menyeluruh dengan filter (F-10)
     */
    public function ticketsReport(Request $request)
    {
        // Ambil semua agent untuk dropdown filter
        $agents = User::where('role', 'agent')->orderBy('name')->get();

        // Filter params
        $dateFrom  = $request->get('date_from');
        $dateTo    = $request->get('date_to');
        $status    = $request->get('status');
        $agentId   = $request->get('agent_id');
        $ticketId  = $request->get('ticket_id');
        $keyword   = $request->get('keyword');

        if (empty($dateFrom) && empty($dateTo)) {
            $dateFrom = Carbon::today()->toDateString();
            $dateTo = Carbon::today()->toDateString();
        }

        $filters = [
            'ticket_id' => $ticketId,
            'keyword' => $keyword,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'status' => $status,
            'agent_id' => $agentId,
        ];
        $applyFilters = function ($query) use ($filters) {
            $query->reportFilter($filters);
        };

        // Query tiket
        $query = Ticket::query();
        $applyFilters($query);

        $tickets = $query->with(['assignedTo', 'solvedBy'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Statistik ringkas dari hasil filter (tanpa paginate)
        $statsQuery = Ticket::query();
        $applyFilters($statsQuery);

        $statusCounts = (clone $statsQuery)
            ->select('condition', DB::raw('count(*) as total'))
            ->groupBy('condition')
            ->pluck('total', 'condition')
            ->toArray();

        // Normalize keys to lowercase to prevent case sensitivity mismatch bugs
        $normalizedCounts = array_change_key_case($statusCounts, CASE_LOWER);

        $totalTickets   = array_sum($statusCounts);
        $closedCount    = $normalizedCounts['closed']   ?? 0;
        $assignedCount  = $normalizedCounts['assigned']  ?? 0;
        $queuedCount    = ($normalizedCounts['queued']   ?? 0) + ($normalizedCounts['unassigned'] ?? 0);

        return view('admin.reports.tickets', compact(
            'tickets', 'agents', 'statusCounts',
            'totalTickets', 'closedCount', 'assignedCount', 'queuedCount',
            'dateFrom', 'dateTo', 'status', 'agentId', 'ticketId', 'keyword'
        ));
    }

    /**
     * Export all user reports to Excel
     */
    public function exportUserReports()
    {
        return Excel::download(
            new UserReportsExport(),
            'user_reports_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Export all tickets to Excel
     */
    public function exportAllTickets(Request $request)
    {
        $filters = $request->only([
            'user_id',
            'status',
            'priority',
            'regional',
            'witel',
            'date_from',
            'date_to',
            'ticket_id',
            'agent_id',
            'keyword',
        ]);

        return Excel::download(
            new TicketsExport($filters),
            'laporan_tiket_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Export specific user's tickets to Excel
     */
    public function exportUserTickets(User $user, Request $request)
    {
        $type = $request->get('type', 'assigned'); // 'assigned', 'solved', 'inbox', or 'dispatched'
        $filters = $request->only(['date_from', 'date_to']);

        return Excel::download(
            new UserTicketsExport($user, $type, $filters),
            $user->name . '_' . $type . '_tickets_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }
}

