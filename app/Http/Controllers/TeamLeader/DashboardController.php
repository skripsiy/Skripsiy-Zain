<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\AgentWorkSession;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $timeFilter = $request->input('time_filter', 'today');
        $search     = $request->input('search');

        $agentIds = User::where('role', 'agent')->pluck('id');

        $cacheKey = 'tl_dashboard_stats_' . md5($timeFilter . '_' . $search . '_' . Carbon::now()->toDateString());

        [$stats, $chartData, $tickets, $aht, $achievement] = Cache::remember($cacheKey, 10, function () use ($timeFilter, $search, $agentIds) {
            $query = Ticket::query();
            $now   = Carbon::now();

            switch ($timeFilter) {
                case 'week':
                    $query->where('created_at', '>=', $now->copy()->startOfWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', $now->copy()->startOfMonth());
                    break;
                case 'quarter':
                    $query->where('created_at', '>=', $now->copy()->startOfQuarter());
                    break;
                case 'today':
                default:
                    $query->where(function ($q) use ($now) {
                        $q->whereDate('tickets.created_at', $now->toDateString())
                          ->orWhereDate('tickets.updated_at', $now->toDateString())
                          ->orWhereDate('tickets.datereport', $now->toDateString())
                          ->orWhereDate('tickets.datesolved', $now->toDateString());
                    });
                    break;
            }

            // Filter Pencarian
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('tickets.idTicket', 'like', "%{$search}%")
                      ->orWhere('tickets.idlaporan', 'like', "%{$search}%")
                      ->orWhereHas('customer', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('assignedTo', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('category', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%")
                               ->orWhereHas('parent', function ($p1) use ($search) {
                                   $p1->where('name', 'like', "%{$search}%")
                                      ->orWhereHas('parent', function ($p2) use ($search) {
                                          $p2->where('name', 'like', "%{$search}%");
                                      });
                               });
                      });
                });
            }

            // ODS is defined as tickets that are actually resolved in the field (statusSC is 'closed')
            $statsRow = (clone $query)->selectRaw("
                COUNT(*) as wo_available,
                SUM(CASE WHEN LOWER(COALESCE(`condition`, status)) IN ('in progress', 'in-progress', 'assigned') THEN 1 ELSE 0 END) as consume,
                SUM(CASE WHEN LOWER(COALESCE(`condition`, status)) IN ('closed', 'closed') THEN 1 ELSE 0 END) as closed,
                SUM(CASE WHEN LOWER(COALESCE(`condition`, status)) IN ('dispatched', 'dispatched') THEN 1 ELSE 0 END) as dispatched,
                SUM(CASE WHEN LOWER(COALESCE(`condition`, status)) = 'saltik' THEN 1 ELSE 0 END) as saltik,
                SUM(CASE WHEN LOWER(statusSC) = 'closed' THEN 1 ELSE 0 END) as ods,
                SUM(CASE WHEN LOWER(COALESCE(`condition`, status)) IN ('in progress', 'in-progress', 'assigned') AND (LOWER(division_target) = 'besfixed' OR division_target IS NULL) THEN 1 ELSE 0 END) as besfixed_consume,
                SUM(CASE WHEN LOWER(COALESCE(`condition`, status)) = 'saltik' OR (LOWER(COALESCE(`condition`, status)) IN ('in progress', 'in-progress', 'assigned') AND LOWER(division_target) = 'saltik') THEN 1 ELSE 0 END) as saltik_consume
            ")->first();

            $stats = [
                'wo_available' => (int) $statsRow->wo_available,
                'consume'      => (int) $statsRow->consume,
                'closed'       => (int) $statsRow->closed,
                'dispatched'   => (int) $statsRow->dispatched,
                'saltik'       => (int) $statsRow->saltik,
                'ods'          => (int) $statsRow->ods,
            ];

            $totalConsume  = (int) $statsRow->consume;
            $besfixedCount = (int) $statsRow->besfixed_consume;
            $saltikCount   = (int) $statsRow->saltik_consume;

            $achievement = [
                'besfixed_count' => $besfixedCount,
                'besfixed_pct'   => $totalConsume > 0 ? min(100, round(($besfixedCount / $totalConsume) * 100)) : 0,
                'saltik_count'   => $saltikCount,
                'saltik_pct'     => $totalConsume > 0 ? min(100, round(($saltikCount / $totalConsume) * 100)) : 0,
            ];

            // AHT (menit) dari datereport -> datesolved, tiket yang di-solve para agent
            $ahtBase = Ticket::whereIn('solved_by_user_id', $agentIds)
                ->whereNotNull('datereport')->whereNotNull('datesolved');
            $diffExpr = DB::getDriverName() === 'sqlite'
                ? "(julianday(datesolved) - julianday(datereport)) * 24 * 60"
                : "TIMESTAMPDIFF(MINUTE, datereport, datesolved)";
            $ahtPeriod = (clone $ahtBase);
            switch ($timeFilter) {
                case 'week':    $ahtPeriod->where('datesolved', '>=', Carbon::now()->startOfWeek()); break;
                case 'month':   $ahtPeriod->where('datesolved', '>=', Carbon::now()->startOfMonth()); break;
                case 'quarter': $ahtPeriod->where('datesolved', '>=', Carbon::now()->startOfQuarter()); break;
                default:        $ahtPeriod->whereDate('datesolved', Carbon::today()); break;
            }
            $lastSolved = (clone $ahtBase)->orderByDesc('datesolved')->first();
            $aht = [
                'period' => round((float) ((clone $ahtPeriod)->selectRaw("AVG($diffExpr) as v")->value('v') ?? 0), 1),
                'last'   => $lastSolved
                    ? round(Carbon::parse($lastSolved->datereport)->diffInMinutes(Carbon::parse($lastSolved->datesolved)), 1)
                    : 0,
            ];

            // Chart: Grouping di PHP side agar kompatibel MySQL dan SQLite (testing)
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
            $barChartSaltik     = [];

            for ($i = 9; $i >= 0; $i--) {
                $dayLabel             = Carbon::now()->subDays($i)->format('d');
                $barChartLabels[]     = $dayLabel;
                $dayRows              = $groupedByDay[$dayLabel] ?? collect();

                $barChartConsume[]    = $dayRows->filter(fn($t) => strcasecmp($t->condition, 'In Progress') === 0)->count();
                $barChartClosed[]     = $dayRows->filter(fn($t) => strcasecmp($t->condition, 'Closed') === 0)->count();
                $barChartOds[]        = $dayRows->filter(fn($t) => strcasecmp($t->statusSC, 'Closed') === 0)->count();
                $barChartDispatched[] = $dayRows->filter(fn($t) => strcasecmp($t->condition, 'Dispatched') === 0)->count();
                $barChartSaltik[]     = $dayRows->filter(fn($t) => strcasecmp($t->condition, 'Saltik') === 0)->count();
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
                'barSaltik'          => $barChartSaltik,
                'lineLabels'         => $lineChartLabels,
                'lineData'           => $lineChartData,
            ];

            // Limit data tiket terbaru yang di-load ke tabel (max 50 baris)
            $tickets = (clone $query)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return [$stats, $chartData, $tickets, $aht, $achievement];
        });

        // Total AUX/Online (jam) - AGREGAT sesi kerja seluruh agent HARI INI (di luar cache)
        $sessions = AgentWorkSession::whereIn('user_id', $agentIds)
            ->where('work_date', today())->get();
        $onlineSeconds = 0; $auxSeconds = 0;
        foreach ($sessions as $s) {
            $online = $s->total_online_seconds ?? 0;
            if ($s->status === 'online' && $s->current_session_start) {
                $online += Carbon::parse($s->current_session_start)->diffInSeconds(now());
            }
            $onlineSeconds += $online;
            $auxSeconds    += $s->total_aux_seconds ?? 0;
        }
        $workStats = [
            'online_hours' => round($onlineSeconds / 3600, 1),
            'aux_hours'    => round($auxSeconds / 3600, 1),
        ];

        return view('team-leader.dashboard', compact('tickets', 'stats', 'timeFilter', 'chartData', 'aht', 'workStats', 'achievement'));
    }
}
