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
        
        $notifications = $user->unreadNotifications->map(function($notification) {
            $data = $notification->data;
            return [
                'id' => $notification->id,
                'type' => $data['type'] ?? 'ticket_assigned',
                'title' => $data['title'] ?? 'New Ticket Assigned',
                'message' => $data['message'] ?? '',
                'ticket_id' => $data['ticket_id'] ?? ($data['id'] ?? null),
                'customer_name' => $data['customer_name'] ?? null,
                'ticket_type' => $data['ticket_type'] ?? null,
                'created_at' => $notification->created_at->diffForHumans(),
                'timestamp' => $notification->created_at->toIso8601String(),
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
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Get notification count
     */
    public function getCount()
    {
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }
}
