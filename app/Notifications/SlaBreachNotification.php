<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SlaBreachNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->ticket->idTicket,
            'type' => 'sla_breach',
            'title' => 'SLA Breach Alert',
            'message' => "Ticket #{$this->ticket->idTicket} has exceeded the 6-hour SLA threshold!",
            'ticket_id' => $this->ticket->idTicket,
            'customer_name' => $this->ticket->namacust,
            'ticket_type' => $this->ticket->jenisTicket,
            'created_at' => now()->toIso8601String(),
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'id' => $this->ticket->idTicket,
            'type' => 'sla_breach',
            'title' => 'SLA Breach Alert',
            'message' => "Ticket #{$this->ticket->idTicket} has exceeded the 6-hour SLA threshold!",
            'ticket_id' => $this->ticket->idTicket,
            'customer_name' => $this->ticket->namacust,
            'ticket_type' => $this->ticket->jenisTicket,
            'created_at' => now()->toIso8601String(),
        ];
    }
}
