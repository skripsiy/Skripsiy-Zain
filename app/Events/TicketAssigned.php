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
            'message' => 'New ticket assigned: TK' . str_pad($this->ticket->idTicket, 6, '0', STR_PAD_LEFT),
            'ticket_id' => $this->ticket->idTicket,
            'priority' => $this->ticket->reportedpriority,
            'type' => 'assigned'
        ];
    }
}
