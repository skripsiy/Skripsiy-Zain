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
    public function index()
    {
        $today = \Carbon\Carbon::today()->toDateString();

        // Get all users with their ticket counts for TODAY
        $users = User::select('users.*')
            ->selectRaw('COUNT(DISTINCT CASE WHEN tickets.assigned_to_user_id = users.id AND DATE(tickets.datereport) = ? THEN tickets.idTicket END) as assigned_tickets', [$today])
            ->selectRaw('COUNT(DISTINCT CASE WHEN tickets.solved_by_user_id = users.id AND DATE(tickets.datesolved) = ? THEN tickets.idTicket END) as solved_tickets', [$today])
            ->selectRaw('COUNT(DISTINCT CASE WHEN tickets.assigned_to_user_id = users.id AND tickets.status = "QUEUED" THEN tickets.idTicket END) as inbox_tickets')
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
    public function getUserTickets(User $user)
    {
        $today = \Carbon\Carbon::today()->toDateString();

        // Get tickets assigned to user TODAY
        $assignedTickets = Ticket::where('assigned_to_user_id', $user->id)
            ->whereDate('datereport', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get tickets solved by user TODAY
        $solvedTickets = Ticket::where('solved_by_user_id', $user->id)
            ->whereDate('datesolved', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get inbox tickets (queued tickets assigned to user) - Inbox biasanya semua yang belum selesai
        $inboxTickets = Ticket::where('assigned_to_user_id', $user->id)
            ->where('status', 'QUEUED')
            ->orderBy('created_at', 'desc')
            ->get();

        // Count by status for TODAY
        $statusCounts = Ticket::where('assigned_to_user_id', $user->id)
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

        // Query tiket
        $query = Ticket::query();

        // Filter ticket ID
        if ($ticketId) {
            $query->where('idTicket', 'like', "%{$ticketId}%");
        }

        // Filter keyword (F-10)
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                foreach (['idTicket', 'idlaporan', 'detailticket', 'resume', 'description', 'noSC'] as $col) {
                    $q->orWhere('tickets.' . $col, 'like', "%{$keyword}%");
                }
                $q->orWhereHas('customer', function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', "%{$keyword}%")
                             ->orWhere('phone_number', 'like', "%{$keyword}%");
                });
                $q->orWhereHas('escalations', function ($subQuery) use ($keyword) {
                    $subQuery->where('escalated_to', 'like', "%{$keyword}%")
                             ->orWhere('escalated_via', 'like', "%{$keyword}%")
                             ->orWhere('contact', 'like', "%{$keyword}%")
                             ->orWhere('status', 'like', "%{$keyword}%");
                });
                $q->orWhereHas('category', function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', "%{$keyword}%")
                             ->orWhereHas('parent', function ($p1) use ($keyword) {
                                 $p1->where('name', 'like', "%{$keyword}%")
                                    ->orWhereHas('parent', function ($p2) use ($keyword) {
                                        $p2->where('name', 'like', "%{$keyword}%")
                                           ->orWhereHas('parent', function ($p3) use ($keyword) {
                                               $p3->where('name', 'like', "%{$keyword}%");
                                           });
                                    });
                             });
                });
                $q->orWhereHas('witelRelation', function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', "%{$keyword}%")
                             ->orWhereHas('area', function ($a) use ($keyword) {
                                 $a->where('name', 'like', "%{$keyword}%");
                             });
                });
            });
        }

        // Filter tanggal masuk (datereport)
        if ($dateFrom) {
            $query->whereDate('datereport', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('datereport', '<=', $dateTo);
        }

        // Filter status
        if ($status) {
            if ($status === 'QUEUED') {
                // QUEUED dan UNASSIGNED dianggap sama
                $query->whereIn('condition', ['QUEUED', 'UNASSIGNED']);
            } else {
                $query->where('condition', $status);
            }
        }

        // Filter agent
        if ($agentId) {
            $query->where(function ($q) use ($agentId) {
                $q->where('assigned_to_user_id', $agentId)
                  ->orWhere('solved_by_user_id', $agentId);
            });
        }

        $tickets = $query->with(['assignedTo'])
            ->orderBy('datereport', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Statistik ringkas dari hasil filter (tanpa paginate)
        $statsQuery = Ticket::query();
        if ($ticketId)  $statsQuery->where('idTicket', 'like', "%{$ticketId}%");
        if ($keyword) {
            $statsQuery->where(function ($q) use ($keyword) {
                foreach (['idTicket', 'idlaporan', 'detailticket', 'resume', 'description', 'noSC'] as $col) {
                    $q->orWhere('tickets.' . $col, 'like', "%{$keyword}%");
                }
                $q->orWhereHas('customer', function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', "%{$keyword}%")
                             ->orWhere('phone_number', 'like', "%{$keyword}%");
                });
                $q->orWhereHas('escalations', function ($subQuery) use ($keyword) {
                    $subQuery->where('escalated_to', 'like', "%{$keyword}%")
                             ->orWhere('escalated_via', 'like', "%{$keyword}%")
                             ->orWhere('contact', 'like', "%{$keyword}%")
                             ->orWhere('status', 'like', "%{$keyword}%");
                });
                $q->orWhereHas('category', function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', "%{$keyword}%")
                             ->orWhereHas('parent', function ($p1) use ($keyword) {
                                 $p1->where('name', 'like', "%{$keyword}%")
                                    ->orWhereHas('parent', function ($p2) use ($keyword) {
                                        $p2->where('name', 'like', "%{$keyword}%")
                                           ->orWhereHas('parent', function ($p3) use ($keyword) {
                                               $p3->where('name', 'like', "%{$keyword}%");
                                           });
                                    });
                             });
                });
                $q->orWhereHas('witelRelation', function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', "%{$keyword}%")
                             ->orWhereHas('area', function ($a) use ($keyword) {
                                 $a->where('name', 'like', "%{$keyword}%");
                             });
                });
            });
        }
        if ($dateFrom)  $statsQuery->whereDate('datereport', '>=', $dateFrom);
        if ($dateTo)    $statsQuery->whereDate('datereport', '<=', $dateTo);
        if ($status) {
            if ($status === 'QUEUED') {
                $statsQuery->whereIn('condition', ['QUEUED', 'UNASSIGNED']);
            } else {
                $statsQuery->where('condition', $status);
            }
        }
        if ($agentId) {
            $statsQuery->where(function ($q) use ($agentId) {
                $q->where('assigned_to_user_id', $agentId)
                  ->orWhere('solved_by_user_id', $agentId);
            });
        }

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
            'ticket_id'
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
        $type = $request->get('type', 'assigned'); // 'assigned', 'solved', or 'inbox'

        return Excel::download(
            new UserTicketsExport($user, $type),
            $user->name . '_' . $type . '_tickets_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }
}

