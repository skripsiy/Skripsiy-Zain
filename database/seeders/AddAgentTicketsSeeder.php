<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AddAgentTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=AddAgentTicketsSeeder
     */
    public function run(): void
    {
        $this->command->info('🔍 Finding agent@xena.com user...');
        
        $agent = User::where('email', 'agent@xena.com')->first();
        
        if (!$agent) {
            $this->command->error('❌ User agent@xena.com not found!');
            return;
        }

        // Ensure the agent has active profile details if not set
        $agent->update([
            'username' => $agent->username ?? 'agent_xena',
            'campaign' => $agent->campaign ?? 'besfixed',
            'area' => $agent->area ?? 'BESFIXED',
            'site' => $agent->site ?? 'BANDUNG',
            'status' => 'active',
        ]);

        $this->command->info('🟢 Found agent: ' . $agent->name . ' (ID: ' . $agent->id . ')');
        $this->command->info('📥 Generating 50 tickets specifically assigned to this agent (preserving existing tickets)...');

        $personalNames = [
            'Rudi Hartono', 'Lilis Karlina', 'Agus Supriatna', 'Neneng Hasanah', 'Siti Rahma',
            'Eko Prasetyo', 'Yanto Basna', 'Inul Daratista', 'Iis Dahlia', 'Evi Masamba'
        ];

        $corporateNames = [
            'PT KAI (Persero)', 'PT PLN (Persero)', 'Kementerian Luar Negeri', 'PT Pertamina Geothermal',
            'PT Wijaya Karya Tbk', 'Universitas Gadjah Mada', 'PT Bukalapak.com'
        ];

        $topics = [
            'LOS Merah', 'Koneksi Lambat', 'Router Blank', 'Billing Tagihan', 'Putus-putus', 
            'IPTV Error', 'WiFi Not Connecting', 'FO Cut', 'High Latency', 'Modem Rusak'
        ];

        $regions = ['DKI JAKARTA', 'JABAR', 'JATENG', 'JATIM'];
        $witels = [
            'DKI JAKARTA' => ['JAKARTA PUSAT', 'JAKARTA SELATAN'],
            'JABAR' => ['BANDUNG', 'BEKASI'],
            'JATENG' => ['SEMARANG', 'SOLO'],
            'JATIM' => ['SURABAYA', 'MALANG']
        ];

        $conditions = ['In Progress', 'Closed', 'Dispatched'];

        $injectedCount = 0;
        $totalTickets = 50;

        for ($i = 1; $i <= $totalTickets; $i++) {
            // 1. Distribute dates
            if ($i <= 30) {
                // Today (evenly spread throughout today's hours)
                $hour = ($i % 24);
                $createdAt = Carbon::today()->addHours($hour)->addMinutes(rand(0, 59));
            } elseif ($i <= 40) {
                // This week (last 7 days)
                $createdAt = Carbon::now()->subDays(rand(1, 7))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            } elseif ($i <= 47) {
                // This month (last 30 days)
                $createdAt = Carbon::now()->subDays(rand(8, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            } else {
                // This quarter (last 90 days)
                $createdAt = Carbon::now()->subDays(rand(31, 90))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            }

            // 2. Set division based on agent's campaign
            $division = $agent->campaign;

            // 3. Condition / Status
            $conditionRand = rand(1, 100);
            if ($conditionRand <= 50) {
                $condition = 'In Progress';
            } elseif ($conditionRand <= 90) {
                $condition = 'Closed';
            } else {
                $condition = 'Dispatched';
            }

            $isClosed = ($condition === 'Closed');

            // 4. Regional & Witel
            $region = $regions[array_rand($regions)];
            $witelOptions = $witels[$region];
            $witel = $witelOptions[array_rand($witelOptions)];

            // 5. Customer & Details
            $isCorporate = (rand(1, 100) <= 25);
            $customerName = $isCorporate ? $corporateNames[array_rand($corporateNames)] : $personalNames[array_rand($personalNames)];
            
            $topic = $topics[array_rand($topics)];
            $detail = "Pelanggan melaporkan kendala " . strtolower($topic) . " pada segmen jaringan lokal.";

            $ticket = Ticket::create([
                'datereport' => $createdAt->toDateString(),
                'jenisTicket' => 'INTERNET',
                'notelpCust' => '08' . rand(55, 99) . rand(1000000, 9999999),
                'namacust' => $customerName,
                'idlaporan' => rand(400000, 499999),
                'detailticket' => $detail,
                'resume' => "Agent Spec: Kendala " . $topic . " - " . $customerName,
                'topic' => $topic,
                'noSC' => 'SC' . rand(4000000, 4999999),
                'statusSC' => $isClosed ? 'Closed' : 'Open',
                'contact' => 'Aplikasi',
                'reportedpriority' => ['Low', 'Emergency', 'Super Emergency'][rand(0, 2)],
                'datesolved' => $isClosed ? $createdAt->copy()->addMinutes(rand(15, 120))->toDateString() : null,
                'THT' => $createdAt->copy()->addHours(2),
                'status' => $condition,
                'regional' => $region,
                'witel' => $witel,
                'condition' => $condition,
                'assigned_to_user_id' => $agent->id,
                'solved_by_user_id' => $isClosed ? $agent->id : null,
                'resolved_by_agent' => $isClosed ? $agent->name : null,
                'hasil_pengecekan' => $isClosed ? 'Pengecekan port normal. Koneksi aktif kembali.' : 'Sedang diproses oleh agen terkait.',
                'eksalasiVia' => 'Telegram',
                'division_target' => $division,
                'urgency_level' => $isClosed ? 1 : 2,
            ]);

            // Override timestamps
            DB::table('tickets')->where('idTicket', $ticket->idTicket)->update([
                'created_at' => $createdAt,
                'updated_at' => $isClosed ? $createdAt->copy()->addMinutes(rand(15, 120)) : $createdAt,
            ]);

            $injectedCount++;
        }

        // Flush all dashboard caches
        try {
            Cache::flush();
            $this->command->info('✨ Dashboard cache flushed.');
        } catch (\Exception $e) {
            $this->command->warn('⚠️ Cache flush failed: ' . $e->getMessage());
        }

        $this->command->info("🚀 Successfully added {$injectedCount} tickets to {$agent->email}!");
        $this->command->info("📊 Total tickets in DB: " . Ticket::count());
    }
}
