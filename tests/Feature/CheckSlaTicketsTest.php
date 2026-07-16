<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\SlaBreachNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CheckSlaTicketsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Tiket SLA breach memicu notifikasi ke TL divisi terkait dan mengupdate flag sla_notified.
     */
    public function test_command_notifies_relevant_team_leaders_and_updates_flag()
    {
        Notification::fake();

        // 1. Team Leader untuk divisi 'saltik'
        $tlSaltik = User::factory()->create([
            'role' => 'team_leader',
            'status' => 'active',
            'campaign' => 'saltik',
        ]);

        // 2. Team Leader untuk divisi 'area' (harusnya tidak menerima)
        $tlArea = User::factory()->create([
            'role' => 'team_leader',
            'status' => 'active',
            'campaign' => 'area',
        ]);

        // 3. Tiket saltik yang lewat 6 jam (7 jam ago)
        $ticket = Ticket::create([
            'datereport' => now()->subHours(7),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Customer A',
            'condition' => 'QUEUED',
            'status' => 'QUEUED',
            'division_target' => 'saltik',
            'sla_notified' => false,
        ]);

        $this->artisan('tickets:check-sla')
            ->assertExitCode(0);

        // Pengecekan Notifikasi
        Notification::assertSentTo($tlSaltik, SlaBreachNotification::class, function ($notification) use ($ticket) {
            return $notification->ticket->idTicket === $ticket->idTicket;
        });

        Notification::assertNotSentTo($tlArea, SlaBreachNotification::class);

        // Pengecekan Database Flag
        $ticket->refresh();
        $this->assertTrue((bool) $ticket->sla_notified);
    }

    /**
     * Test 2: Tiket berstatus Closed diabaikan oleh SLA checker.
     */
    public function test_command_ignores_closed_tickets()
    {
        Notification::fake();

        $tl = User::factory()->create([
            'role' => 'team_leader',
            'status' => 'active',
            'campaign' => 'saltik',
        ]);

        $ticket = Ticket::create([
            'datereport' => now()->subHours(7),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Customer Closed',
            'condition' => 'Closed',
            'status' => 'Closed',
            'division_target' => 'saltik',
            'sla_notified' => false,
        ]);

        $this->artisan('tickets:check-sla')->assertExitCode(0);

        Notification::assertNothingSent();

        $ticket->refresh();
        $this->assertFalse((bool) $ticket->sla_notified);
    }

    /**
     * Test 3: Tiket yang sudah ditandai sla_notified = true tidak diproses ulang.
     */
    public function test_command_does_not_double_alert()
    {
        Notification::fake();

        $tl = User::factory()->create([
            'role' => 'team_leader',
            'status' => 'active',
            'campaign' => 'saltik',
        ]);

        $ticket = Ticket::create([
            'datereport' => now()->subHours(7),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Customer Notified',
            'condition' => 'QUEUED',
            'status' => 'QUEUED',
            'division_target' => 'saltik',
            'sla_notified' => true,
        ]);

        $this->artisan('tickets:check-sla')->assertExitCode(0);

        Notification::assertNothingSent();
    }

    /**
     * Test 4: Bila tidak ada TL divisi terkait, notifikasi dikirim ke seluruh TL (fallback).
     */
    public function test_fallback_to_all_team_leaders_when_no_matching_division()
    {
        Notification::fake();

        // Tidak ada TL dengan campaign 'saltik', hanya ada TL area
        $tlArea = User::factory()->create([
            'role' => 'team_leader',
            'status' => 'active',
            'campaign' => 'area',
        ]);

        $ticket = Ticket::create([
            'datereport' => now()->subHours(7),
            'jenisTicket' => 'INTERNET',
            'namacust' => 'Customer Fallback',
            'condition' => 'QUEUED',
            'status' => 'QUEUED',
            'division_target' => 'saltik',
            'sla_notified' => false,
        ]);

        $this->artisan('tickets:check-sla')->assertExitCode(0);

        // Fallback: tlArea harus menerima notifikasi
        Notification::assertSentTo($tlArea, SlaBreachNotification::class);
    }
}
