<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_reports_index_page()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.index'));

        $response->assertOk();
    }

    public function test_index_page_summary_counts_follow_activity_dates()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $agent = User::factory()->create([
            'role' => 'agent',
            'name' => 'Agent Summary',
            'status' => 'active',
        ]);

        $ticket = Ticket::create([
            'datereport' => now()->subDay(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135920',
            'namacust' => 'Customer Summary',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
        ]);
        $ticket->forceFill([
            'created_at' => now(),
            'updated_at' => now(),
        ])->save();

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.index'));

        $response->assertOk();
        $response->assertViewHas('users', function ($users) use ($agent) {
            $user = $users->firstWhere('id', $agent->id);
            return $user && $user->assigned_tickets == 1 && $user->inbox_tickets == 1 && $user->solved_tickets == 0;
        });
    }

    public function test_admin_can_view_tickets_report_page_and_apply_filters()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $agent = User::factory()->create([
            'role' => 'agent',
            'name' => 'Agent A',
            'status' => 'active',
        ]);

        $otherAgent = User::factory()->create([
            'role' => 'agent',
            'name' => 'Agent B',
            'status' => 'active',
        ]);

        // 1. Create a ticket assigned to Agent A, with activity 2 days ago
        $ticketA = Ticket::create([
            'datereport' => now()->subDays(2),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Customer A',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
        ]);
        $ticketA->forceFill([
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ])->save();

        // 2. Create a ticket assigned to Agent B, with activity today
        $ticketB = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135918',
            'namacust' => 'Customer B',
            'status' => 'Closed',
            'condition' => 'Closed',
            'assigned_to_user_id' => $otherAgent->id,
            'division_target' => 'besfixed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // --- Filter by Date (activity dates: created_at / updated_at) ---
        $response = $this->actingAs($admin)
            ->get(route('admin.reports.tickets', [
                'date_from' => now()->subDay()->toDateString(),
                'date_to' => now()->toDateString(),
            ]));

        $response->assertOk();
        $response->assertViewHas('tickets', function ($tickets) use ($ticketA, $ticketB) {
            return $tickets->getCollection()->contains('idTicket', $ticketB->idTicket) &&
                   !$tickets->getCollection()->contains('idTicket', $ticketA->idTicket);
        });

        // --- Filter by Status (condition) ---
        $response = $this->actingAs($admin)
            ->get(route('admin.reports.tickets', [
                'status' => 'Closed',
            ]));

        $response->assertOk();
        $response->assertViewHas('tickets', function ($tickets) use ($ticketA, $ticketB) {
            return !$tickets->getCollection()->contains('idTicket', $ticketA->idTicket) &&
                   $tickets->getCollection()->contains('idTicket', $ticketB->idTicket);
        });

        // --- Filter by Agent ---
        $response = $this->actingAs($admin)
            ->get(route('admin.reports.tickets', [
                'agent_id' => $agent->id,
                'date_from' => now()->subDays(10)->toDateString(),
                'date_to' => now()->toDateString(),
            ]));

        $response->assertOk();
        $response->assertViewHas('tickets', function ($tickets) use ($ticketA, $ticketB) {
            return $tickets->getCollection()->contains('idTicket', $ticketA->idTicket) &&
                   !$tickets->getCollection()->contains('idTicket', $ticketB->idTicket);
        });
    }

    public function test_admin_can_filter_tickets_report_by_ticket_id()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $ticketA = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Customer A',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'division_target' => 'besfixed',
        ]);

        $ticketB = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135918',
            'namacust' => 'Customer B',
            'status' => 'Closed',
            'condition' => 'Closed',
            'division_target' => 'besfixed',
        ]);

        // Filter by ticket ID (exact/partial)
        $response = $this->actingAs($admin)
            ->get(route('admin.reports.tickets', [
                'ticket_id' => $ticketA->idTicket,
            ]));

        $response->assertOk();
        $response->assertViewHas('tickets', function ($tickets) use ($ticketA, $ticketB) {
            return $tickets->getCollection()->contains('idTicket', $ticketA->idTicket) &&
                   !$tickets->getCollection()->contains('idTicket', $ticketB->idTicket);
        });
    }


    public function test_admin_can_filter_tickets_report_by_keyword()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Ticket A has namacust = 'Budi Santoso'
        $ticketA = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135917',
            'namacust' => 'Budi Santoso',
            'status' => 'ASSIGNED',
            'condition' => 'ASSIGNED',
            'division_target' => 'besfixed',
        ]);

        // Ticket B has detailticket = 'Koneksi lambat dan putus-putus'
        $ticketB = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135918',
            'namacust' => 'Rina Wijaya',
            'detailticket' => 'Koneksi lambat dan putus-putus',
            'status' => 'Closed',
            'condition' => 'Closed',
            'division_target' => 'besfixed',
        ]);

        // Search for keyword 'Budi'
        $response = $this->actingAs($admin)
            ->get(route('admin.reports.tickets', [
                'keyword' => 'Budi',
            ]));

        $response->assertOk();
        $response->assertViewHas('tickets', function ($tickets) use ($ticketA, $ticketB) {
            return $tickets->getCollection()->contains('idTicket', $ticketA->idTicket) &&
                   !$tickets->getCollection()->contains('idTicket', $ticketB->idTicket);
        });

        // Search for keyword 'putus-putus'
        $response = $this->actingAs($admin)
            ->get(route('admin.reports.tickets', [
                'keyword' => 'putus-putus',
            ]));

        $response->assertOk();
        $response->assertViewHas('tickets', function ($tickets) use ($ticketA, $ticketB) {
            return !$tickets->getCollection()->contains('idTicket', $ticketA->idTicket) &&
                   $tickets->getCollection()->contains('idTicket', $ticketB->idTicket);
        });
    }

    public function test_admin_reports_include_dispatched_ticket_in_list_when_filtering_by_activity_date()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $agent = User::factory()->create(['role' => 'agent', 'name' => 'Agent Dispatched']);

        $ticket = Ticket::create([
            'datereport' => now()->subDay(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135919',
            'namacust' => 'Customer Dispatched',
            'status' => 'DISPATCHED',
            'condition' => 'Dispatched',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.tickets', [
                'status' => 'Dispatched',
                'date_from' => now()->subDay()->toDateString(),
                'date_to' => now()->toDateString(),
            ]));

        $response->assertOk();
        $response->assertViewHas('tickets', function ($tickets) use ($ticket) {
            return $tickets->getCollection()->contains('idTicket', $ticket->idTicket);
        });
    }

    public function test_user_detail_endpoint_returns_dispatched_tickets_for_selected_range()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $agent = User::factory()->create(['role' => 'agent', 'name' => 'Agent Detail']);

        $dispatchedTicket = Ticket::create([
            'datereport' => now()->subDays(2),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135920',
            'namacust' => 'Customer Detail',
            'status' => 'DISPATCHED',
            'condition' => 'Dispatched',
            'assigned_to_user_id' => $agent->id,
            'division_target' => 'besfixed',
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.user', ['user' => $agent->id]), [
                'date_from' => now()->subDay()->toDateString(),
                'date_to' => now()->toDateString(),
            ]);

        $response->assertOk();
        $response->assertJsonPath('dispatched_count', 1);
        $response->assertJsonCount(1, 'dispatched_tickets');
        $response->assertJsonPath('dispatched_tickets.0.idTicket', $dispatchedTicket->idTicket);
    }

    public function test_admin_can_export_user_reports_to_excel()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.export.users'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_admin_can_export_tickets_to_excel()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.export.tickets'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
