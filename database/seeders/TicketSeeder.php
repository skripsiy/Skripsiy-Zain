<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agent = User::where('email', 'agent@xena.com')->first();
        $teamLeader = User::where('email', 'teamleader@xena.com')->first();
        
        if (!$agent || !$teamLeader) {
            echo "Users not found. Please run UserSeeder first.\n";
            return;
        }

        // Create tickets for agent
        $tickets = [
            [
                'datereport' => Carbon::now()->subDays(5),
                'jenisTicket' => 'GANGGUAN INTERNET',
                'notelpCust' => '081234567890',
                'namacust' => 'John Doe',
                'detailticket' => 'Internet tidak bisa connect',
                'status' => 'QUEUED',
                'assignby' => $agent->email,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
            ],
            [
                'datereport' => Carbon::now()->subDays(4),
                'jenisTicket' => 'GANGGUAN TELEPON',
                'notelpCust' => '081234567891',
                'namacust' => 'Jane Smith',
                'detailticket' => 'Telepon tidak bisa digunakan',
                'status' => 'QUEUED',
                'assignby' => $agent->email,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
            ],
            [
                'datereport' => Carbon::now()->subDays(3),
                'jenisTicket' => 'GANGGUAN TV',
                'notelpCust' => '081234567892',
                'namacust' => 'Bob Johnson',
                'detailticket' => 'TV tidak ada sinyal',
                'status' => 'IN PROGRESS',
                'assignby' => $agent->email,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
            ],
            [
                'datereport' => Carbon::now()->subDays(2),
                'jenisTicket' => 'GANGGUAN INTERNET',
                'notelpCust' => '081234567893',
                'namacust' => 'Alice Brown',
                'detailticket' => 'Kecepatan internet lambat',
                'status' => 'SOLVED',
                'assignby' => $agent->email,
                'solvedby' => $agent->email,
                'datesolved' => Carbon::now()->subDays(1),
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
            ],
            [
                'datereport' => Carbon::now()->subDays(1),
                'jenisTicket' => 'GANGGUAN TELEPON',
                'notelpCust' => '081234567894',
                'namacust' => 'Charlie Wilson',
                'detailticket' => 'Tidak bisa telepon keluar',
                'status' => 'SOLVED',
                'assignby' => $agent->email,
                'solvedby' => $agent->email,
                'datesolved' => Carbon::now(),
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
            ],
        ];

        // Create tickets for team leader
        $teamLeaderTickets = [
            [
                'datereport' => Carbon::now()->subDays(3),
                'jenisTicket' => 'GANGGUAN INTERNET',
                'notelpCust' => '081234567895',
                'namacust' => 'David Lee',
                'detailticket' => 'Internet putus-putus',
                'status' => 'QUEUED',
                'assignby' => $teamLeader->email,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
            ],
            [
                'datereport' => Carbon::now()->subDays(2),
                'jenisTicket' => 'GANGGUAN TV',
                'notelpCust' => '081234567896',
                'namacust' => 'Eva Martinez',
                'detailticket' => 'Channel TV tidak lengkap',
                'status' => 'IN PROGRESS',
                'assignby' => $teamLeader->email,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
            ],
        ];

        // Create SUPER EMERGENCY tickets
        $superEmergencyTickets = [
            [
                'datereport' => Carbon::now(),
                'jenisTicket' => 'GANGGUAN INTERNET',
                'notelpCust' => '081234567897',
                'namacust' => 'CRITICAL USER - PT Bank Mandiri',
                'detailticket' => 'Seluruh jaringan internet kantor pusat down, urgent untuk transaksi banking',
                'reportedpriority' => 'SUPER EMERGENCY',
                'status' => 'QUEUED',
                'assignby' => $agent->email,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'escalationStatus' => 'URGENT',
                'THT' => Carbon::now()->addHours(1),
            ],
            [
                'datereport' => Carbon::now()->subMinutes(30),
                'jenisTicket' => 'GANGGUAN TELEPON',
                'notelpCust' => '081234567898',
                'namacust' => 'CRITICAL USER - RS Hasan Sadikin',
                'detailticket' => 'Sistem telepon rumah sakit mati total, mengganggu operasional emergency',
                'reportedpriority' => 'SUPER EMERGENCY',
                'status' => 'IN PROGRESS',
                'assignby' => $agent->email,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'escalationStatus' => 'CRITICAL',
                'THT' => Carbon::now()->addMinutes(30),
            ],
            [
                'datereport' => Carbon::now()->subHours(2),
                'jenisTicket' => 'GANGGUAN INTERNET',
                'notelpCust' => '081234567899',
                'namacust' => 'CRITICAL USER - Polda Jabar',
                'detailticket' => 'Koneksi internet pusat komando mati, butuh penanganan segera',
                'reportedpriority' => 'SUPER EMERGENCY',
                'status' => 'QUEUED',
                'assignby' => $teamLeader->email,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'escalationStatus' => 'URGENT',
                'THT' => Carbon::now()->addMinutes(45),
            ],
            [
                'datereport' => Carbon::now()->subDays(1),
                'jenisTicket' => 'GANGGUAN TV',
                'notelpCust' => '081234567800',
                'namacust' => 'CRITICAL USER - TVRI Jabar',
                'detailticket' => 'Gangguan siaran TV nasional, segera ditangani',
                'reportedpriority' => 'SUPER EMERGENCY',
                'status' => 'SOLVED',
                'assignby' => $agent->email,
                'solvedby' => $agent->email,
                'datesolved' => Carbon::now()->subHours(2),
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'escalationStatus' => 'RESOLVED',
            ],
        ];

        foreach ($tickets as $ticketData) {
            Ticket::create($ticketData);
        }

        foreach ($teamLeaderTickets as $ticketData) {
            Ticket::create($ticketData);
        }

        foreach ($superEmergencyTickets as $ticketData) {
            Ticket::create($ticketData);
        }

        echo "Tickets seeded successfully!\n";
        echo "Total tickets created: " . (count($tickets) + count($teamLeaderTickets) + count($superEmergencyTickets)) . "\n";
        echo "Super Emergency tickets created: " . count($superEmergencyTickets) . "\n";
    }
}
