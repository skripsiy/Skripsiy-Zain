<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {


        $timeFilter = $request->input('time_filter', 'today');
        $agentId    = auth()->id();

        // Fix P-2: Cache stats dashboard agent selama 60 detik
        // Menggunakan key dinamis per user agent agar tidak saling tertimpa
        $cacheKey = "agent_dashboard_stats_{$agentId}_{$timeFilter}";

        [$stats, $chartData, $tickets] = Cache::remember($cacheKey, 60, function () use ($timeFilter, $agentId) {
            $now   = Carbon::now();
            $query = Ticket::where('assigned_to_user_id', $agentId);

            switch ($timeFilter) {
                case 'week':
                    $query->where(function ($q) use ($now) {
                        $q->where('created_at', '>=', $now->copy()->startOfWeek())
                          ->orWhere('updated_at', '>=', $now->copy()->startOfWeek());
                    });
                    break;
                case 'month':
                    $query->where(function ($q) use ($now) {
                        $q->where('created_at', '>=', $now->copy()->startOfMonth())
                          ->orWhere('updated_at', '>=', $now->copy()->startOfMonth());
                    });
                    break;
                case 'quarter':
                    $query->where(function ($q) use ($now) {
                        $q->where('created_at', '>=', $now->copy()->startOfQuarter())
                          ->orWhere('updated_at', '>=', $now->copy()->startOfQuarter());
                    });
                    break;
                case 'today':
                default:
                    $query->where(function ($q) use ($now) {
                        $q->whereDate('created_at', $now->today())
                          ->orWhereDate('updated_at', $now->today());
                    });
                    break;
            }

            // Fix P-2: DB Aggregation untuk performa tinggi
            // ODS is defined as tickets that are actually resolved in the field (statusSC is 'closed')
            $statsRow = (clone $query)->selectRaw("
                COUNT(*) as wo_available,
                SUM(CASE WHEN LOWER(`condition`) = 'in progress' THEN 1 ELSE 0 END) as consume,
                SUM(CASE WHEN LOWER(`condition`) IN ('closed', 'saltik') THEN 1 ELSE 0 END) as closed,
                SUM(CASE WHEN LOWER(`condition`) = 'dispatched' THEN 1 ELSE 0 END) as dispatched,
                SUM(CASE WHEN LOWER(statusSC) = 'closed'       THEN 1 ELSE 0 END) as ods
            ")->first();

            $stats = [
                'wo_available' => (int) $statsRow->wo_available,
                'consume'      => (int) $statsRow->consume,
                'closed'       => (int) $statsRow->closed,
                'dispatched'   => (int) $statsRow->dispatched,
                'ods'          => (int) $statsRow->ods,
            ];

            // Chart: Grouping di PHP side (DB-agnostic)
            $chartTickets = (clone $query)
                ->select(['created_at', 'condition', 'statusSC'])
                ->where('created_at', '>=', Carbon::now()->subDays(9)->startOfDay())
                ->orderBy('created_at')
                ->get();

            $groupedByDay = $chartTickets->groupBy(fn($t) => Carbon::parse($t->created_at)->format('d'));

            $barChartLabels     = [];
            $barChartConsume    = [];
            $barChartOds        = [];
            $barChartClosed     = [];
            $barChartDispatched = [];

            for ($i = 9; $i >= 0; $i--) {
                $dayLabel             = Carbon::now()->subDays($i)->format('d');
                $barChartLabels[]     = $dayLabel;
                $dayRows              = $groupedByDay[$dayLabel] ?? collect();

                $barChartConsume[]    = $dayRows->filter(fn($t) => strcasecmp($t->condition, 'In Progress') === 0)->count();
                $barChartClosed[]     = $dayRows->filter(fn($t) => in_array(strtolower($t->condition), ['closed', 'saltik']))->count();
                $barChartOds[]        = $dayRows->filter(fn($t) => strcasecmp($t->statusSC, 'Closed') === 0)->count();
                $barChartDispatched[] = $dayRows->filter(fn($t) => strcasecmp($t->condition, 'Dispatched') === 0)->count();
            }

            // Line Chart: group by hour
            $todayTickets    = (clone $query)
                ->select(['created_at'])
                ->whereDate('created_at', Carbon::today())
                ->get();

            $groupedByHour   = $todayTickets->groupBy(fn($t) => (int) Carbon::parse($t->created_at)->format('G'));

            $lineChartLabels = [];
            $lineChartData   = [];
            for ($i = 0; $i <= 23; $i++) {
                $lineChartLabels[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                $lineChartData[]   = isset($groupedByHour[$i]) ? $groupedByHour[$i]->count() : 0;
            }

            $chartData = [
                'barLabels'          => $barChartLabels,
                'barConsume'         => $barChartConsume,
                'barOds'             => $barChartOds,
                'barClosed'          => $barChartClosed,
                'barDispatched'      => $barChartDispatched,
                'lineLabels'         => $lineChartLabels,
                'lineData'           => $lineChartData,
            ];

            // Limit data tiket terbaru yang di-load ke tabel (max 10 baris untuk dashboard)
            // Fix P-1 & P-2: Eager load relasi assignedTo & batasi kolom yang ditarik dari database
            $tickets = (clone $query)
                ->with(['assignedTo'])
                ->select(['idTicket', 'category_id', 'assigned_to_user_id', 'condition', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return [$stats, $chartData, $tickets];
        });

        return view('agent.dashboard', compact('tickets', 'stats', 'timeFilter', 'chartData'));
    }

    /**
     * AJAX endpoint to filter tickets by date range for Ticket Report table only.
     */
    public function filterTickets(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = \App\Models\Ticket::where('assigned_to_user_id', auth()->id());

        if ($startDate && $endDate) {
            $query->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [
                    \Carbon\Carbon::parse($startDate)->startOfDay(),
                    \Carbon\Carbon::parse($endDate)->endOfDay()
                ])->orWhereBetween('updated_at', [
                    \Carbon\Carbon::parse($startDate)->startOfDay(),
                    \Carbon\Carbon::parse($endDate)->endOfDay()
                ]);
            });
        }

        // Fix P-1: Tambahkan eager loading ->with('assignedTo')
        // Sebelumnya: tiap $ticket->assignedTo?->name trigger 1 query tersendiri (N+1)
        $tickets = $query->with('assignedTo')->orderBy('created_at', 'desc')->get();

        $result = $tickets->map(function ($ticket) {
            return [
                'code' => 'IN' . str_pad($ticket->idTicket, 8, '0', STR_PAD_LEFT),
                'symptomp' => $ticket->topic ?? '-',
                'agent' => $ticket->assignedTo?->name ?? '-',
                'date' => $ticket->created_at->format('Y-m-d'),
                'status' => $ticket->condition,
            ];
        });

        return response()->json($result);
    }
}
