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

class TicketDispatched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $dispatcherName;

    /**
     * Create a new event instance.
     */
    public function __construct(Ticket $ticket, $dispatcherName)
    {
        $this->ticket = $ticket;
        $this->dispatcherName = $dispatcherName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('team-leader'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => 'Ticket dispatched by ' . $this->dispatcherName . ': TK' . str_pad($this->ticket->idTicket, 6, '0', STR_PAD_LEFT),
            'ticket_id' => $this->ticket->idTicket,
            'dispatcher' => $this->dispatcherName,
            'type' => 'dispatched'
        ];
    }
}
