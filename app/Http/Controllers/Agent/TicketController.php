<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $view       = $request->get('view', 'active'); // active or today
        $agentName  = auth()->user()->name;
        $agentEmail = auth()->user()->email;
        $agentDiv   = strtolower(auth()->user()->campaign ?? 'besfixed');

        $query = Ticket::query();

        // Search functionality - global search
        if ($request->has('search') && $request->search != '') {
            $search = trim($request->search);

            // Jika formatnya IN diikuti angka (contoh: IN00000044), cari spesifik (Exact Match)
            if (preg_match('/^IN\d+$/i', $search)) {
                $exactId = (int) preg_replace('/^IN0*/i', '', $search);
                $query->where('idTicket', $exactId);
            } else {
                // Jika format bebas, cari berdasarkan nama, regional, atau witel secara parsial
                $query->where(function ($q) use ($search) {
                    $q->where('namacust', 'like', "%{$search}%")
                      ->orWhere('regional', 'like', "%{$search}%")
                      ->orWhere('witel', 'like', "%{$search}%");
                });
            }

            // Tetap batasi ke tiket milik agent ini saat search
            $query->where(function ($q) use ($agentName, $agentEmail) {
                $q->where('assignby', $agentName)
                  ->orWhere('solvedby', $agentName)
                  ->orWhere('solvedby', $agentEmail);
            });

        } else {
            // ── Filter: hanya tiket yang sudah di-assign ke agent ini ──
            $query->where(function ($q) use ($agentName, $agentEmail) {
                $q->where('assignby', $agentName)
                  ->orWhere('solvedby', $agentName)
                  ->orWhere('solvedby', $agentEmail);
            });

            // ── Filter by view type ──
            if ($view === 'today') {
                // Today Logs: tiket yang kondisinya closed/saltik/dispatched/assigned/in progress hari ini
                $query->whereIn('condition', ['Closed', 'Saltik', 'Dispatched', 'DISPATCHED', 'ASSIGNED', 'In Progress'])
                      ->where(function ($q) {
                          $q->whereDate('updated_at', today())
                            ->orWhereDate('datesolved', today());
                      });
            } else {
                // Active Tickets: kondisi masih open/in-progress/queued/assigned
                $query->whereIn('condition', ['Open', 'In Progress', 'QUEUED', 'ASSIGNED'])
                      ->orWhereNull('condition');
            }
        }

        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [1, 10, 25, 50])) {
            $perPage = 10;
        }

        // ── Urutan Prioritas (sesuai kriteria divisi) ──
        // 1. Urgency level DESC (5=VVIP tertinggi, 1=Low terbawah)
        // 2. Usia tiket terlama (datereport ASC)
        // 3. Lapul + Gaul DESC
        // 4. Tiket ASSIGNED tampil lebih awal
        $tickets = $query
            ->orderByRaw("CASE
                WHEN `condition` IN ('ASSIGNED') OR `status` IN ('ASSIGNED') THEN 0
                WHEN `condition` IN ('Dispatched', 'DISPATCHED') THEN 1
                ELSE 2
            END ASC")
            ->orderByRaw("COALESCE(urgency_level, 1) DESC")
            ->orderBy('datereport', 'asc')
            ->orderByRaw('(COALESCE(lapul,0) + COALESCE(gaul,0)) DESC')
            ->paginate($perPage)
            ->withQueryString();

        // ── Statistik agent ──
        $baseQuery = Ticket::where(function ($q) use ($agentName, $agentEmail) {
            $q->where('assignby', $agentName)
              ->orWhere('solvedby', $agentName)
              ->orWhere('solvedby', $agentEmail);
        });

        $totalTickets = (clone $baseQuery)->count();

        $consumedTickets = Ticket::where('solvedby', $agentName)
            ->orWhere('solvedby', $agentEmail)
            ->count();

        $submittedTickets = Ticket::where('assignby', $agentName)
            ->whereIn('condition', ['QUEUED', 'ASSIGNED', 'Open'])
            ->count();

        $closedTickets = Ticket::where(function ($q) use ($agentName, $agentEmail) {
            $q->where('solvedby', $agentName)
              ->orWhere('solvedby', $agentEmail);
        })->whereIn('condition', ['Closed', 'Saltik'])->count();

        $dispatchedTickets = Ticket::where('assignby', $agentName)
            ->whereIn('condition', ['Dispatched', 'DISPATCHED'])
            ->count();

        return view('agent.tickets', compact(
            'tickets', 'totalTickets', 'consumedTickets', 'submittedTickets',
            'closedTickets', 'dispatchedTickets', 'view', 'agentDiv'
        ));
    }
}
