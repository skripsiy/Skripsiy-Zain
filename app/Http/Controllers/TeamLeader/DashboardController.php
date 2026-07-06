<?php

namespace App\Http\Controllers\TeamLeader;

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
        $search     = $request->input('search');

        $cacheKey = 'tl_dashboard_stats_' . md5($timeFilter . '_' . $search);

        [$stats, $chartData, $tickets] = Cache::remember($cacheKey, 60, function () use ($timeFilter, $search) {
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
                    $query->whereDate('created_at', $now->copy()->toDateString());
                    break;
            }

            // Filter Pencarian
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('idTicket', 'like', "%{$search}%")
                      ->orWhere('namacust', 'like', "%{$search}%")
                      ->orWhere('idlaporan', 'like', "%{$search}%")
                      ->orWhere('topic', 'like', "%{$search}%")
                      ->orWhereHas('assignedTo', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Fix P-2: DB Aggregation menggantikan pemrosesan di PHP-side collection
            $statsRow = (clone $query)->selectRaw("
                COUNT(*) as wo_available,
                SUM(CASE WHEN LOWER(`condition`) = 'in progress' THEN 1 ELSE 0 END) as consume,
                SUM(CASE WHEN LOWER(`condition`) = 'closed'      THEN 1 ELSE 0 END) as closed,
                SUM(CASE WHEN LOWER(`condition`) = 'dispatched'  THEN 1 ELSE 0 END) as dispatched,
                SUM(CASE WHEN LOWER(`condition`) = 'saltik'      THEN 1 ELSE 0 END) as saltik
            ")->first();

            $stats = [
                'wo_available' => (int) $statsRow->wo_available,
                'consume'      => (int) $statsRow->consume,
                'closed'       => (int) $statsRow->closed,
                'dispatched'   => (int) $statsRow->dispatched,
                'saltik'       => (int) $statsRow->saltik,
                'ods'          => (int) $statsRow->closed,
            ];

            // Chart: Grouping di PHP side agar kompatibel MySQL dan SQLite (testing)
            $chartTickets = (clone $query)
                ->select(['created_at', 'condition'])
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
                $barChartOds[]        = $dayRows->filter(fn($t) => strcasecmp($t->condition, 'Closed') === 0)->count();
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

            return [$stats, $chartData, $tickets];
        });

        return view('team-leader.dashboard', compact('tickets', 'stats', 'timeFilter', 'chartData'));
    }
}
