<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketRoutingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketRoutingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TicketRoutingService $router;

    protected function setUp(): void
    {
        parent::setUp();
        $this->router = new TicketRoutingService();
    }

    /**
     * Test 1: Tiket divisi 'saltik' HANYA ter-assign ke agent campaign 'saltik' (case-insensitive).
     */
    public function test_ticket_assigned_only_to_agent_in_matching_division_case_insensitive()
    {
        // Agent campaign 'saltik' (case mixed)
        $saltikAgent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
            'campaign' => 'SaLtIk',
        ]);
        $saltikAgent->workSessions()->create([
            'work_date' => today(),
            'status' => 'online',
        ]);

        // Agent campaign 'besfixed'
        $besfixedAgent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
            'campaign' => 'besfixed',
        ]);
        $besfixedAgent->workSessions()->create([
            'work_date' => today(),
            'status' => 'online',
        ]);

        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Test Customer Saltik',
            'division_target' => 'saltik',
            'urgency_level' => 1,
            'status' => 'QUEUED',
            'condition' => 'QUEUED',
        ]);

        $assigned = $this->router->autoAssignToAgent($ticket);

        $this->assertTrue($assigned);
        $ticket->refresh();
        $this->assertEquals($saltikAgent->id, $ticket->assigned_to_user_id);
    }

    /**
     * Test 2: Dengan > 10 agent online di satu divisi, semua agent mendapat giliran.
     */
    public function test_more_than_ten_agents_all_get_tickets_eventually()
    {
        $agents = [];
        // Create 12 active online agents in 'besfixed'
        for ($i = 0; $i < 12; $i++) {
            $agent = User::factory()->create([
                'role' => 'agent',
                'status' => 'active',
                'campaign' => 'besfixed',
            ]);
            $agent->workSessions()->create([
                'work_date' => today(),
                'status' => 'online',
            ]);
            $agents[] = $agent;
        }

        // Sort by ID to match the orderBy('id') in the service query
        usort($agents, fn($a, $b) => $a->id <=> $b->id);

        // Assign 12 tickets sequentially
        for ($i = 0; $i < 12; $i++) {
            $ticket = Ticket::create([
                'datereport' => now(),
                'jenisTicket' => 'INTERNET',
                'namacust' => "Test Customer {$i}",
                'division_target' => 'besfixed',
                'urgency_level' => 1,
                'status' => 'QUEUED',
                'condition' => 'QUEUED',
            ]);

            $assigned = $this->router->autoAssignToAgent($ticket);
            $this->assertTrue($assigned);

            $ticket->refresh();
            $this->assertEquals($agents[$i]->id, $ticket->assigned_to_user_id, "Failed on assignment index {$i}");
        }
    }

    /**
     * Test 3: Bila tidak ada agent online di divisi itu -> tiket masuk QUEUED (loker TL).
     */
    public function test_no_online_agents_leads_to_queued_status()
    {
        // No agents exist at all
        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => "Test Queue Customer",
            'division_target' => 'area',
            'urgency_level' => 1,
            'status' => 'NEW',
            'condition' => 'NEW',
        ]);

        $this->router->routeTicket($ticket);

        $ticket->refresh();
        $this->assertEquals('QUEUED', $ticket->status);
        $this->assertEquals('QUEUED', $ticket->condition);
        $this->assertNull($ticket->assigned_to_user_id);
    }

    /**
     * Test 4: Round-robin tiap divisi berputar merata dan terpisah.
     */
    public function test_round_robin_index_is_separate_per_division()
    {
        // 2 agents in 'besfixed'
        $bfAgent1 = User::factory()->create(['role' => 'agent', 'status' => 'active', 'campaign' => 'besfixed']);
        $bfAgent1->workSessions()->create(['work_date' => today(), 'status' => 'online']);
        $bfAgent2 = User::factory()->create(['role' => 'agent', 'status' => 'active', 'campaign' => 'besfixed']);
        $bfAgent2->workSessions()->create(['work_date' => today(), 'status' => 'online']);

        // 2 agents in 'saltik'
        $stAgent1 = User::factory()->create(['role' => 'agent', 'status' => 'active', 'campaign' => 'saltik']);
        $stAgent1->workSessions()->create(['work_date' => today(), 'status' => 'online']);
        $stAgent2 = User::factory()->create(['role' => 'agent', 'status' => 'active', 'campaign' => 'saltik']);
        $stAgent2->workSessions()->create(['work_date' => today(), 'status' => 'online']);

        // Sort by ID to ensure order
        $bfAgents = [$bfAgent1, $bfAgent2];
        usort($bfAgents, fn($a, $b) => $a->id <=> $b->id);
        $stAgents = [$stAgent1, $stAgent2];
        usort($stAgents, fn($a, $b) => $a->id <=> $b->id);

        // Ticket 1: besfixed -> should get $bfAgents[0]
        $t1 = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Test Customer BF 1',
            'division_target' => 'besfixed',
        ]);
        $this->router->autoAssignToAgent($t1);
        $this->assertEquals($bfAgents[0]->id, $t1->refresh()->assigned_to_user_id);

        // Ticket 2: saltik -> should get $stAgents[0] (since index is separate and starting at 0)
        $t2 = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Test Customer ST 1',
            'division_target' => 'saltik',
        ]);
        $this->router->autoAssignToAgent($t2);
        $this->assertEquals($stAgents[0]->id, $t2->refresh()->assigned_to_user_id);

        // Ticket 3: besfixed -> should get $bfAgents[1] (increments to 1)
        $t3 = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Test Customer BF 2',
            'division_target' => 'besfixed',
        ]);
        $this->router->autoAssignToAgent($t3);
        $this->assertEquals($bfAgents[1]->id, $t3->refresh()->assigned_to_user_id);

        // Ticket 4: saltik -> should get $stAgents[1]
        $t4 = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Test Customer ST 2',
            'division_target' => 'saltik',
        ]);
        $this->router->autoAssignToAgent($t4);
        $this->assertEquals($stAgents[1]->id, $t4->refresh()->assigned_to_user_id);
    }

    /**
     * Test 5: Fallback bila division_target bisa null.
     */
    public function test_fallback_when_division_target_is_null_or_empty()
    {
        $bfAgent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
            'campaign' => 'besfixed',
        ]);
        $bfAgent->workSessions()->create([
            'work_date' => today(),
            'status' => 'online',
        ]);

        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Test Null Division',
            'division_target' => null,
            'urgency_level' => 1,
            'status' => 'QUEUED',
            'condition' => 'QUEUED',
        ]);

        $assigned = $this->router->autoAssignToAgent($ticket);
        $this->assertTrue($assigned);
        $ticket->refresh();
        $this->assertEquals($bfAgent->id, $ticket->assigned_to_user_id);
    }

    /**
     * Test 6: Verify round-robin increment is atomic and works sequentially.
     */
    public function test_round_robin_index_increments_atomically()
    {
        $agent1 = User::factory()->create(['role' => 'agent', 'status' => 'active', 'campaign' => 'besfixed']);
        $agent1->workSessions()->create(['work_date' => today(), 'status' => 'online']);
        $agent2 = User::factory()->create(['role' => 'agent', 'status' => 'active', 'campaign' => 'besfixed']);
        $agent2->workSessions()->create(['work_date' => today(), 'status' => 'online']);

        $ticket1 = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Test BF 1',
            'division_target' => 'besfixed',
        ]);

        $this->router->autoAssignToAgent($ticket1);

        // Check if the setting is created and has value 1
        $setting = \App\Models\Setting::where('key', 'rr_index_besfixed')->first();
        $this->assertNotNull($setting);
        $this->assertEquals('1', $setting->value);

        $ticket2 = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Test BF 2',
            'division_target' => 'besfixed',
        ]);

        $this->router->autoAssignToAgent($ticket2);

        $setting->refresh();
        $this->assertEquals('0', $setting->value); // Wrapped around since there are only 2 agents
    }
}
