<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $timeFilter = $request->input('time_filter', 'today');


        $cacheKey = "admin_dashboard_stats_{$timeFilter}";

        [$stats, $chartData, $tickets] = Cache::remember($cacheKey, 60, function () use ($timeFilter) {
            $now = Carbon::now();
            $query = Ticket::query();

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

            // Fix P-2: Satu query aggregasi menggantikan ->get() + multiple ->filter()
            $statsRow = (clone $query)->selectRaw("
                COUNT(*) as wo_available,
                SUM(CASE WHEN LOWER(`condition`) = 'in progress' THEN 1 ELSE 0 END) as consume,
                SUM(CASE WHEN LOWER(`condition`) = 'closed'      THEN 1 ELSE 0 END) as closed,
                SUM(CASE WHEN LOWER(statusSC) = 'closed'       THEN 1 ELSE 0 END) as ods
            ")->first();

            $stats = [
                'wo_available' => (int) $statsRow->wo_available,
                'consume' => (int) $statsRow->consume,
                'ods' => (int) $statsRow->ods,
                'closed' => (int) $statsRow->closed,
            ];

            // Chart: Ambil data tiket untuk chart (limit 500, bukan semua)
            // Menggunakan PHP-side grouping agar kompatibel MySQL & SQLite
            $chartTickets = (clone $query)
                ->select(['created_at', 'condition'])
                ->where('created_at', '>=', Carbon::now()->subDays(9)->startOfDay())
                ->orderBy('created_at')
                ->get();

            // Bar Chart: group by day
            $groupedByDay = $chartTickets->groupBy(fn($t) => Carbon::parse($t->created_at)->format('d'));

            $barChartLabels = [];
            $barChartConsume = [];
            $barChartOds = [];
            $barChartClosed = [];

            for ($i = 9; $i >= 0; $i--) {
                $dayLabel = Carbon::now()->subDays($i)->format('d');
                $barChartLabels[] = $dayLabel;
                $dayRows = $groupedByDay[$dayLabel] ?? collect();

                $barChartConsume[] = $dayRows->filter(fn($t) => strcasecmp($t->condition, 'In Progress') === 0)->count();
                $barChartClosed[] = $dayRows->filter(fn($t) => strcasecmp($t->condition, 'Closed') === 0)->count();
                $barChartOds[] = $dayRows->filter(fn($t) => strcasecmp($t->condition, 'Closed') === 0)->count();
            }

            // Line Chart: group by hour (only today's tickets)
            $todayTickets = (clone $query)
                ->select(['created_at'])
                ->whereDate('created_at', Carbon::today())
                ->get();

            // Gunakan format 'G' (tanpa leading zero) agar key konsisten dengan integer $i
            $groupedByHour = $todayTickets->groupBy(fn($t) => (int) Carbon::parse($t->created_at)->format('G'));

            $lineChartLabels = [];
            $lineChartData = [];
            for ($i = 0; $i <= 23; $i++) {
                $lineChartLabels[] = str_pad($i, 2, '0', STR_PAD_LEFT);
                $lineChartData[] = isset($groupedByHour[$i]) ? $groupedByHour[$i]->count() : 0;
            }

            $chartData = [
                'barLabels' => $barChartLabels,
                'barConsume' => $barChartConsume,
                'barOds' => $barChartOds,
                'barClosed' => $barChartClosed,
                'lineLabels' => $lineChartLabels,
                'lineData' => $lineChartData,
            ];

            // Ambil tiket terbaru untuk tabel (limit 50 — bukan semua)
            $tickets = (clone $query)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return [$stats, $chartData, $tickets];
        });

        return view('admin.dashboard', compact('tickets', 'stats', 'timeFilter', 'chartData'));
    }
}
