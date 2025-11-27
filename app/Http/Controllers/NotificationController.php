<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

class NotificationController extends Controller
{
    /**
     * Get unread notifications for the authenticated user
     */
    public function getUnreadNotifications()
    {
        $user = Auth::user();
        
        // Get new tickets assigned to user that haven't been viewed
        $newTickets = Ticket::where('assignby', $user->email)
            ->where('status', 'QUEUED')
            ->where('created_at', '>=', now()->subHours(24)) // Last 24 hours
            ->orderBy('created_at', 'desc')
            ->get();

        $notifications = $newTickets->map(function($ticket) {
            return [
                'id' => $ticket->idTicket,
                'type' => 'new_ticket',
                'title' => 'New Ticket Assigned',
                'message' => "Ticket #{$ticket->idTicket} from {$ticket->namacust}",
                'ticket_id' => $ticket->idTicket,
                'customer_name' => $ticket->namacust,
                'ticket_type' => $ticket->jenisTicket,
                'created_at' => $ticket->created_at->diffForHumans(),
                'timestamp' => $ticket->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request)
    {
        // In a real application, you would update a notifications table
        // For now, we'll just return success
        return response()->json(['success' => true]);
    }

    /**
     * Get notification count
     */
    public function getCount()
    {
        $user = Auth::user();
        
        $count = Ticket::where('assignby', $user->email)
            ->where('status', 'QUEUED')
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        return response()->json(['count' => $count]);
    }
}
