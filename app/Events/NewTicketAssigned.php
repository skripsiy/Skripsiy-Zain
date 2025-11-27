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

class NewTicketAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;
    public $assignedTo;

    /**
     * Create a new event instance.
     */
    public function __construct(Ticket $ticket, string $assignedTo)
    {
        $this->ticket = $ticket;
        $this->assignedTo = $assignedTo;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->assignedTo),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'ticket.assigned';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'ticket_id' => $this->ticket->idTicket,
            'customer_name' => $this->ticket->namacust,
            'ticket_type' => $this->ticket->jenisTicket,
            'status' => $this->ticket->status,
            'created_at' => $this->ticket->created_at->toDateTimeString(),
        ];
    }
}
