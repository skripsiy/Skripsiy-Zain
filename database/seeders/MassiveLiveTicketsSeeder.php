<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use App\Models\AgentWorkSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class MassiveLiveTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=MassiveLiveTicketsSeeder
     */
    public function run(): void
    {
        $this->command->info('🧹 Cleaning existing tickets, work sessions, and activity logs...');
        
        Schema::disableForeignKeyConstraints();
        Ticket::truncate();
        AgentWorkSession::truncate();
        DB::table('activity_log')->truncate();
        Schema::enableForeignKeyConstraints();

        // Clear all dashboard caches
        try {
            Cache::flush();
            $this->command->info('✨ Cache cleared successfully.');
        } catch (\Exception $e) {
            $this->command->warn('⚠️ Cache flush failed: ' . $e->getMessage());
        }

        // Fetch agents from database
        $areaAgents = User::where('role', 'agent')->where('campaign', 'area')->get();
        $besfixedAgents = User::where('role', 'agent')->where('campaign', 'besfixed')->get();
        $saltikAgents = User::where('role', 'agent')->where('campaign', 'saltik')->get();
        $allAgents = User::where('role', 'agent')->get();

        if ($allAgents->isEmpty()) {
            $this->command->error('❌ No agents found. Please run DivisionUsersSeeder first!');
            return;
        }

        $this->command->info('🟢 Creating active, realistic work sessions for all 15 agents...');
        foreach ($allAgents as $agent) {
            // Randomize shift and online times to make Total AUX/Online dashboard cards look alive
            $onlineHours = rand(6, 8) + (rand(0, 59) / 60); // e.g. 7.5h
            $auxMinutes = rand(15, 45); // e.g. 30 minutes
            $totalOnlineSeconds = (int) ($onlineHours * 3600);
            $totalAuxSeconds = $auxMinutes * 60;

            AgentWorkSession::create([
                'user_id' => $agent->id,
                'work_date' => today(),
                'status' => 'online',
                'shift_start' => Carbon::now()->startOfDay()->addHours(8),
                'shift_end' => Carbon::now()->startOfDay()->addHours(17),
                'current_session_start' => Carbon::now()->subHours(rand(1, 4)),
                'total_online_seconds' => $totalOnlineSeconds,
                'total_aux_seconds' => $totalAuxSeconds,
                'aux_remaining_seconds' => 1800 - $totalAuxSeconds,
            ]);
        }

        $personalNames = [
            'Slamet Rahardjo', 'Anisa Bahar', 'Bambang Pamungkas', 'Joko Susilo', 'Siti Aminah', 
            'Dewi Lestari', 'Agus Harimurti', 'Taufik Hidayat', 'Chandra Wijaya', 'Lusi Indah', 
            'Yusuf Mansur', 'Rina Nose', 'Gading Marten', 'Raffi Ahmad', 'Nagita Slavina', 
            'Atta Halilintar', 'Baim Wong', 'Paula Verhoeven', 'Andre Taulany', 'Sule Sutisna',
            'Deddy Corbuzier', 'Najwa Shihab', 'Reza Rahadian', 'Chelsea Islan', 'Dian Sastrowardoyo'
        ];

        $corporateNames = [
            'PT Astra International', 'PT Bank Central Asia', 'Kementerian Kesehatan', 'PT Telekomunikasi Indonesia', 
            'Otoritas Jasa Keuangan', 'PT Pertamina', 'PT GoTo Gojek Tokopedia', 'Universitas Indonesia', 
            'PT Unilever Indonesia', 'Badan Pusat Statistik', 'Kementerian Keuangan RI', 'PT Bank Mandiri Tbk', 
            'PT Indofood Sukses Makmur', 'PT Telkomsel', 'OVO (PT Bumi Digital)'
        ];

        $topics = [
            'LOS Merah', 'Koneksi Lambat', 'Router Blank', 'Billing Tagihan', 'Putus-putus', 
            'IPTV Error', 'WiFi Not Connecting', 'FO Cut', 'High Latency', 'Modem Rusak', 
            'Red Light Blinking', 'Request Open Port', 'Reset Password PPPoE', 'Registrasi Gagal'
        ];

        $regions = ['DKI JAKARTA', 'JABAR', 'JATENG', 'JATIM', 'SUMATERA', 'KALIMANTAN', 'SULAWESI'];
        $witels = [
            'DKI JAKARTA' => ['JAKARTA PUSAT', 'JAKARTA SELATAN', 'JAKARTA BARAT', 'JAKARTA TIMUR', 'JAKARTA UTARA'],
            'JABAR' => ['BANDUNG', 'BEKASI', 'BOGOR', 'CIREBON', 'SUKABUMI'],
            'JATENG' => ['SEMARANG', 'SOLO', 'YOGYAKARTA', 'PURWOKERTO'],
            'JATIM' => ['SURABAYA', 'MALANG', 'SIDOARJO', 'JEMBER', 'KEDIRI'],
            'SUMATERA' => ['MEDAN', 'PALEMBANG', 'PADANG', 'PEKANBARU', 'LAMPUNG'],
            'KALIMANTAN' => ['BALIKPAPAN', 'SAMARINDA', 'PONTIANAK', 'BANJARMASIN'],
            'SULAWESI' => ['MAKASSAR', 'MANADO', 'PALU', 'KENDARI']
        ];

        $conditions = ['In Progress', 'Closed', 'Dispatched', 'Saltik', 'Queued', 'New'];

        $this->command->info('📥 Generating 220 realistic tickets across different dates & categories...');

        $totalTickets = 220;
        $injectedCount = 0;

        for ($i = 1; $i <= $totalTickets; $i++) {
            // 1. Distribute dates
            if ($i <= 120) {
                // Today's tickets (evenly spread throughout today's hours to populate the hourly traffic chart)
                $hour = ($i % 24); 
                $createdAt = Carbon::today()->addHours($hour)->addMinutes(rand(0, 59));
            } elseif ($i <= 170) {
                // This week (last 7 days)
                $createdAt = Carbon::now()->subDays(rand(1, 7))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            } elseif ($i <= 200) {
                // This month (last 30 days)
                $createdAt = Carbon::now()->subDays(rand(8, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            } else {
                // This quarter (last 90 days)
                $createdAt = Carbon::now()->subDays(rand(31, 90))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            }

            // 2. Determine Division
            // 35% Area, 35% Besfixed, 30% Saltik
            $randDiv = rand(1, 100);
            if ($randDiv <= 35) {
                $division = 'area';
                $agentsPool = $areaAgents;
            } elseif ($randDiv <= 70) {
                $division = 'besfixed';
                $agentsPool = $besfixedAgents;
            } else {
                $division = 'saltik';
                $agentsPool = $saltikAgents;
            }

            // 3. Determine Urgency and priority
            $priorityRand = rand(1, 100);
            if ($priorityRand <= 40) {
                $priority = 'Low';
                $urgency = 1;
            } elseif ($priorityRand <= 70) {
                $priority = 'Emergency';
                $urgency = 2;
            } elseif ($priorityRand <= 85) {
                $priority = 'Super Emergency';
                $urgency = 3;
            } elseif ($priorityRand <= 95) {
                $priority = 'HVC';
                $urgency = 4;
            } else {
                $priority = 'VVIP';
                $urgency = 5;
            }

            // 4. Condition / Status
            // Let's align conditions with divisions
            if ($division === 'saltik') {
                $conditionRand = rand(1, 100);
                if ($conditionRand <= 60) {
                    $condition = 'Saltik'; // unique to saltik
                } elseif ($conditionRand <= 80) {
                    $condition = 'Closed';
                } else {
                    $condition = 'In Progress';
                }
            } else {
                $conditionRand = rand(1, 100);
                if ($conditionRand <= 45) {
                    $condition = 'In Progress';
                } elseif ($conditionRand <= 75) {
                    $condition = 'Closed';
                } elseif ($conditionRand <= 90) {
                    $condition = 'Dispatched';
                } else {
                    $condition = 'Queued';
                }
            }

            // 5. Assigned & Solved By
            $isClosed = ($condition === 'Closed' || $condition === 'Saltik');
            $isAssigned = ($condition !== 'Queued' && $condition !== 'New');

            $agent = ($isAssigned && $agentsPool && $agentsPool->isNotEmpty()) ? $agentsPool->random() : null;
            $agentId = $agent ? $agent->id : null;
            $agentName = $agent ? $agent->name : null;

            // 6. Regional & Witel
            $region = $regions[array_rand($regions)];
            $witelOptions = $witels[$region];
            $witel = $witelOptions[array_rand($witelOptions)];

            // 7. Customer & Details
            $isCorporate = (rand(1, 100) <= 20);
            $customerName = $isCorporate ? $corporateNames[array_rand($corporateNames)] : $personalNames[array_rand($personalNames)];
            if ($isCorporate) {
                $customerName .= ' (' . ['Pusat', 'Cabang', 'Divisi TI', 'Kantor Operasional'][rand(0,3)] . ')';
            }

            $topic = ($division === 'saltik') ? 'Salam Simpatik' : $topics[array_rand($topics)];
            $detail = "Pelanggan melaporkan kendala " . strtolower($topic) . ". Sinyal drop, memerlukan kunjungan teknisi lapangan jika diperlukan.";

            $ticket = Ticket::create([
                'datereport' => $createdAt->toDateString(),
                'jenisTicket' => ($division === 'saltik') ? 'TELEPON' : 'INTERNET',
                'notelpCust' => '08' . rand(11, 23) . rand(1000000, 9999999),
                'namacust' => $customerName,
                'idlaporan' => rand(500000, 999999),
                'detailticket' => $detail,
                'resume' => "Massive Seed: Kendala " . $topic . " pada pelanggan " . $customerName,
                'topic' => $topic,
                'noSC' => 'SC' . rand(5000000, 9999999),
                'statusSC' => $isClosed ? 'Closed' : 'Open',
                'contact' => ['Aplikasi', 'Call Center', 'Telegram', 'Walk-in'][rand(0, 3)],
                'reportedpriority' => $priority,
                'datesolved' => $isClosed ? $createdAt->copy()->addMinutes(rand(10, 180))->toDateString() : null,
                'THT' => $createdAt->copy()->addHours(2), // SLA Deadline
                'status' => $condition,
                'regional' => $region,
                'witel' => $witel,
                'condition' => $condition,
                'assigned_to_user_id' => $agentId,
                'solved_by_user_id' => $isClosed ? $agentId : null,
                'resolved_by_agent' => $isClosed ? $agentName : null,
                'hasil_pengecekan' => $isClosed ? 'Pengecekan selesai, konfigurasi OLT dan ONT normal. Redaman optik aman.' : 'Sedang dianalisis oleh tim support.',
                'eksalasiVia' => 'Telegram',
                'division_target' => $division,
                'urgency_level' => $urgency,
            ]);

            // Override timestamps to represent correct date filters in dashboard
            DB::table('tickets')->where('idTicket', $ticket->idTicket)->update([
                'created_at' => $createdAt,
                'updated_at' => $isClosed ? $createdAt->copy()->addMinutes(rand(10, 180)) : $createdAt,
            ]);

            $injectedCount++;
        }

        $this->command->info("🚀 Massively injected {$injectedCount} tickets successfully!");
        $this->command->info("📊 Total tickets in DB: " . Ticket::count());
    }
}
