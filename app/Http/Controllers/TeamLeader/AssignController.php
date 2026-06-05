<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Http\Request;

class AssignController extends Controller
{
    public function index()
    {
        $tl       = auth()->user();
        $tlDiv    = strtolower($tl->campaign ?? '');

        // ── Loker Dispatch TL: tiket prioritas tinggi (SE/HVC/VVIP) yang belum di-assign ──
        // Filter berdasarkan divisi TL. Jika TL tidak punya divisi, tampilkan semua.
        $dispatchQuery = Ticket::whereIn('urgency_level', [3, 4, 5])
            ->where(function ($q) {
                $q->whereNull('assignby')
                  ->orWhere('assignby', '')
                  ->orWhereIn('condition', ['QUEUED']);
            });

        if ($tlDiv !== '') {
            $dispatchQuery->whereRaw('LOWER(division_target) = ?', [$tlDiv]);
        }

        $dispatchTickets = $dispatchQuery
            ->orderByRaw("urgency_level DESC")          // VVIP → HVC → SE
            ->orderBy('datereport', 'asc')              // usia terlama dulu
            ->orderByRaw('(COALESCE(lapul,0) + COALESCE(gaul,0)) DESC')
            ->get();

        // ── Semua tiket (termasuk low/emergency) — untuk re-assign jika diperlukan ──
        $allTicketsQuery = Ticket::whereNotIn('condition', ['Closed', 'Saltik']);
        if ($tlDiv !== '') {
            $allTicketsQuery->whereRaw('LOWER(division_target) = ?', [$tlDiv]);
        }
        $allTickets = $allTicketsQuery
            ->orderByRaw("COALESCE(urgency_level,1) DESC")
            ->orderBy('created_at', 'asc')
            ->get();

        // ── Agent aktif dari divisi TL (case-insensitive) ──
        $agentQuery = User::where('role', 'agent')->where('status', 'active');
        if ($tlDiv !== '') {
            $agentQuery->whereRaw('LOWER(campaign) = ?', [$tlDiv]);
        }
        $agents = $agentQuery->orderBy('name')->get();

        // ── Statistik ringkas ──
        $stats = [
            'dispatch_count' => $dispatchTickets->count(),
            'vvip_count'     => $dispatchTickets->where('urgency_level', 5)->count(),
            'hvc_count'      => $dispatchTickets->where('urgency_level', 4)->count(),
            'se_count'       => $dispatchTickets->where('urgency_level', 3)->count(),
            'agents_online'  => $agents->filter(function ($agent) {
                return $agent->workSessions()
                    ->where('work_date', today())
                    ->where('status', 'online')
                    ->exists();
            })->count(),
        ];

        return view('team-leader.assign', compact(
            'dispatchTickets', 'allTickets', 'agents', 'stats', 'tlDiv'
        ));
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id'
        ]);

        $agent = User::find($request->agent_id);
        $tl    = auth()->user();
        $tlDiv = strtolower($tl->campaign ?? '');

        // Pastikan agent dari divisi yang sama dengan TL (jika TL punya divisi)
        if ($tlDiv !== '' && strtolower($agent->campaign ?? '') !== $tlDiv) {
            return redirect()->back()->with('error', 'Agent tidak berada dalam divisi Anda.');
        }

        $ticket->update([
            'assignby'  => $agent->name,
            'status'    => 'ASSIGNED',
            'condition' => 'ASSIGNED',
        ]);

        // Broadcast event ke agent
        try {
            event(new \App\Events\TicketAssigned($ticket, $agent->id));
        } catch (\Exception $e) {
            \Log::warning("TicketAssigned event failed: " . $e->getMessage());
        }

        // Kirim notifikasi ke agent
        try {
            $agent->notify(new TicketAssignedNotification($ticket, $agent));
        } catch (\Exception $e) {
            \Log::warning("Notification failed: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Ticket berhasil di-dispatch ke ' . $agent->name);
    }
}
