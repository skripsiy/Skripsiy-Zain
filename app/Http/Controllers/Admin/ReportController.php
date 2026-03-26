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
     * Display user reports listing
     */
    public function index()
    {
        // Get all users with their ticket counts
        $users = User::select('users.*')
            ->selectRaw('COUNT(DISTINCT CASE WHEN tickets.assignby = users.email THEN tickets.idTicket END) as assigned_tickets')
            ->selectRaw('COUNT(DISTINCT CASE WHEN tickets.solvedby = users.email THEN tickets.idTicket END) as solved_tickets')
            ->selectRaw('COUNT(DISTINCT CASE WHEN tickets.assignby = users.email AND tickets.status = "QUEUED" THEN tickets.idTicket END) as inbox_tickets')
            ->leftJoin('tickets', function($join) {
                $join->on('tickets.assignby', '=', 'users.email')
                     ->orOn('tickets.solvedby', '=', 'users.email');
            })
            ->groupBy('users.id', 'users.name', 'users.email', 'users.password', 'users.role', 'users.status', 'users.campaign', 'users.area', 'users.site', 'users.username', 'users.phone', 'users.email_verified_at', 'users.remember_token', 'users.created_at', 'users.updated_at')
            ->orderBy('users.name')
            ->get();

        return view('admin.reports.index', compact('users'));
    }

    /**
     * Get user ticket details
     */
    public function getUserTickets(User $user)
    {
        // Get tickets assigned to user
        $assignedTickets = Ticket::where('assignby', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get tickets solved by user
        $solvedTickets = Ticket::where('solvedby', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get inbox tickets (queued tickets assigned to user)
        $inboxTickets = Ticket::where('assignby', $user->email)
            ->where('status', 'QUEUED')
            ->orderBy('created_at', 'desc')
            ->get();

        // Count by status
        $statusCounts = Ticket::where('assignby', $user->email)
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

