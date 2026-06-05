<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Services\TicketRoutingService;
use Illuminate\Support\Facades\Log;

class TicketObserver
{
    protected TicketRoutingService $router;

    public function __construct(TicketRoutingService $router)
    {
        $this->router = $router;
    }

    /**
     * Dipanggil saat tiket baru dibuat.
     * Jalankan routing otomatis: tentukan divisi, urgency, dan auto-assign.
     */
    public function created(Ticket $ticket): void
    {
        // Hanya route jika tiket belum punya divisi (tiket baru dari luar)
        if (empty($ticket->division_target)) {
            try {
                $this->router->routeTicket($ticket);
            } catch (\Exception $e) {
                Log::error("TicketObserver@created failed for ticket #{$ticket->idTicket}: " . $e->getMessage());
            }
        }
    }

    /**
     * Dipanggil saat tiket diupdate.
     * Re-route jika source_system / channel / pool_id / reportedpriority berubah.
     */
    public function updated(Ticket $ticket): void
    {
        $rerouteFields = ['channel', 'source_system', 'pool_id', 'reportedpriority', 'klasifikasi'];

        $needsReroute = false;
        foreach ($rerouteFields as $field) {
            if ($ticket->wasChanged($field)) {
                $needsReroute = true;
                break;
            }
        }

        if ($needsReroute) {
            try {
                // Hanya update division & urgency, jangan auto-assign ulang jika sudah ada assignby
                $division = $this->router->determineDivision($ticket);
                $urgency  = $this->router->determineUrgencyLevel($ticket);

                // Gunakan updateQuietly agar tidak re-trigger observer lagi
                $ticket->updateQuietly([
                    'division_target' => $division,
                    'urgency_level'   => $urgency,
                ]);
            } catch (\Exception $e) {
                Log::error("TicketObserver@updated re-route failed for ticket #{$ticket->idTicket}: " . $e->getMessage());
            }
        }
    }
}
