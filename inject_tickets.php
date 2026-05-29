<?php

use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// Hapus semua tiket lama
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
Ticket::truncate();
DB::table('activity_log')->truncate(); // clear logs for fresh start
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

$agents = User::where('role', 'agent')->pluck('name')->toArray();
if (empty($agents)) {
    $agents = ['Today User 5', 'Agent 1', 'Agent 2'];
}

$topics = ['LOS Merah', 'Koneksi Lambat', 'Router Blank', 'Biling Tagihan', 'Putus-putus'];
$priorities = ['Super Emergency', 'Emergency', 'High', 'Medium'];

echo "Mulai menginject tiket...\n";

// =========================================================
// BAGIAN 1: 30 tiket QUEUED belum di-assign (untuk Team Leader)
// Tiket ini muncul di halaman Assign Team Leader
// Syarat: status = 'QUEUED', assignby = null, priority = emergency
// =========================================================
echo ">> Membuat 30 tiket QUEUED untuk Team Leader assign...\n";

for ($i = 1; $i <= 30; $i++) {
    $date = Carbon::now()->subDays(rand(0, 5))->subHours(rand(0, 12));
    // Prioritas emergency agar muncul di halaman assign team leader
    $priority = $priorities[array_rand(array_slice($priorities, 0, 2))]; // Super Emergency atau Emergency

    Ticket::create([
        'datereport'       => $date,
        'jenisTicket'      => 'INTERNET',
        'notelpCust'       => '0812' . rand(10000000, 99999999),
        'namacust'         => 'Pelanggan Baru ' . $i,
        'idlaporan'        => rand(400000, 499999),
        'detailticket'     => 'Gangguan koneksi pelanggan baru #' . $i,
        'resume'           => 'Pelanggan melaporkan gangguan melalui Call Center. Menunggu assign ke agent.',
        'topic'            => $topics[array_rand($topics)],
        'noSC'             => 'SC' . rand(1000000, 9999999),
        'statusSC'         => 'Open',
        'contact'          => 'Telepon',
        'reportedpriority' => $priority,
        'status'           => 'QUEUED',
        'condition'        => 'QUEUED',
        'assignby'         => null,   // Belum di-assign
        'solvedby'         => null,
        'eksalasiVia'      => 'Telegram',
    ]);
}

// =========================================================
// BAGIAN 2: 70 tiket yang sudah di-assign ke agent
// Tiket ini muncul di dashboard agent
// =========================================================
echo ">> Membuat 70 tiket yang sudah di-assign ke agent...\n";

$conditions = ['In Progress', 'Closed', 'ASSIGNED'];

for ($i = 1; $i <= 70; $i++) {
    $date = Carbon::now()->subDays(rand(0, 10))->subHours(rand(0, 24));
    $condition = $conditions[array_rand($conditions)];
    $isClosed = $condition === 'Closed';
    $agentAssigned = $agents[array_rand($agents)];
    $priority = $priorities[array_rand($priorities)];

    Ticket::create([
        'datereport'       => $date,
        'jenisTicket'      => 'INTERNET',
        'notelpCust'       => '0812' . rand(10000000, 99999999),
        'namacust'         => 'Pelanggan ' . $i,
        'idlaporan'        => rand(300000, 399999),
        'detailticket'     => 'Gangguan koneksi nomor ' . $i,
        'resume'           => 'Pelanggan melaporkan gangguan melalui Call Center. Segera lakukan pengecekan.',
        'topic'            => $topics[array_rand($topics)],
        'noSC'             => 'SC' . rand(1000000, 9999999),
        'statusSC'         => $isClosed ? 'Closed' : 'Open',
        'contact'          => 'Telepon',
        'reportedpriority' => $priority,
        'status'           => $isClosed ? 'Closed' : 'ASSIGNED',
        'condition'        => $condition,
        'assignby'         => $agentAssigned,
        'solvedby'         => $isClosed ? $agentAssigned : null,
        'hasil_pengecekan' => $isClosed ? 'Pengecekan port sisi ODP normal. Reset sisi OLT sukses. Koneksi pelanggan kembali Up.' : 'Masih dalam proses eskalasi regu teknisi.',
        'resolved_by_agent'=> $isClosed ? $agentAssigned : null,
        'eksalasiVia'      => 'Telegram',
    ]);
}

echo "\nBerhasil! Total 100 tiket telah di-inject:\n";
echo "  - 30 tiket QUEUED (belum assign, untuk Team Leader)\n";
echo "  - 70 tiket sudah di-assign ke agent\n";
