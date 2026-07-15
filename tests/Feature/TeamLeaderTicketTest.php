<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamLeaderTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_leader_can_view_and_manage_tickets()
    {
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
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

        // 1. View Dashboard (F-13)
        $response = $this->actingAs($teamLeader)
            ->get(route('team-leader.dashboard'));

        $response->assertOk();

        // 2. View Tickets List (F-14)
        $response = $this->actingAs($teamLeader)
            ->get(route('team-leader.tickets'));

        $response->assertOk();

        // 3. View Ticket Detail & Activities (F-14 & F-17)
        $response = $this->actingAs($teamLeader)
            ->get(route('team-leader.ticket.detail', $ticket->idTicket));

        $response->assertOk();
        $response->assertViewHas('activities');

        // 4. Update Ticket Info (F-15)
        $response = $this->actingAs($teamLeader)
            ->post(route('team-leader.ticket.update', $ticket->idTicket), [
                'resume' => 'Resolved',
                'klasifikasi' => 'Technical',
                'topic' => 'Internet Issue',
                'topicDetail' => 'Slow Connection',
                'noSC' => 'SC001',
                'statusSC' => 'Closed',
                'validateClose' => 'Yes',
                'reasonnoODS' => 'PEOPLE - Menunggu Konfirmasi Dari Pelanggan',
                'eksalasiTicket' => 'Yes',
                'eksalasiVia' => 'Telegram',
                'PIC' => 'TEKNISI',
                'contact' => 'Contacted',
                'responBE' => 'Hanya di Read saja',
                'description' => 'Updated ticket details by TL',
            ]);

        $response->assertRedirect(route('team-leader.ticket.detail', $ticket->idTicket));
        $response->assertSessionHas('success', 'Tiket berhasil di-dispatch ke tim terkait.');
        $this->assertEquals('Updated ticket details by TL', $ticket->fresh()->description);
        $this->assertEquals('DISPATCHED', $ticket->fresh()->status);
        $this->assertEquals('Dispatched', $ticket->fresh()->condition);
        $this->assertCount(1, $ticket->fresh()->escalations);
        $this->assertEquals('TEKNISI', $ticket->fresh()->pic);

        // 5. Update Ticket Status to Closed (F-15)
        $response = $this->actingAs($teamLeader)
            ->post(route('team-leader.ticket.status', $ticket->idTicket), [
                'action' => 'closed',
            ]);

        $response->assertRedirect(route('team-leader.ticket.detail', $ticket->idTicket));
        $response->assertSessionHas('success', 'Ticket has been closed successfully!');
        $this->assertEquals('Closed', $ticket->fresh()->status);
        $this->assertEquals('Closed', $ticket->fresh()->condition);
        $this->assertEquals($teamLeader->id, $ticket->fresh()->solved_by_user_id);
    }

    public function test_team_leader_can_export_tickets_to_excel()
    {
        $teamLeader = User::factory()->create([
            'role' => 'team_leader',
        ]);

        $response = $this->actingAs($teamLeader)
            ->get(route('team-leader.tickets', ['export' => 'excel']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_team_leader_can_dispatch_non_technical_ticket_with_nullable_status_sc()
    {
        $teamLeader = User::factory()->create(['role' => 'team_leader']);
        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Customer B',
            'status' => 'QUEUED',
            'condition' => 'QUEUED',
            'division_target' => 'besfixed',
        ]);

        $response = $this->actingAs($teamLeader)
            ->from(route('team-leader.ticket.detail', $ticket->idTicket))
            ->post(route('team-leader.ticket.update', $ticket->idTicket), [
                'resume' => 'Resolved',
                'klasifikasi' => 'Non-Technical',
                'topic' => 'Internet Issue',
                'topicDetail' => 'Slow Connection',
                'noSC' => 'SC001',
                'statusSC' => '', // statusSC is optional (nullable)
                'validateClose' => 'Yes',
                'reasonnoODS' => 'PEOPLE - Menunggu Konfirmasi Dari Pelanggan',
                'eksalasiTicket' => 'Yes',
                'eksalasiVia' => 'Telegram',
                'PIC' => 'BESFIXED', // Valid Non-Technical team
                'contact' => 'Contacted',
                'responBE' => 'Hanya di Read saja',
                'description' => 'Updated ticket details by TL',
            ]);

        $response->assertRedirect(route('team-leader.ticket.detail', $ticket->idTicket));
        $response->assertSessionHas('success', 'Tiket berhasil di-dispatch ke tim terkait.');
        
        $ticket->refresh();
        $this->assertEquals('BESFIXED', $ticket->pic);
        $this->assertNull($ticket->statusSC);
    }

    public function test_team_leader_cannot_dispatch_with_invalid_pic_key_for_classification()
    {
        $teamLeader = User::factory()->create(['role' => 'team_leader']);
        $ticket = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Customer C',
            'status' => 'QUEUED',
            'condition' => 'QUEUED',
            'division_target' => 'besfixed',
        ]);

        // Attempting Technical classification but using non-technical team key BESFIXED
        $response = $this->actingAs($teamLeader)
            ->from(route('team-leader.ticket.detail', $ticket->idTicket))
            ->post(route('team-leader.ticket.update', $ticket->idTicket), [
                'resume' => 'Resolved',
                'klasifikasi' => 'Technical',
                'topic' => 'Internet Issue',
                'topicDetail' => 'Slow Connection',
                'noSC' => 'SC001',
                'statusSC' => 'Closed',
                'validateClose' => 'Yes',
                'reasonnoODS' => 'PEOPLE - Menunggu Konfirmasi Dari Pelanggan',
                'eksalasiTicket' => 'Yes',
                'eksalasiVia' => 'Telegram',
                'PIC' => 'BESFIXED', // Invalid: BESFIXED is not a technical team
                'contact' => 'Contacted',
                'responBE' => 'Hanya di Read saja',
                'description' => 'Updated ticket details by TL',
            ]);

        $response->assertRedirect(route('team-leader.ticket.detail', $ticket->idTicket));
        $response->assertSessionHasErrors(['PIC']);
    }
}
