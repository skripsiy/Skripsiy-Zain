<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentTicketUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_can_update_assigned_ticket_and_view_activity_logs()
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
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
        ]);

        // 1. Agent updates ticket info (F-21)
        $response = $this->actingAs($agent)
            ->post(route('agent.ticket.update', $ticket->idTicket), [
                'description' => 'Updated by Agent',
                'hasil_pengecekan' => 'All looks good',
            ]);

        $response->assertRedirect(route('agent.ticket.detail', $ticket->idTicket));
        $response->assertSessionHas('success', 'Ticket updated successfully!');
        $this->assertEquals('Updated by Agent', $ticket->fresh()->description);

        // 2. View Detail & Activity logs (F-22)
        $response = $this->actingAs($agent)
            ->get(route('agent.ticket.detail', $ticket->idTicket));

        $response->assertOk();
        $response->assertViewHas('activities');

        // 3. Agent updates ticket status to closed (F-21)
        $response = $this->actingAs($agent)
            ->post(route('agent.ticket.status', $ticket->idTicket), [
                'action' => 'closed',
            ]);

        $response->assertRedirect(route('agent.ticket.detail', $ticket->idTicket));
        $response->assertSessionHas('success', 'Status tiket berhasil diperbarui!');
        $this->assertEquals('Closed', $ticket->fresh()->status);
        $this->assertEquals('Closed', $ticket->fresh()->condition);
        $this->assertEquals($agent->id, $ticket->fresh()->solved_by_user_id);
    }

    public function test_agent_cannot_update_unassigned_ticket()
    {
        $agent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        $otherAgent = User::factory()->create([
            'role' => 'agent',
            'status' => 'active',
        ]);

        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Customer A',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $otherAgent->id,
            'division_target' => 'besfixed',
        ]);

        // Attempt update (should fail)
        $response = $this->actingAs($agent)
            ->post(route('agent.ticket.update', $ticket->idTicket), [
                'description' => 'Cheeky agent trying to write',
            ]);

        $response->assertRedirect(route('agent.ticket.detail', $ticket->idTicket));
        $response->assertSessionHas('error');
        $this->assertNotEquals('Cheeky agent trying to write', $ticket->fresh()->description);
    }

    public function test_agent_can_update_with_valid_classification_and_pic()
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
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
        ]);

        $response = $this->actingAs($agent)
            ->post(route('agent.ticket.update', $ticket->idTicket), [
                'klasifikasi' => 'Technical',
                'PIC' => 'TEKNISI',
            ]);

        $response->assertRedirect(route('agent.ticket.detail', $ticket->idTicket));
        $response->assertSessionHas('success');
        $this->assertEquals('TEKNISI', $ticket->fresh()->pic);
    }

    public function test_agent_cannot_update_with_invalid_pic_for_classification()
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
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
        ]);

        $response = $this->actingAs($agent)
            ->post(route('agent.ticket.update', $ticket->idTicket), [
                'klasifikasi' => 'Technical',
                'PIC' => 'BESFIXED', // Invalid for Technical classification
            ]);

        $response->assertSessionHasErrors(['PIC']);
    }
}
