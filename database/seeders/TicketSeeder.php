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

        foreach ($tickets as $ticketData) {
            Ticket::create($ticketData);
        }

        foreach ($teamLeaderTickets as $ticketData) {
            Ticket::create($ticketData);
        }

        echo "Tickets seeded successfully!\n";
    }
}
