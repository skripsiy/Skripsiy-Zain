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

class ReportController extends Controller
{
    /**
     * Display user reports listing (Data Harian)
     */
    public function index()
    {
        $today = \Carbon\Carbon::today()->toDateString();

        // Get all users with their ticket counts for TODAY
        $users = User::select('users.*')
            ->selectRaw('COUNT(DISTINCT CASE WHEN (tickets.assignby = users.email OR tickets.assignby = users.name) AND DATE(tickets.datereport) = ? THEN tickets.idTicket END) as assigned_tickets', [$today])
            ->selectRaw('COUNT(DISTINCT CASE WHEN (tickets.solvedby = users.email OR tickets.solvedby = users.name) AND DATE(tickets.datesolved) = ? THEN tickets.idTicket END) as solved_tickets', [$today])
            ->selectRaw('COUNT(DISTINCT CASE WHEN (tickets.assignby = users.email OR tickets.assignby = users.name) AND tickets.status = "QUEUED" THEN tickets.idTicket END) as inbox_tickets') // Inbox tetap menghitung yang masih menggantung
            ->leftJoin('tickets', function($join) {
                $join->on('tickets.assignby', '=', 'users.email')
                     ->orOn('tickets.assignby', '=', 'users.name')
                     ->orOn('tickets.solvedby', '=', 'users.email')
                     ->orOn('tickets.solvedby', '=', 'users.name');
            })
            ->groupBy('users.id', 'users.name', 'users.email', 'users.password', 'users.role', 'users.status', 'users.campaign', 'users.area', 'users.site', 'users.username', 'users.phone', 'users.email_verified_at', 'users.remember_token', 'users.created_at', 'users.updated_at')
            ->orderBy('users.name')
            ->get();

        return view('admin.reports.index', compact('users'));
    }

    /**
     * Get user ticket details (Data Harian)
     */
    public function getUserTickets(User $user)
    {
        $today = \Carbon\Carbon::today()->toDateString();

        // Get tickets assigned to user TODAY
        $assignedTickets = Ticket::where(function($q) use ($user) {
                $q->where('assignby', $user->email)->orWhere('assignby', $user->name);
            })
            ->whereDate('datereport', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get tickets solved by user TODAY
        $solvedTickets = Ticket::where(function($q) use ($user) {
                $q->where('solvedby', $user->email)->orWhere('solvedby', $user->name);
            })
            ->whereDate('datesolved', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get inbox tickets (queued tickets assigned to user) - Inbox biasanya semua yang belum selesai
        $inboxTickets = Ticket::where(function($q) use ($user) {
                $q->where('assignby', $user->email)->orWhere('assignby', $user->name);
            })
            ->where('status', 'QUEUED')
            ->orderBy('created_at', 'desc')
            ->get();

        // Count by status for TODAY
        $statusCounts = Ticket::where(function($q) use ($user) {
                $q->where('assignby', $user->email)->orWhere('assignby', $user->name);
            })
            ->whereDate('datereport', $today)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return response()->json([
            'user' => $user,
            'assigned_count' => $assignedTickets->count(),
            'solved_count' => $solvedTickets->count(),
            'inbox_count' => $inboxTickets->count(),
            'status_counts' => $statusCounts,
            'assigned_tickets' => $assignedTickets,
            'solved_tickets' => $solvedTickets,
            'inbox_tickets' => $inboxTickets,
        ]);
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
            'date_to'
        ]);

        return Excel::download(
            new TicketsExport($filters),
            'all_tickets_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Export specific user's tickets to Excel
     */
    public function exportUserTickets(User $user, Request $request)
    {
        $type = $request->get('type', 'assigned'); // 'assigned', 'solved', or 'inbox'

        return Excel::download(
            new UserTicketsExport($user, $type),
            $user->name . '_' . $type . '_tickets_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }
}

