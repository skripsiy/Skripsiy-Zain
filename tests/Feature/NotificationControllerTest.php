<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use App\Notifications\TicketAssignedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_interact_with_notifications()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Customer A',
            'status' => 'QUEUED',
            'condition' => 'QUEUED',
            'division_target' => 'besfixed',
        ]);

        // Send a notification to the agent
        $agent->notify(new TicketAssignedNotification($ticket, $agent));

        // 1. Fetch unread notifications count
        $response = $this->actingAs($agent)
            ->get('/notifications/count');

        $response->assertOk();
        $response->assertJson(['count' => 1]);

        // 2. Fetch unread notifications list
        $response = $this->actingAs($agent)
            ->get('/notifications/unread');

        $response->assertOk();
        $response->assertJsonCount(1, 'notifications');
        $this->assertEquals($ticket->idTicket, $response->json()['notifications'][0]['ticket_id']);

        // 3. Mark notification as read
        $notificationId = $agent->unreadNotifications->first()->id;
        $response = $this->actingAs($agent)
            ->post('/notifications/mark-read', [
                'id' => $notificationId,
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        // Verify count is now 0
        $this->assertEquals(0, $agent->fresh()->unreadNotifications()->count());
    }

    public function test_notification_bell_contains_escape_html_remediation()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        $view = $this->actingAs($agent)
            ->view('components.notification-bell');

        $view->assertSee('function escapeHtml(str)');
        $view->assertSee('escapeHtml(notif.title)');
        $view->assertSee('escapeHtml(notif.message)');
        $view->assertSee('escapeHtml(notif.ticket_type)');
        $view->assertSee('escapeHtml(notif.created_at)');
        $view->assertSee('escapeHtml(notification.title)');
        $view->assertSee('escapeHtml(notification.message)');
        $view->assertSee('escapeHtml(notification.ticket_type)');
    }
}
