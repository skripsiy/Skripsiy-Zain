<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardCaseInsensitiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_counts_are_case_insensitive()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Create tickets with different case formats
        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Customer 1',
            'status' => 'Closed',
            'condition' => 'closed', // Lowercase
            'division_target' => 'besfixed',
        ]);

        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135918',
            'namacust' => 'Customer 2',
            'status' => 'Closed',
            'condition' => 'CLOSED', // Uppercase
            'division_target' => 'besfixed',
        ]);

        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135919',
            'namacust' => 'Customer 3',
            'status' => 'In Progress',
            'condition' => 'in progress', // Lowercase
            'division_target' => 'besfixed',
        ]);

        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135920',
            'namacust' => 'Customer 4',
            'status' => 'In Progress',
            'condition' => 'IN PROGRESS', // Uppercase
            'division_target' => 'besfixed',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertOk();
        $stats = $response->viewData('stats');

        $this->assertEquals(2, $stats['closed']);
        $this->assertEquals(2, $stats['consume']);
    }

    public function test_team_leader_dashboard_counts_are_case_insensitive()
    {
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
        ]);

        // Create tickets with different case formats
        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135921',
            'namacust' => 'Customer A',
            'status' => 'Closed',
            'condition' => 'closed',
            'division_target' => 'besfixed',
        ]);

        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135922',
            'namacust' => 'Customer B',
            'status' => 'In Progress',
            'condition' => 'in progress',
            'division_target' => 'besfixed',
        ]);

        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135923',
            'namacust' => 'Customer C',
            'status' => 'Dispatched',
            'condition' => 'dispatched',
            'division_target' => 'besfixed',
        ]);

        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135924',
            'namacust' => 'Customer D',
            'status' => 'Saltik',
            'condition' => 'saltik',
            'division_target' => 'besfixed',
        ]);

        $response = $this->actingAs($teamLeader)
            ->get(route('team-leader.dashboard'));

        $response->assertOk();
        $stats = $response->viewData('stats');

        $this->assertEquals(1, $stats['closed']);
        $this->assertEquals(1, $stats['consume']);
        $this->assertEquals(1, $stats['dispatched']);
        $this->assertEquals(1, $stats['saltik']);
    }

    public function test_agent_dashboard_counts_are_case_insensitive()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        // Create tickets assigned to this agent with different cases
        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135925',
            'namacust' => 'Customer Agent 1',
            'status' => 'Closed',
            'condition' => 'closed',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
        ]);

        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135926',
            'namacust' => 'Customer Agent 2',
            'status' => 'In Progress',
            'condition' => 'in progress',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
        ]);

        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135927',
            'namacust' => 'Customer Agent 3',
            'status' => 'Dispatched',
            'condition' => 'dispatched',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
        ]);

        $response = $this->actingAs($agent)
            ->get(route('agent.dashboard'));

        $response->assertOk();
        $stats = $response->viewData('stats');

        $this->assertEquals(1, $stats['closed']);
        $this->assertEquals(1, $stats['consume']);
        $this->assertEquals(1, $stats['dispatched']);
    }

    public function test_admin_reports_counts_are_case_insensitive()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135930',
            'namacust' => 'Customer R1',
            'status' => 'Closed',
            'condition' => 'closed', // Lowercase
            'division_target' => 'besfixed',
        ]);

        Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135931',
            'namacust' => 'Customer R2',
            'status' => 'ASSIGNED',
            'condition' => 'assigned', // Lowercase
            'division_target' => 'besfixed',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.tickets'));

        $response->assertOk();
        $response->assertViewHas('closedCount', 1);
        $response->assertViewHas('assignedCount', 1);
    }
}
