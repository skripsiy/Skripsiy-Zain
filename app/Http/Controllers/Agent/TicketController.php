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
                $query->where(function ($q) use ($search) {
                    $q->whereHas('customer', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('witelRelation', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%")
                             ->orWhereHas('area', function ($q3) use ($search) {
                                 $q3->where('name', 'like', "%{$search}%");
                             });
                      });
                });
            }

            // Tetap batasi ke tiket milik agent ini saat search
            $query->where(function ($q) {
                $q->where('assigned_to_user_id', auth()->id())
                  ->orWhere('solved_by_user_id', auth()->id());
            });

        } else {
            // ── Filter: hanya tiket yang sudah di-assign ke agent ini ──
            $query->where(function ($q) {
                $q->where('assigned_to_user_id', auth()->id())
                  ->orWhere('solved_by_user_id', auth()->id());
            });

            // ── Filter by view type ──
            if ($view === 'today') {
                // Today Logs: tiket yang kondisinya closed/saltik/dispatched/assigned/in progress hari ini
                $query->whereIn('condition', ['Closed', 'Saltik', 'Dispatched', 'DISPATCHED', 'ASSIGNED', 'In Progress'])
                      ->where(function ($q) {
                          $q->whereDate('updated_at', today())
                            ->orWhereDate('created_at', today())
                            ->orWhereDate('datereport', today())
                            ->orWhereDate('datesolved', today());
                      });
            } else {
                // Active Tickets: kondisi masih open/in-progress/queued/assigned
                $query->where(function ($q) {
                    $q->whereIn('condition', ['Open', 'In Progress', 'QUEUED', 'ASSIGNED'])
                      ->orWhereNull('condition');
                });
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
            ->with(['assignedTo']) // Fix P-1: Eager load relation
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
        // Fix P-2: Gunakan 1 query DB aggregation untuk mengambil seluruh data statistik sekaligus
        $statsRow = Ticket::where(function ($q) {
            $q->where('assigned_to_user_id', auth()->id())
              ->orWhere('solved_by_user_id', auth()->id());
        })
        ->where(function ($q) {
            $q->whereDate('created_at', today())
              ->orWhereDate('updated_at', today())
              ->orWhereDate('datereport', today())
              ->orWhereDate('datesolved', today());
        })
        ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN LOWER(COALESCE(`condition`, status)) IN ('in progress', 'in-progress', 'assigned') THEN 1 ELSE 0 END) as consumed,
            SUM(CASE WHEN assigned_to_user_id = ? AND LOWER(COALESCE(`condition`, status)) IN ('queued', 'assigned', 'open', 'new') THEN 1 ELSE 0 END) as submitted,
            SUM(CASE WHEN LOWER(COALESCE(`condition`, status)) IN ('closed', 'saltik') THEN 1 ELSE 0 END) as closed,
            SUM(CASE WHEN LOWER(COALESCE(`condition`, status)) IN ('dispatched', 'dispatched') THEN 1 ELSE 0 END) as dispatched
        ", [auth()->id()])
        ->first();

        $totalTickets      = (int) ($statsRow->total ?? 0);
        $consumedTickets   = (int) ($statsRow->consumed ?? 0);
        $submittedTickets  = (int) ($statsRow->submitted ?? 0);
        $closedTickets     = (int) ($statsRow->closed ?? 0);
        $dispatchedTickets = (int) ($statsRow->dispatched ?? 0);

        return view('agent.tickets', compact(
            'tickets', 'totalTickets', 'consumedTickets', 'submittedTickets',
            'closedTickets', 'dispatchedTickets', 'view', 'agentDiv'
        ));
    }
}
