<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = [
            [
                'datereport' => now()->toDateString(),
                'jenisTicket' => 'Internet',
                'notelpCust' => '081234567890',
                'namacust' => 'John Doe',
                'reportedpriority' => 'Super Emergency',
                'datesolved' => now()->toDateString(),
                'THT' => now()->toDateTimeString(),
                'status' => 'QUEUED',
                'regional' => 'Regional 1 - Sumatera',
                'witel' => 'Medan',
                'lapul' => 1,
                'gaul' => 0,
                'condition' => 'Closed',
            ],
            [
                'datereport' => now()->toDateString(),
                'jenisTicket' => 'Internet',
                'notelpCust' => '081234567891',
                'namacust' => 'Jane Smith',
                'reportedpriority' => 'Very High',
                'datesolved' => null,
                'THT' => now()->toDateTimeString(),
                'status' => 'QUEUED',
                'regional' => 'Regional 2 - Jawa',
                'witel' => 'Surabaya',
                'lapul' => 0,
                'gaul' => 0,
                'condition' => 'Open',
            ],
            [
                'datereport' => now()->toDateString(),
                'jenisTicket' => 'Telephone',
                'notelpCust' => '081234567892',
                'namacust' => 'Bob Johnson',
                'reportedpriority' => 'Medium',
                'datesolved' => null,
                'THT' => now()->toDateTimeString(),
                'status' => 'QUEUED',
                'regional' => 'Regional 3 - Kalimantan & Sulawesi',
                'witel' => 'Balikpapan',
                'lapul' => 0,
                'gaul' => 0,
                'condition' => 'Closed',
            ],
            [
                'datereport' => now()->toDateString(),
                'jenisTicket' => 'Internet',
                'notelpCust' => '081234567893',
                'namacust' => 'Alice Brown',
                'reportedpriority' => 'Low',
                'datesolved' => null,
                'THT' => now()->toDateTimeString(),
                'status' => 'QUEUED',
                'regional' => 'Regional 4 - Maluku & Papua',
                'witel' => 'Jayapura',
                'lapul' => 0,
                'gaul' => 0,
                'condition' => 'In Progress',
            ],
            [
                'datereport' => now()->toDateString(),
                'jenisTicket' => 'Internet',
                'notelpCust' => '081234567894',
                'namacust' => 'Charlie Wilson',
                'reportedpriority' => 'Super Emergency',
                'datesolved' => null,
                'THT' => now()->toDateTimeString(),
                'status' => 'QUEUED',
                'regional' => 'Regional 1 - Sumatera',
                'witel' => 'Palembang',
                'lapul' => 1,
                'gaul' => 0,
                'condition' => 'Open',
            ],
            [
                'datereport' => now()->toDateString(),
                'jenisTicket' => 'Internet',
                'notelpCust' => '081234567895',
                'namacust' => 'Diana Prince',
                'reportedpriority' => 'Very High',
                'datesolved' => now()->toDateString(),
                'THT' => now()->toDateTimeString(),
                'status' => 'QUEUED',
                'regional' => 'Regional 2 - Jawa',
                'witel' => 'Bandung',
                'lapul' => 0,
                'gaul' => 0,
                'condition' => 'Closed',
            ],
            [
                'datereport' => now()->toDateString(),
                'jenisTicket' => 'Telephone',
                'notelpCust' => '081234567896',
                'namacust' => 'Edward Norton',
                'reportedpriority' => 'Medium',
                'datesolved' => null,
                'THT' => now()->toDateTimeString(),
                'status' => 'QUEUED',
                'regional' => 'Regional 3 - Kalimantan & Sulawesi',
                'witel' => 'Makassar',
                'lapul' => 0,
                'gaul' => 0,
                'condition' => 'Closed',
            ],
            [
                'datereport' => now()->toDateString(),
                'jenisTicket' => 'Internet',
                'notelpCust' => '081234567897',
                'namacust' => 'Fiona Apple',
                'reportedpriority' => 'Low',
                'datesolved' => null,
                'THT' => now()->toDateTimeString(),
                'status' => 'QUEUED',
                'regional' => 'Regional 4 - Maluku & Papua',
                'witel' => 'Ambon',
                'lapul' => 0,
                'gaul' => 0,
                'condition' => 'In Progress',
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::create($ticket);
        }
    }
}
