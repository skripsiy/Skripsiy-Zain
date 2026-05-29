<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TicketSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to allow truncation
        Schema::disableForeignKeyConstraints();
        
        Ticket::truncate();
        DB::table('activity_log')->truncate(); // Clear activity logs
        
        Schema::enableForeignKeyConstraints();

        $agents = User::where('role', 'agent')->pluck('name')->toArray();
        if (empty($agents)) {
            $agents = ['Agent 1', 'Agent 2', 'Agent 3'];
        }

        $topics = ['LOS Merah', 'Koneksi Lambat', 'Router Blank', 'Biling Tagihan', 'Putus-putus'];
        $conditions = ['QUEUED', 'In Progress', 'Closed'];

        for ($i = 1; $i <= 100; $i++) {
            $date = Carbon::now()->subDays(rand(0, 10))->subHours(rand(0, 24));
            $condition = $conditions[array_rand($conditions)];
            $isClosed = $condition === 'Closed';
            $agentAssigned = $agents[array_rand($agents)];
            
            Ticket::create([
                'datereport' => $date,
                'jenisTicket' => 'INTERNET',
                'notelpCust' => '0812' . rand(10000000, 99999999),
                'namacust' => 'Pelanggan ' . $i,
                'idlaporan' => rand(300000, 399999),
                'detailticket' => 'Gangguan koneksi nomor ' . $i,
                'resume' => 'Pelanggan melaporkan gangguan melalui Call Center. Segera lakukan pengecekan.',
                'topic' => $topics[array_rand($topics)],
                'noSC' => 'SC' . rand(1000000, 9999999),
                'statusSC' => $isClosed ? 'Closed' : 'Open',
                'contact' => 'Telepon',
                'reportedpriority' => 'High',
                'condition' => $condition,
                'assignby' => $agentAssigned,
                'solvedby' => $isClosed ? $agentAssigned : null,
                'hasil_pengecekan' => $isClosed ? 'Pengecekan port sisi ODP normal. Reset sisi OLT sukses. Koneksi pelanggan kembali Up.' : 'Masih dalam proses eskalasi regu teknisi.',
                'resolved_by_agent' => $isClosed ? $agentAssigned : null,
                'eksalasiVia' => 'Telegram',
            ]);
        }
    }
}
