<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_dashboard_includes_tickets_assigned_or_updated_today()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'name' => 'Agent Bobby',
            'status' => 'active',
        ]);

        // Create a ticket created 5 days ago, but updated/assigned today
        $ticket = Ticket::create([
            'datereport' => now()->subDays(5),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Old Customer',
            'idlaporan' => 423846,
            'detailticket' => 'Internet slow',
            'topic' => 'Koneksi Lambat',
            'reportedpriority' => 'Emergency',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
            'created_at' => now()->subDays(5),
            'updated_at' => now(), // updated/assigned today
        ]);

        $response = $this->actingAs($agent)
            ->get(route('agent.dashboard'));

        $response->assertOk();
        
        // Assert ticket is passed to the dashboard view
        $response->assertViewHas('tickets', function ($tickets) use ($ticket) {
            return $tickets->contains('idTicket', $ticket->idTicket);
        });

        // Assert stats in view count this ticket
        $stats = $response->viewData('stats');
        $this->assertEquals(1, $stats['wo_available']);
    }

    public function test_agent_tickets_today_logs_includes_tickets_dispatched_or_closed_today()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'name' => 'Agent Bobby',
            'status' => 'active',
        ]);

        // Create a ticket created 5 days ago, but dispatched today
        $ticket = Ticket::create([
            'datereport' => now()->subDays(5),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Old Customer 2',
            'idlaporan' => 423847,
            'detailticket' => 'Internet down',
            'topic' => 'Koneksi Lambat',
            'reportedpriority' => 'Emergency',
            'status' => 'DISPATCHED',
            'condition' => 'Dispatched',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
            'created_at' => now()->subDays(5),
            'updated_at' => now(), // updated/dispatched today
        ]);

        $response = $this->actingAs($agent)
            ->get(route('agent.tickets', ['view' => 'today']));

        $response->assertOk();
        
        // Assert ticket is in the Today Logs view list
        $response->assertViewHas('tickets', function ($tickets) use ($ticket) {
            return $tickets->contains('idTicket', $ticket->idTicket);
        });
    }

    public function test_ticket_detail_page_renders_for_agent()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'name' => 'Agent Bobby',
            'status' => 'active',
        ]);

        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Customer',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $agent->id,
        ]);

        $response = $this->actingAs($agent)
            ->get(route('agent.ticket.detail', $ticket->idTicket));

        $response->assertOk();
    }

    public function test_ticket_detail_page_renders_for_team_leader()
    {
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
        ]);

        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Customer',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
        ]);

        $response = $this->actingAs($teamLeader)
            ->get(route('team-leader.ticket.detail', $ticket->idTicket));

        $response->assertOk();
    }

    public function test_ticket_detail_v2_page_renders_for_agent()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'name' => 'Agent Bobby',
            'status' => 'active',
        ]);

        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Customer',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $agent->id,
        ]);

        $response = $this->actingAs($agent)
            ->get(route('agent.ticket.detail.v2', $ticket->idTicket));

        $response->assertOk();
    }
}
