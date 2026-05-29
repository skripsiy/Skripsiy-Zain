<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $timeFilter = $request->input('time_filter', 'today');

        $query = \App\Models\Ticket::query();

        $now = \Carbon\Carbon::now();
        switch ($timeFilter) {
            case 'week':
                $query->where('created_at', '>=', $now->startOfWeek());
                break;
            case 'month':
                $query->where('created_at', '>=', $now->startOfMonth());
                break;
            case 'quarter':
                $query->where('created_at', '>=', $now->startOfQuarter());
                break;
            case 'today':
            default:
                $query->whereDate('created_at', $now->today());
                break;
        }

        // Apply Search Filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('idTicket', 'like', "%{$search}%")
                  ->orWhere('namacust', 'like', "%{$search}%")
                  ->orWhere('idlaporan', 'like', "%{$search}%")
                  ->orWhere('topic', 'like', "%{$search}%")
                  ->orWhere('assignby', 'like', "%{$search}%");
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        $woAvailable = $tickets->count();
        $consume = $tickets->where('condition', 'In Progress')->count();
        $closed = $tickets->where('condition', 'Closed')->count();
        $ods = $tickets->where('condition', 'Closed')->count(); // Assuming ODS is similar to Closed for now

        $stats = [
            'wo_available' => $woAvailable,
            'consume' => $consume,
            'ods' => $ods,
            'closed' => $closed,
        ];

        // Prepare Chart Data
        $barChartLabels = [];
        $barChartConsume = [];
        $barChartOds = [];
        $barChartClosed = [];
        $lineChartLabels = [];
        $lineChartData = [];

        // Group by Day for Bar Chart (Last 10 days)
        $groupedByDay = $tickets->groupBy(function($ticket) {
            return \Carbon\Carbon::parse($ticket->created_at)->format('d');
        });
        
        for ($i = 9; $i >= 0; $i--) {
            $dayLabel = \Carbon\Carbon::now()->subDays($i)->format('d');
            $barChartLabels[] = $dayLabel;
            
            if (isset($groupedByDay[$dayLabel])) {
                $dayTickets = $groupedByDay[$dayLabel];
                $barChartConsume[] = $dayTickets->where('condition', 'In Progress')->count();
                $barChartClosed[] = $dayTickets->where('condition', 'Closed')->count();
                $barChartOds[] = $dayTickets->where('condition', 'Closed')->count();
            } else {
                $barChartConsume[] = 0;
                $barChartClosed[] = 0;
                $barChartOds[] = 0;
            }
        }

        // Group by Hour for Line Chart (00 to 23)
        $groupedByHour = $tickets->groupBy(function($ticket) {
            return \Carbon\Carbon::parse($ticket->created_at)->format('H');
        });
        
        for ($i = 0; $i <= 23; $i++) {
            $hourLabel = str_pad($i, 2, '0', STR_PAD_LEFT);
            $lineChartLabels[] = $hourLabel;
            $lineChartData[] = isset($groupedByHour[$hourLabel]) ? $groupedByHour[$hourLabel]->count() : 0;
        }

        $chartData = [
            'barLabels' => $barChartLabels,
            'barConsume' => $barChartConsume,
            'barOds' => $barChartOds,
            'barClosed' => $barChartClosed,
            'lineLabels' => $lineChartLabels,
            'lineData' => $lineChartData,
        ];

        return view('team-leader.dashboard', compact('tickets', 'stats', 'timeFilter', 'chartData'));
    }
}
