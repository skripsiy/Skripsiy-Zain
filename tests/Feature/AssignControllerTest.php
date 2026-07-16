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
        $agent->workSessions()->create([
            'work_date' => today(),
            'status' => 'online',
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
        $response->assertSessionHas('success', 'Ticket berhasil di-dispatch ke ' . $agent->name);

        // 6. Assert ticket data is updated
        $ticket->refresh();
        $this->assertEquals('ASSIGNED', $ticket->status);
        $this->assertEquals('ASSIGNED', $ticket->condition);
        $this->assertEquals($agent->id, $ticket->assigned_to_user_id);

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

    public function test_team_leader_assign_page_contains_all_tickets_for_filtering()
    {
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
        ]);

        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);
        $agent->workSessions()->create([
            'work_date' => today(),
            'status' => 'online',
        ]);

        // 1. Create an unassigned (queued) ticket
        $unassignedTicket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Unassigned Customer',
            'status' => 'QUEUED',
            'condition' => 'QUEUED',
        ]);

        // 2. Create an assigned ticket
        $assignedTicket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135918',
            'namacust' => 'Assigned Customer',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $agent->id,
        ]);

        // 3. Request the page
        $response = $this->actingAs($teamLeader)
            ->get(route('team-leader.assign'));

        $response->assertOk();

        // 4. Assert both tickets exist in $allTickets view data (F-24 requirement)
        $response->assertViewHas('allTickets', function ($allTickets) use ($unassignedTicket, $assignedTicket) {
            return $allTickets->contains('idTicket', $unassignedTicket->idTicket) &&
                   $allTickets->contains('idTicket', $assignedTicket->idTicket);
        });
    }

    public function test_team_leader_cannot_assign_ticket_to_inactive_or_offline_agent()
    {
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
        ]);

        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);
        // Sesi kerja offline/tidak dimulai
        $agent->workSessions()->create([
            'work_date' => today(),
            'status' => 'offline',
        ]);

        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Pelanggan Baru',
            'status' => 'QUEUED',
            'condition' => 'QUEUED',
        ]);

        $response = $this->actingAs($teamLeader)
            ->from(route('team-leader.assign'))
            ->post(route('team-leader.assign.ticket', $ticket), [
                'agent_id' => $agent->id,
            ]);

        $response->assertRedirect(route('team-leader.assign'));
        $response->assertSessionHasErrors(['agent_id']);
        
        $ticket->refresh();
        $this->assertNull($ticket->assigned_to_user_id);
    }

    public function test_dispatch_tickets_does_not_have_six_hours_restriction()
    {
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
        ]);

        // Create a new unassigned VVIP ticket (created just now)
        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'New VVIP Customer',
            'urgency_level' => 5,
            'status' => 'QUEUED',
            'condition' => 'QUEUED',
            'division_target' => 'besfixed',
        ]);

        $response = $this->actingAs($teamLeader)
            ->get(route('team-leader.assign'));

        $response->assertOk();
        $response->assertViewHas('stats', function ($stats) {
            return $stats['dispatch_count'] === 1 && $stats['vvip_count'] === 1;
        });
    }

    public function test_sla_breach_badge_is_rendered()
    {
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
        ]);

        // Create an unassigned ticket created 7 hours ago
        $ticket = Ticket::create([
            'datereport' => now()->subHours(7),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Old Customer',
            'urgency_level' => 3,
            'status' => 'QUEUED',
            'condition' => 'QUEUED',
            'division_target' => 'besfixed',
        ]);

        $response = $this->actingAs($teamLeader)
            ->get(route('team-leader.assign'));

        $response->assertOk();
        $response->assertSee('SLA! / Nyangkut');
    }

    public function test_assigned_and_closed_tickets_are_excluded_from_dispatch_tickets()
    {
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
        ]);

        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        // Create a closed ticket
        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Closed Customer',
            'status' => 'Closed',
            'condition' => 'Closed',
            'division_target' => 'besfixed',
        ]);

        // Create an assigned ticket
        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Assigned Customer',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
        ]);

        $response = $this->actingAs($teamLeader)
            ->get(route('team-leader.assign'));

        $response->assertOk();
        $response->assertViewHas('stats', function ($stats) {
            return $stats['dispatch_count'] === 0;
        });
    }
}
