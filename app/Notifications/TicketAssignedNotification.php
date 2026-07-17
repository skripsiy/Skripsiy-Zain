<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TicketAssignedNotification extends Notification
{

    public $ticket;
    public $user;

    public function __construct($ticket, $user)
    {
        $this->ticket = $ticket;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->ticket->idTicket,
            'type' => 'ticket_assigned',
            'title' => 'New Ticket Assigned',
            'message' => "You have been assigned a new ticket: {$this->ticket->idTicket}",
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
            'type' => 'ticket_assigned',
            'title' => 'New Ticket Assigned',
            'message' => "You have been assigned a new ticket: {$this->ticket->idTicket}",
            'ticket_id' => $this->ticket->idTicket,
            'customer_name' => $this->ticket->namacust,
            'ticket_type' => $this->ticket->jenisTicket,
            'created_at' => now()->toIso8601String(),
        ];
    }
}
