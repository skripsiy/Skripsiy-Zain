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

        // 1. Create a ticket assigned to Agent A, reported 2 days ago
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

        // 2. Create a ticket assigned to Agent B, reported today
        $ticketB = Ticket::create([
            'datereport' => now(),
            'jenisTicket' => 'INTERNET',
            'notelpCust' => '081294135918',
            'namacust' => 'Customer B',
            'status' => 'Closed',
            'condition' => 'Closed',
            'assigned_to_user_id' => $otherAgent->id,
            'division_target' => 'besfixed',
        ]);

        // --- Filter by Date (date_from / date_to) ---
        $response = $this->actingAs($admin)
            ->get(route('admin.reports.tickets', [
                'date_from' => now()->subDay()->toDateString(),
                'date_to' => now()->toDateString(),
            ]));

        $response->assertOk();
        $response->assertViewHas('tickets', function ($tickets) use ($ticketA, $ticketB) {
            return !$tickets->getCollection()->contains('idTicket', $ticketA->idTicket) &&
                   $tickets->getCollection()->contains('idTicket', $ticketB->idTicket);
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
