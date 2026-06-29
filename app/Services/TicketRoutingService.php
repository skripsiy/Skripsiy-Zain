<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use App\Models\AgentWorkSession;
use App\Notifications\TicketAssignedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class TicketRoutingService
{
    // ─────────────────────────────────────────────
    // SLA Threshold per divisi (dalam menit)
    // ─────────────────────────────────────────────
    const SLA_THRESHOLD = [
        'saltik'   => 11,   // rata-rata 10-12 menit
        'area'     => 9,    // rata-rata 8-10 menit
        'besfixed' => 6,    // rata-rata 5-6 menit
    ];

    // ─────────────────────────────────────────────
    // Channel INSERA per divisi
    // ─────────────────────────────────────────────
    const INSERA_CHANNELS_BESFIXED = ['2', '19', '4', '40', '54'];
    const INSERA_CHANNELS_SALTIK   = ['2', '19', '4', '40', '54'];
    const INSERA_CHANNEL_AREA      = ['21'];

    // Pool IDs
    const POOL_ID_BESFIXED = 'new_site179 BESFIXED';
    const POOL_ID_SALTIK   = 'SALAM SIMPATIK';
    const POOL_ID_AREA     = 'Network Service Desk';

    // Status DSC yang menjadi kriteria (In Progress)
    const DSC_INPROGRESS_STATUSES = [
        'Un-Assign', 'Unassign', 'Processing',
        'Remedy Cancel', 'Remedy Cancell',
        'Waiting Add. Information', 'Waiting Additional Information',
    ];

    // Status INSERA yang menjadi kriteria (In Progress)
    const INSERA_INPROGRESS_STATUSES = [
        'New', 'Analysis', 'Pending', 'Backend', 'FinalCheck', 'Final Check',
    ];

    // Status DSC untuk SALTIK
    const DSC_SALTIK_STATUSES = [
        'Confirmation to Customer', 'Salam Simpatik', 'SALAMSIM',
    ];

    // Status INSERA untuk SALTIK
    const INSERA_SALTIK_STATUSES = [
        'Resolved', 'Media Care', 'SALAMSIM', 'Salam Simpatik',
    ];

    /**
     * Tentukan divisi target berdasarkan kriteria tiket.
     * Urutan prioritas pengecekan: SALTIK → AREA → BESFIXED → fallback BESFIXED
     */
    public function determineDivision(Ticket $ticket): string
    {
        return 'besfixed';
    }

    /**
     * Tentukan level urgency tiket (1–5).
     * 1 = Low Emergency, 2 = Emergency, 3 = Super Emergency,
     * 4 = HVC, 5 = VVIP/Management
     */
    public function determineUrgencyLevel(Ticket $ticket): int
    {
        $priority = strtoupper(trim($ticket->reportedpriority ?? ''));
        $klasifikasi = strtoupper(trim($ticket->klasifikasi ?? ''));
        $topic = strtoupper(trim($ticket->topic ?? ''));

        // Tier 5 — VVIP / Management
        if (
            str_contains($priority, 'VVIP')
            || str_contains($priority, 'MANAGEMENT')
            || str_contains($klasifikasi, 'VVIP')
            || str_contains($klasifikasi, 'MANAGEMENT')
        ) {
            return 5;
        }

        // Tier 4 — HVC (High Value Customer)
        if (
            str_contains($priority, 'HVC')
            || str_contains($priority, 'HIGH VALUE')
            || str_contains($klasifikasi, 'HVC')
            || str_contains($klasifikasi, 'HIGH VALUE')
        ) {
            return 4;
        }

        // Tier 3 — Super Emergency
        if (
            str_contains($priority, 'SUPER EMERGENCY')
            || str_contains($priority, 'SUPER_EMERGENCY')
            || ($priority === 'SE')
        ) {
            return 3;
        }

        // Tier 2 — Emergency (tapi bukan LOW EMERGENCY dan bukan SUPER)
        if (str_contains($priority, 'EMERGENCY') && !str_contains($priority, 'LOW')) {
            return 2;
        }

        // Tier 1 — Low Emergency (default)
        return 1;
    }

    /**
     * Cek apakah tiket boleh di-auto-assign (hanya urgency 1 & 2).
     * Urgency 3 (Super Emergency), 4 (HVC), 5 (VVIP) harus di-assign manual oleh Team Leader.
     */
    public function shouldAutoAssign(Ticket $ticket): bool
    {
        $urgency = (int) ($ticket->urgency_level ?? $this->determineUrgencyLevel($ticket));
        return $urgency <= 2; // Level 1 (Low Emergency) & 2 (Emergency) → auto round-robin
    }

    /**
     * Auto-assign tiket ke agent online secara global (round-robin).
     * Maksimal 10 agent yang aktif per hari.
     */
    public function autoAssignToAgent(Ticket $ticket): bool
    {
        // Ambil agent yang online hari ini secara global (case-insensitive)
        $onlineAgents = User::where('role', 'agent')
            ->where('status', 'active')
            ->whereHas('workSessions', function ($q) {
                $q->where('work_date', today())
                  ->where('status', 'online');
            })
            ->orderBy('id')
            ->take(10)
            ->get();

        if ($onlineAgents->isEmpty()) {
            return false; // Tidak ada agent online, biarkan di queue
        }

        // Round-robin: ambil index dari cache, increment, wrap around
        $cacheKey = "rr_index_global";
        $index    = Cache::get($cacheKey, 0);

        if ($index >= $onlineAgents->count()) {
            $index = 0;
        }

        $agent = $onlineAgents[$index];
        Cache::put($cacheKey, ($index + 1) % $onlineAgents->count(), 3600);

        // Assign tiket ke agent menggunakan assigned_to_user_id
        $ticket->update([
            'assigned_to_user_id' => $agent->id,
            'condition'           => 'ASSIGNED',
            'status'              => 'ASSIGNED',
            'auto_assigned_at'    => now(),
        ]);

        // Kirim notifikasi ke agent
        try {
            $agent->notify(new TicketAssignedNotification($ticket, $agent));
        } catch (\Exception $e) {
            // Log saja, jangan gagalkan proses
            \Log::warning("Failed to send notification to agent {$agent->id}: " . $e->getMessage());
        }

        // Broadcast event jika ada
        try {
            event(new \App\Events\TicketAssigned($ticket, $agent->id));
        } catch (\Exception $e) {
            \Log::warning("Failed to broadcast TicketAssigned: " . $e->getMessage());
        }

        return true;
    }

    /**
     * Route tiket: tentukan divisi, urgency, lalu auto-assign atau biarkan di loker TL.
     *
     * Flow:
     *   - Urgency 1 (Low Emergency) & 2 (Emergency) → auto-assign round-robin ke agent
     *   - Urgency 3 (Super Emergency), 4 (HVC), 5 (VVIP) → QUEUED untuk Team Leader
     */
    public function routeTicket(Ticket $ticket): void
    {
        // 1. Tentukan divisi
        $division = $this->determineDivision($ticket);

        // 2. Tentukan urgency
        $urgency = $this->determineUrgencyLevel($ticket);

        // 3. Update divisi & urgency dulu (quietly agar tidak re-trigger observer)
        $ticket->updateQuietly([
            'division_target' => $division,
            'urgency_level'   => $urgency,
        ]);

        // 4. Cek apakah bisa auto-assign (urgency 1-2)
        if ($this->shouldAutoAssign($ticket)) {
            // Coba auto-assign via round-robin
            $assigned = $this->autoAssignToAgent($ticket);

            if (!$assigned) {
                // Tidak ada agent online, masukkan ke queue
                $ticket->updateQuietly([
                    'condition'           => 'QUEUED',
                    'status'              => 'QUEUED',
                    'assigned_to_user_id' => null,
                ]);
            }
            // Jika assigned, autoAssignToAgent() sudah update status ke ASSIGNED
        } else {
            // Urgency 3-5: masuk loker Team Leader untuk assign manual
            $ticket->updateQuietly([
                'condition'           => 'QUEUED',
                'status'              => 'QUEUED',
                'assigned_to_user_id' => null,
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // Helper Methods
    // ─────────────────────────────────────────────────────────────────

    private function statusMatchesAny(string $status, array $targets): bool
    {
        $status = strtolower(trim($status));
        foreach ($targets as $target) {
            if (str_contains($status, strtolower($target))) {
                return true;
            }
        }
        return false;
    }

    private function isNearOrOutSla(Ticket $ticket, string $division): bool
    {
        if (!$ticket->THT) {
            return false;
        }

        $thresholdMinutes = self::SLA_THRESHOLD[$division] ?? 9;
        $tht = Carbon::parse($ticket->THT);
        $now = Carbon::now();

        // Out SLA: THT sudah lewat
        // Menuju SLA: THT < threshold menit dari sekarang
        return $now->gte($tht) || $tht->diffInMinutes($now, false) >= -$thresholdMinutes;
    }

    private function notifyTeamLeaders(Ticket $ticket, string $division): void
    {
        $teamLeaders = User::where('role', 'team_leader')
            ->where('status', 'active')
            ->get();

        foreach ($teamLeaders as $tl) {
            try {
                $tl->notify(new TicketAssignedNotification($ticket, $tl));
            } catch (\Exception $e) {
                \Log::warning("Failed to notify TL {$tl->id}: " . $e->getMessage());
            }
        }
    }
}
