<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignController extends Controller
{
    public function index()
    {
        $tl       = auth()->user();
        $tlDiv    = strtolower($tl->campaign ?? '');

        // ── Loker Dispatch TL: tiket unassigned & belum closed >= 6 jam ──
        $dispatchTickets = Ticket::where('condition', '!=', 'Closed')
            ->where(function ($q) {
                $q->whereNull('assigned_to_user_id')
                  ->orWhereIn('condition', ['QUEUED', 'UNASSIGNED']);
            })
            ->where(function ($q) {
                $q->where('created_at', '<=', now()->subHours(6))
                  ->orWhere('datereport', '<=', now()->subHours(6));
            })
            ->orderByRaw("urgency_level DESC")          // VVIP → HVC → SE → Emergency → Low
            ->orderBy('created_at', 'asc')              // usia terlama dulu
            ->orderByRaw('(COALESCE(lapul,0) + COALESCE(gaul,0)) DESC')
            ->get();

        // ── Semua tiket (termasuk low/emergency) — untuk re-assign jika diperlukan ──
        $allTickets = Ticket::whereNotIn('condition', ['Closed', 'Saltik'])
            ->orderByRaw("COALESCE(urgency_level,1) DESC")
            ->orderBy('created_at', 'asc')
            ->get();

        // ── Agent aktif secara global ──
        $agents = User::select('users.*')
            ->where('role', 'agent')
            ->where('status', 'active')
            ->whereHas('workSessions', function ($q) {
                $q->where('work_date', today())
                  ->whereIn('status', ['online', 'aux']);
            })
            ->addSelect(['work_status' => \App\Models\AgentWorkSession::select('status')
                ->whereColumn('user_id', 'users.id')
                ->where('work_date', today())
                ->whereIn('status', ['online', 'aux'])
                ->orderByDesc('id')
                ->limit(1)
            ])
            ->orderBy('name')
            ->get();

        // ── Statistik ringkas ──
        $stats = [
            'dispatch_count' => $dispatchTickets->count(),
            'vvip_count'     => $dispatchTickets->where('urgency_level', 5)->count(),
            'hvc_count'      => $dispatchTickets->where('urgency_level', 4)->count(),
            'se_count'       => $dispatchTickets->where('urgency_level', 3)->count(),
            'agents_online'  => $agents->where('work_status', 'online')->count(),
            'agents_aux'     => $agents->where('work_status', 'aux')->count(),
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

        // Fix R-1: Gunakan findOrFail agar 404 jika agent tidak ditemukan
        $agent = User::findOrFail($request->agent_id);

        // Validasi ulang status aktif/sesi kerja agent
        $isActiveNow = $agent->role === 'agent'
            && $agent->status === 'active'
            && $agent->workSessions()
                ->where('work_date', today())
                ->whereIn('status', ['online', 'aux'])
                ->exists();

        if (! $isActiveNow) {
            return back()->withErrors(['agent_id' => 'Agent tersebut sedang tidak aktif. Pilih agent yang online/AUX.']);
        }

        // Fix R-1: Bungkus update data penting dengan DB::transaction()
        // Jika update gagal di tengah jalan, semua perubahan otomatis di-rollback
        DB::transaction(function () use ($ticket, $agent) {
            $ticket->update([
                'assigned_to_user_id' => $agent->id,
                'status'              => 'ASSIGNED',
                'condition'           => 'ASSIGNED',
            ]);
        });

        // Broadcast event ke agent (di luar transaction — boleh gagal, tidak rollback data)
        try {
            event(new \App\Events\TicketAssigned($ticket, $agent->id));
        } catch (\Exception $e) {
            Log::warning('TicketAssigned event failed', [
                'ticket_id' => $ticket->idTicket,
                'agent_id'  => $agent->id,
                'error'     => $e->getMessage(),
            ]);
        }

        // Kirim notifikasi ke agent (di luar transaction — boleh gagal, tidak rollback data)
        try {
            $agent->notify(new TicketAssignedNotification($ticket, $agent));
        } catch (\Exception $e) {
            Log::warning('TicketAssignedNotification failed', [
                'ticket_id' => $ticket->idTicket,
                'agent_id'  => $agent->id,
                'error'     => $e->getMessage(),
            ]);
        }

        return redirect()->back()->with('success', 'Ticket berhasil di-dispatch ke ' . $agent->name);
    }
}
