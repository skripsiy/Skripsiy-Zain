<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_leader_can_assign_ticket_to_agent()
    {
        // 1. Create team leader user
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
        ]);

        // 2. Create agent user
        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        // 3. Create a queued ticket
        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Pelanggan Baru 2',
            'idlaporan' => 423846,
            'detailticket' => 'Gangguan koneksi pelanggan baru #2',
            'topic' => 'Koneksi Lambat',
            'reportedpriority' => 'Emergency',
            'status' => 'QUEUED',
            'condition' => 'QUEUED',
        ]);

        // 4. Send request to assign the ticket
        $response = $this->actingAs($teamLeader)
            ->from(route('team-leader.assign'))
            ->post(route('team-leader.assign.ticket', $ticket), [
                'agent_id' => $agent->id,
            ]);

        // 5. Assert redirection and success message
        $response->assertRedirect(route('team-leader.assign'));
        $response->assertSessionHas('success', 'Ticket successfully assigned to ' . $agent->name);

        // 6. Assert ticket data is updated
        $ticket->refresh();
        $this->assertEquals('ASSIGNED', $ticket->status);
        $this->assertEquals('ASSIGNED', $ticket->condition);
        $this->assertEquals($agent->name, $ticket->assignby);

        // 7. Assert notification is created in database
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $agent->id,
            'notifiable_type' => User::class,
            'type' => 'App\Notifications\TicketAssignedNotification',
        ]);

        // Verify the notification content
        $notification = $agent->notifications()->first();
        $this->assertNotNull($notification);
        $this->assertEquals('ticket_assigned', $notification->data['type']);
        $this->assertEquals($ticket->idTicket, $notification->data['ticket_id']);
    }
}
