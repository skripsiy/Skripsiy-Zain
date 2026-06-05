<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $timeFilter = $request->input('time_filter', 'today');
        $agentName = auth()->user()->name;

        $query = \App\Models\Ticket::where('assignby', $agentName);

        $now = \Carbon\Carbon::now();
        switch ($timeFilter) {
            case 'week':
                $query->where(function($q) use ($now) {
                    $q->where('created_at', '>=', $now->copy()->startOfWeek())
                      ->orWhere('updated_at', '>=', $now->copy()->startOfWeek());
                });
                break;
            case 'month':
                $query->where(function($q) use ($now) {
                    $q->where('created_at', '>=', $now->copy()->startOfMonth())
                      ->orWhere('updated_at', '>=', $now->copy()->startOfMonth());
                });
                break;
            case 'quarter':
                $query->where(function($q) use ($now) {
                    $q->where('created_at', '>=', $now->copy()->startOfQuarter())
                      ->orWhere('updated_at', '>=', $now->copy()->startOfQuarter());
                });
                break;
            case 'today':
            default:
                $query->where(function($q) use ($now) {
                    $q->whereDate('created_at', $now->today())
                      ->orWhereDate('updated_at', $now->today());
                });
                break;
        }

        $tickets = $query->orderBy('created_at', 'desc')->get();

        $woAvailable = $tickets->count();
        $consume = $tickets->where('condition', 'In Progress')->count();
        $closed = $tickets->whereIn('condition', ['Closed', 'Saltik'])->count();
        $dispatched = $tickets->whereIn('condition', ['Dispatched', 'DISPATCHED'])->count();
        $ods = $tickets->whereIn('condition', ['Closed', 'Saltik'])->count();

        $stats = [
            'wo_available' => $woAvailable,
            'consume' => $consume,
            'ods' => $ods,
            'closed' => $closed,
            'dispatched' => $dispatched,
        ];

        // Prepare Chart Data
        $barChartLabels = [];
        $barChartConsume = [];
        $barChartOds = [];
        $barChartClosed = [];
        $barChartDispatched = [];
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
                $barChartClosed[] = $dayTickets->whereIn('condition', ['Closed', 'Saltik'])->count();
                $barChartOds[] = $dayTickets->whereIn('condition', ['Closed', 'Saltik'])->count();
                $barChartDispatched[] = $dayTickets->whereIn('condition', ['Dispatched', 'DISPATCHED'])->count();
            } else {
                $barChartConsume[] = 0;
                $barChartClosed[] = 0;
                $barChartOds[] = 0;
                $barChartDispatched[] = 0;
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
            'barDispatched' => $barChartDispatched,
            'lineLabels' => $lineChartLabels,
            'lineData' => $lineChartData,
        ];

        return view('agent.dashboard', compact('tickets', 'stats', 'timeFilter', 'chartData'));
    }

    /**
     * AJAX endpoint to filter tickets by date range for Ticket Report table only.
     */
    public function filterTickets(Request $request)
    {
        $agentName = auth()->user()->name;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = \App\Models\Ticket::where('assignby', $agentName);

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

        $tickets = $query->orderBy('created_at', 'desc')->get();

        $result = $tickets->map(function ($ticket) {
            return [
                'code' => 'IN' . str_pad($ticket->idTicket, 8, '0', STR_PAD_LEFT),
                'symptomp' => $ticket->topic ?? '-',
                'agent' => $ticket->assignby ?? '-',
                'date' => $ticket->created_at->format('Y-m-d'),
                'status' => $ticket->condition,
            ];
        });

        return response()->json($result);
    }
}
