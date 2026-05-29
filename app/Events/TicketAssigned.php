<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $agentId;

    /**
     * Create a new event instance.
     */
    public function __construct(Ticket $ticket, $agentId)
    {
        $this->ticket = $ticket;
        $this->agentId = $agentId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('agent.' . $this->agentId),
        ];
    }

    public function broadcastWith(): array
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
