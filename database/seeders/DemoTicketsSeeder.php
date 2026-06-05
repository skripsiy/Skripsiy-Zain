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

class DemoTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=DemoTicketsSeeder
     */
    public function run(): void
    {
        // 1. Bersihkan data lama
        Schema::disableForeignKeyConstraints();
        Ticket::truncate();
        AgentWorkSession::truncate();
        DB::table('activity_log')->truncate();
        Schema::enableForeignKeyConstraints();

        // Clear round-robin caches
        Cache::forget('rr_index_area');
        Cache::forget('rr_index_besfixed');
        Cache::forget('rr_index_saltik');

        $this->command->info('🧹 Caches and ticket tables cleared.');

        // 2. Ambil agent berdasarkan divisi dari database
        $areaAgents = User::where('role', 'agent')->whereRaw('LOWER(campaign) = ?', ['area'])->get();
        $besfixedAgents = User::where('role', 'agent')->whereRaw('LOWER(campaign) = ?', ['besfixed'])->get();
        $saltikAgents = User::where('role', 'agent')->whereRaw('LOWER(campaign) = ?', ['saltik'])->get();

        if ($areaAgents->isEmpty() || $besfixedAgents->isEmpty() || $saltikAgents->isEmpty()) {
            $this->command->error('❌ Pastikan DivisionUsersSeeder sudah dijalankan terlebih dahulu!');
            return;
        }

        // 3. Set work sessions online hari ini untuk semua agent (agar auto-assign round-robin bekerja)
        $agents = User::where('role', 'agent')->get();
        foreach ($agents as $agent) {
            AgentWorkSession::create([
                'user_id' => $agent->id,
                'work_date' => today(),
                'status' => 'online',
                'shift_start' => Carbon::now()->startOfDay()->addHours(8),
                'shift_end' => Carbon::now()->startOfDay()->addHours(17),
                'current_session_start' => Carbon::now()->subMinutes(rand(10, 120)),
                'total_online_seconds' => rand(1000, 10000),
                'total_aux_seconds' => rand(100, 1000),
                'aux_remaining_seconds' => 1800,
            ]);
        }
        $this->command->info('🟢 Created active online work sessions for all 15 agents.');

        // 4. Seed Area Tickets (18 Tickets)
        $this->seedAreaTickets($areaAgents);

        // 5. Seed Besfixed Tickets (18 Tickets)
        $this->seedBesfixedTickets($besfixedAgents);

        // 6. Seed Saltik Tickets (12 Tickets)
        $this->seedSaltikTickets($saltikAgents);

        // 7. Seed Fallback & Routing Test Tickets (5 Tickets via Observer)
        $this->seedFallbackRoutingTickets();

        $this->command->info('🎉 Seeded ' . Ticket::count() . ' tickets successfully!');
    }

    private function seedAreaTickets($agents)
    {
        $ticketsData = [
            // --- VVIP (Loker TL) ---
            [
                'namacust' => 'Joko Widodo (Kepresidenan)',
                'reportedpriority' => 'VVIP',
                'urgency_level' => 5,
                'division_target' => 'area',
                'status' => 'QUEUED',
                'condition' => 'QUEUED',
                'assignby' => null,
                'lapul' => 5,
                'gaul' => 3,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA PUSAT',
                'detailticket' => 'Layanan internet Istana Negara putus total (LOS Merah). Harap ditindaklanjuti segera.',
                'klasifikasi' => 'VVIP INTERNET',
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(2),
                'THT' => Carbon::now()->addHours(1),
            ],
            [
                'namacust' => 'Sri Mulyani (Kemenkeu)',
                'reportedpriority' => 'MANAGEMENT',
                'urgency_level' => 5,
                'division_target' => 'area',
                'status' => 'QUEUED',
                'condition' => 'QUEUED',
                'assignby' => null,
                'lapul' => 3,
                'gaul' => 1,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA PUSAT',
                'detailticket' => 'WiFi VIP gedung Djuanda Kemenkeu lambat. Akses rapat koordinasi terhambat.',
                'klasifikasi' => 'VVIP WIFI',
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(3),
                'THT' => Carbon::now()->addHours(2),
            ],

            // --- HVC (Loker TL) ---
            [
                'namacust' => 'PT Bank Mandiri Tbk',
                'reportedpriority' => 'HVC',
                'urgency_level' => 4,
                'division_target' => 'area',
                'status' => 'QUEUED',
                'condition' => 'QUEUED',
                'assignby' => null,
                'lapul' => 4,
                'gaul' => 2,
                'regional' => 'JATIM',
                'witel' => 'SURABAYA',
                'detailticket' => 'Link utama ATM Cabang Darmo mengalami degradasi kualitas (packet loss 15%).',
                'klasifikasi' => 'HVC LEASED LINE',
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(4),
                'THT' => Carbon::now()->addHours(3),
            ],

            // --- Super Emergency (Loker TL) ---
            [
                'namacust' => 'Badan Penanggulangan Bencana (BPBD)',
                'reportedpriority' => 'SUPER EMERGENCY',
                'urgency_level' => 3,
                'division_target' => 'area',
                'status' => 'QUEUED',
                'condition' => 'QUEUED',
                'assignby' => null,
                'lapul' => 6,
                'gaul' => 4,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'Telepon darurat posko BPBD mati total saat siaga bencana hidrometeorologi.',
                'klasifikasi' => 'TELEPON POSKO',
                'source_system' => 'DSC',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(1),
                'THT' => Carbon::now()->addMinutes(45),
            ],

            // --- Emergency (Assigned ke Agent Area) ---
            [
                'namacust' => 'Ridwan Kamil',
                'reportedpriority' => 'Emergency',
                'urgency_level' => 2,
                'division_target' => 'area',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agents[0]->name, // Budi Santoso
                'lapul' => 2,
                'gaul' => 0,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'Indikasi lampu merah berkedip pada modem ONT di kediaman dinas.',
                'klasifikasi' => 'GANGGUAN INTERNET',
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subMinutes(90),
                'THT' => Carbon::now()->addHours(2),
            ],
            [
                'namacust' => 'Anies Baswedan',
                'reportedpriority' => 'Emergency',
                'urgency_level' => 2,
                'division_target' => 'area',
                'status' => 'ASSIGNED',
                'condition' => 'In Progress',
                'assignby' => $agents[1]->name, // Dewi Rahayu
                'lapul' => 1,
                'gaul' => 0,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA SELATAN',
                'detailticket' => 'Akses internet sering putus nyambung saat jam kerja.',
                'klasifikasi' => 'GANGGUAN INTERNET',
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subMinutes(45),
                'THT' => Carbon::now()->addHours(3),
            ],

            // --- Low Emergency (Assigned ke Agent Area) ---
            [
                'namacust' => 'Bambang Pamungkas',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'area',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agents[2]->name, // Farhan Hidayat
                'lapul' => 0,
                'gaul' => 0,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA TIMUR',
                'detailticket' => 'Layanan UseeTV tidak bisa loading, stuck di logo Indihome.',
                'klasifikasi' => 'USEETV STUCK',
                'source_system' => 'DSC',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(5),
                'THT' => Carbon::now()->addHours(4),
            ],

            // --- THT Out SLA (THT sudah terlewat, harus di area teratas) ---
            [
                'namacust' => 'Susi Susanti',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'area',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agents[0]->name, // Budi Santoso
                'lapul' => 3,
                'gaul' => 1,
                'regional' => 'JATIM',
                'witel' => 'SURABAYA',
                'detailticket' => 'Koneksi lambat semenjak migrasi paket kecepatan baru.',
                'klasifikasi' => 'SLOW SPEED',
                'source_system' => 'DSC',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(6),
                'THT' => Carbon::now()->subMinutes(15), // Out SLA!
            ],

            // --- THT Menuju SLA (Sisa waktu tinggal 5 menit, harus di area teratas) ---
            [
                'namacust' => 'Alan Budikusuma',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'area',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agents[1]->name, // Dewi Rahayu
                'lapul' => 2,
                'gaul' => 1,
                'regional' => 'JATIM',
                'witel' => 'MALANG',
                'detailticket' => 'Sering loss merah di siang hari.',
                'klasifikasi' => 'INTERMITTENT RED',
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(2),
                'THT' => Carbon::now()->addMinutes(5), // Menuju SLA!
            ],

            // --- Closed / Resolved Area ---
            [
                'namacust' => 'Taufik Hidayat',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'area',
                'status' => 'Closed',
                'condition' => 'Closed',
                'assignby' => $agents[3]->name, // Gita Permata
                'solvedby' => $agents[3]->name,
                'datesolved' => Carbon::now(),
                'lapul' => 0,
                'gaul' => 0,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'WiFi mati tidak memancarkan SSID.',
                'klasifikasi' => 'WIFI DISAPPEARED',
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(8),
                'THT' => Carbon::now()->subHours(4),
                'hasil_pengecekan' => 'Selesai disetting ulang modul wlan. SSID kembali muncul dan stabil.',
            ],
            [
                'namacust' => 'Erick Thohir',
                'reportedpriority' => 'Emergency',
                'urgency_level' => 2,
                'division_target' => 'area',
                'status' => 'Closed',
                'condition' => 'Closed',
                'assignby' => $agents[4]->name, // Hendra Wijaya
                'solvedby' => $agents[4]->name,
                'datesolved' => Carbon::now(),
                'lapul' => 1,
                'gaul' => 0,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA PUSAT',
                'detailticket' => 'Telepon dinas tidak bisa menerima panggilan masuk.',
                'klasifikasi' => 'TELEPON GANGGUAN',
                'source_system' => 'DSC',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(4),
                'THT' => Carbon::now()->subHours(1),
                'hasil_pengecekan' => 'Kabel tembaga putus di tiang DP, sudah dilakukan penyambungan ulang.',
            ]
        ];

        // Tambah tiket tambahan untuk variasi
        for ($i = 1; $i <= 7; $i++) {
            $agent = $agents[$i % count($agents)];
            $ticketsData[] = [
                'namacust' => 'Pelanggan Area ' . $i,
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'area',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agent->name,
                'lapul' => rand(0, 2),
                'gaul' => rand(0, 1),
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'Pengujian konektivitas rutin area ke-' . $i,
                'klasifikasi' => 'ROUTINE CHECK',
                'source_system' => 'DSC',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subDays(rand(1, 5)),
                'THT' => Carbon::now()->addHours(24),
            ];
        }

        // Bypass observer agar field custom tidak tertimpa
        $dispatcher = Ticket::getEventDispatcher();
        Ticket::unsetEventDispatcher();
        foreach ($ticketsData as $data) {
            Ticket::create(array_merge([
                'jenisTicket' => 'INTERNET',
                'notelpCust' => '0812' . rand(10000000, 99999999),
                'idlaporan' => rand(400000, 499999),
                'noSC' => 'SC' . rand(2000000, 2999999),
                'statusSC' => isset($data['datesolved']) ? 'Closed' : 'Open',
                'contact' => 'Telepon',
                'eksalasiVia' => 'Telegram',
                'resume' => 'Demo Area: Laporan ' . $data['namacust'],
            ], $data));
        }
        Ticket::setEventDispatcher($dispatcher);
    }

    private function seedBesfixedTickets($agents)
    {
        $ticketsData = [
            // --- VVIP (Loker TL) ---
            [
                'namacust' => 'Prabowo Subianto',
                'reportedpriority' => 'VVIP',
                'urgency_level' => 5,
                'division_target' => 'besfixed',
                'status' => 'QUEUED',
                'condition' => 'QUEUED',
                'assignby' => null,
                'lapul' => 8,
                'gaul' => 4,
                'regional' => 'JABAR',
                'witel' => 'BOGOR',
                'detailticket' => 'Akses internet kediaman Hambalang lambat dan tidak stabil. Rapat zoom terganggu.',
                'klasifikasi' => 'VVIP FIBER',
                'source_system' => 'INSERA',
                'channel' => '19',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subHours(1),
                'THT' => Carbon::now()->addHours(2),
            ],

            // --- HVC (Loker TL) ---
            [
                'namacust' => 'Gibran Rakabuming',
                'reportedpriority' => 'HVC',
                'urgency_level' => 4,
                'division_target' => 'besfixed',
                'status' => 'QUEUED',
                'condition' => 'QUEUED',
                'assignby' => null,
                'lapul' => 3,
                'gaul' => 2,
                'regional' => 'JATENG',
                'witel' => 'SOLO',
                'detailticket' => 'Smart Office Balaikota Surakarta tidak terhubung ke jaringan pusat.',
                'klasifikasi' => 'HVC VPN',
                'source_system' => 'INSERA',
                'channel' => '2',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subHours(2),
                'THT' => Carbon::now()->addHours(4),
            ],

            // --- Super Emergency (Loker TL) ---
            [
                'namacust' => 'Rumah Sakit Hasan Sadikin',
                'reportedpriority' => 'SUPER EMERGENCY',
                'urgency_level' => 3,
                'division_target' => 'besfixed',
                'status' => 'QUEUED',
                'condition' => 'QUEUED',
                'assignby' => null,
                'lapul' => 6,
                'gaul' => 2,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'Sistem antrean BPJS online rumah sakit down. Penumpukan pasien terjadi.',
                'klasifikasi' => 'SE HOSPITAL SYSTEM',
                'source_system' => 'DSC',
                'channel' => '4',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subMinutes(30),
                'THT' => Carbon::now()->addHours(1),
            ],

            // --- Emergency (Assigned ke Agent Besfixed) ---
            [
                'namacust' => 'Sandhy Sondoro',
                'reportedpriority' => 'Emergency',
                'urgency_level' => 2,
                'division_target' => 'besfixed',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agents[0]->name, // Irfan Maulana
                'lapul' => 2,
                'gaul' => 0,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA SELATAN',
                'detailticket' => 'Jaringan Wifi drop secara berkala setiap 15 menit.',
                'klasifikasi' => 'GANGGUAN INTERNET',
                'source_system' => 'INSERA',
                'channel' => '40',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subHours(2),
                'THT' => Carbon::now()->addHours(4),
            ],
            [
                'namacust' => 'Afgan Syahreza',
                'reportedpriority' => 'Emergency',
                'urgency_level' => 2,
                'division_target' => 'besfixed',
                'status' => 'ASSIGNED',
                'condition' => 'In Progress',
                'assignby' => $agents[1]->name, // Juliana Putri
                'lapul' => 1,
                'gaul' => 1,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA SELATAN',
                'detailticket' => 'Modem berkedip merah terus setelah mati listrik kemarin.',
                'klasifikasi' => 'GANGGUAN INTERNET',
                'source_system' => 'INSERA',
                'channel' => '54',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subHours(1),
                'THT' => Carbon::now()->addHours(5),
            ],

            // --- Low Emergency (Assigned ke Agent Besfixed) ---
            [
                'namacust' => 'Isyana Sarasvati',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'besfixed',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agents[2]->name, // Kevin Firmansyah
                'lapul' => 0,
                'gaul' => 0,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'Tidak bisa melakukan login game online, indikasi IP public diblokir.',
                'klasifikasi' => 'INTERNET GANGGUAN IP',
                'source_system' => 'DSC',
                'channel' => '2',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subHours(3),
                'THT' => Carbon::now()->addHours(6),
            ],

            // --- Closed / Resolved Besfixed ---
            [
                'namacust' => 'Raisa Andriana',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'besfixed',
                'status' => 'Closed',
                'condition' => 'Closed',
                'assignby' => $agents[3]->name, // Linda Sari
                'solvedby' => $agents[3]->name,
                'datesolved' => Carbon::now(),
                'lapul' => 0,
                'gaul' => 0,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA SELATAN',
                'detailticket' => 'Kecepatan internet drop, hanya dapat 5 Mbps dari paket 50 Mbps.',
                'klasifikasi' => 'SLOW SPEED',
                'source_system' => 'DSC',
                'channel' => '19',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subHours(5),
                'THT' => Carbon::now()->subHours(1),
                'hasil_pengecekan' => 'Dilakukan penggantian kabel dropwire yang terkelupas digigit hama.',
            ],
            [
                'namacust' => 'Nadiem Makarim',
                'reportedpriority' => 'Emergency',
                'urgency_level' => 2,
                'division_target' => 'besfixed',
                'status' => 'Closed',
                'condition' => 'Closed',
                'assignby' => $agents[4]->name, // Muhamad Rizki
                'solvedby' => $agents[4]->name,
                'datesolved' => Carbon::now(),
                'lapul' => 1,
                'gaul' => 0,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA SELATAN',
                'detailticket' => 'Link Zoom Meeting Kemendikbud ngelag parah.',
                'klasifikasi' => 'GANGGUAN INTERNET',
                'source_system' => 'INSERA',
                'channel' => '2',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subHours(4),
                'THT' => Carbon::now()->subHours(2),
                'hasil_pengecekan' => 'Konfigurasi QoS diprioritaskan untuk IP kantor, rapat berjalan lancar.',
            ]
        ];

        // Tambah tiket tambahan untuk variasi
        for ($i = 1; $i <= 10; $i++) {
            $agent = $agents[$i % count($agents)];
            $ticketsData[] = [
                'namacust' => 'Pelanggan Besfixed ' . $i,
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'besfixed',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agent->name,
                'lapul' => rand(0, 2),
                'gaul' => rand(0, 1),
                'regional' => 'JATIM',
                'witel' => 'SURABAYA',
                'detailticket' => 'Pemeliharaan berkala ONT pelanggan ke-' . $i,
                'klasifikasi' => 'MAINTENANCE CHECK',
                'source_system' => 'DSC',
                'channel' => '19',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subDays(rand(1, 5)),
                'THT' => Carbon::now()->addHours(48),
            ];
        }

        $dispatcher = Ticket::getEventDispatcher();
        Ticket::unsetEventDispatcher();
        foreach ($ticketsData as $data) {
            Ticket::create(array_merge([
                'jenisTicket' => 'INTERNET',
                'notelpCust' => '0812' . rand(10000000, 99999999),
                'idlaporan' => rand(500000, 599999),
                'noSC' => 'SC' . rand(3000000, 3999999),
                'statusSC' => isset($data['datesolved']) ? 'Closed' : 'Open',
                'contact' => 'Telepon',
                'eksalasiVia' => 'Telegram',
                'resume' => 'Demo Besfixed: Laporan ' . $data['namacust'],
            ], $data));
        }
        Ticket::setEventDispatcher($dispatcher);
    }

    private function seedSaltikTickets($agents)
    {
        $ticketsData = [
            // --- VVIP (Loker TL) ---
            [
                'namacust' => 'Megawati Soekarnoputri',
                'reportedpriority' => 'VVIP',
                'urgency_level' => 5,
                'division_target' => 'saltik',
                'status' => 'QUEUED',
                'condition' => 'QUEUED',
                'assignby' => null,
                'lapul' => 5,
                'gaul' => 2,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA PUSAT',
                'detailticket' => 'Telepon rumah kediaman Teuku Umar berdengung keras dan tidak bisa menerima panggilan.',
                'klasifikasi' => 'VVIP WSA TELEPON',
                'source_system' => 'DSC',
                'channel' => '2',
                'pool_id' => 'SALAM SIMPATIK',
                'datereport' => Carbon::now()->subHours(2),
                'THT' => Carbon::now()->addHours(1),
            ],

            // --- HVC (Loker TL) ---
            [
                'namacust' => 'Susilo Bambang Yudhoyono',
                'reportedpriority' => 'HVC',
                'urgency_level' => 4,
                'division_target' => 'saltik',
                'status' => 'QUEUED',
                'condition' => 'QUEUED',
                'assignby' => null,
                'lapul' => 4,
                'gaul' => 1,
                'regional' => 'JABAR',
                'witel' => 'BOGOR',
                'detailticket' => 'Layanan internet Cikeas mengalami mati total berkali-kali.',
                'klasifikasi' => 'HVC WSA INTERNET',
                'source_system' => 'DSC',
                'channel' => '19',
                'pool_id' => 'SALAM SIMPATIK',
                'datereport' => Carbon::now()->subHours(3),
                'THT' => Carbon::now()->addHours(2),
            ],

            // --- Emergency (Assigned ke Agent Saltik) ---
            [
                'namacust' => 'Yura Yunita',
                'reportedpriority' => 'Emergency',
                'urgency_level' => 2,
                'division_target' => 'saltik',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agents[0]->name, // Nadia Kurniawati
                'lapul' => 1,
                'gaul' => 0,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'Telepon kantor tidak berbunyi padahal indikator normal.',
                'klasifikasi' => 'WSA TELEPON GANGGUAN',
                'source_system' => 'INSERA',
                'channel' => '4',
                'pool_id' => 'SALAM SIMPATIK',
                'datereport' => Carbon::now()->subHours(1),
                'THT' => Carbon::now()->addHours(3),
            ],
            [
                'namacust' => 'Tulus',
                'reportedpriority' => 'Emergency',
                'urgency_level' => 2,
                'division_target' => 'saltik',
                'status' => 'ASSIGNED',
                'condition' => 'In Progress',
                'assignby' => $agents[1]->name, // Oscar Pratama
                'lapul' => 3,
                'gaul' => 1,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'Sambungan internet studio mati saat upload album baru.',
                'klasifikasi' => 'WSA INTERNET GANGGUAN',
                'source_system' => 'INSERA',
                'channel' => '40',
                'pool_id' => 'SALAM SIMPATIK',
                'datereport' => Carbon::now()->subHours(2),
                'THT' => Carbon::now()->addHours(4),
            ],

            // --- Low Emergency (Assigned ke Agent Saltik) ---
            [
                'namacust' => 'Ari Lasso',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'saltik',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agents[2]->name, // Putri Ayu
                'lapul' => 0,
                'gaul' => 0,
                'regional' => 'JATIM',
                'witel' => 'SURABAYA',
                'detailticket' => 'Telepon intermiten kresek-kresek jika cuaca mendung.',
                'klasifikasi' => 'WSA LINE NOISY',
                'source_system' => 'DSC',
                'channel' => '54',
                'pool_id' => 'SALAM SIMPATIK',
                'datereport' => Carbon::now()->subHours(4),
                'THT' => Carbon::now()->addHours(5),
            ],

            // --- Closed / Resolved Saltik ---
            [
                'namacust' => 'Once Mekel',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'saltik',
                'status' => 'Closed',
                'condition' => 'Closed',
                'assignby' => $agents[3]->name, // Rendi Saputra
                'solvedby' => $agents[3]->name,
                'datesolved' => Carbon::now(),
                'lapul' => 0,
                'gaul' => 0,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA SELATAN',
                'detailticket' => 'Internet WSA lambat di jam malam.',
                'klasifikasi' => 'SLOW WSA',
                'source_system' => 'DSC',
                'channel' => '2',
                'pool_id' => 'SALAM SIMPATIK',
                'datereport' => Carbon::now()->subHours(6),
                'THT' => Carbon::now()->subHours(2),
                'hasil_pengecekan' => 'Dilakukan refresh IP and port pada sistem backend WSA. Speed normal kembali.',
            ]
        ];

        // Tambah tiket tambahan untuk variasi
        for ($i = 1; $i <= 6; $i++) {
            $agent = $agents[$i % count($agents)];
            $ticketsData[] = [
                'namacust' => 'Pelanggan Saltik ' . $i,
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'saltik',
                'status' => 'ASSIGNED',
                'condition' => 'ASSIGNED',
                'assignby' => $agent->name,
                'lapul' => rand(0, 2),
                'gaul' => rand(0, 1),
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'Cek kualitas line WSA ke-' . $i,
                'klasifikasi' => 'WSA LINE TESTING',
                'source_system' => 'DSC',
                'channel' => '2',
                'pool_id' => 'SALAM SIMPATIK',
                'datereport' => Carbon::now()->subDays(rand(1, 5)),
                'THT' => Carbon::now()->addHours(72),
            ];
        }

        $dispatcher = Ticket::getEventDispatcher();
        Ticket::unsetEventDispatcher();
        foreach ($ticketsData as $data) {
            Ticket::create(array_merge([
                'jenisTicket' => 'TELEPON',
                'notelpCust' => '0812' . rand(10000000, 99999999),
                'idlaporan' => rand(600000, 699999),
                'noSC' => 'SC' . rand(4000000, 4999999),
                'statusSC' => isset($data['datesolved']) ? 'Closed' : 'Open',
                'contact' => 'Telepon',
                'eksalasiVia' => 'Telegram',
                'resume' => 'Demo Saltik: Laporan ' . $data['namacust'],
            ], $data));
        }
        Ticket::setEventDispatcher($dispatcher);
    }

    private function seedFallbackRoutingTickets()
    {
        $fallbackTickets = [
            // 1. Fallback Low Emergency (Tanpa criteria -> masuk Besfixed, Auto-assigned ke salah satu agent Besfixed)
            [
                'namacust' => 'Budi Sudarsono (Fallback Low)',
                'reportedpriority' => 'LOW',
                'detailticket' => 'Tiket tanpa kriteria channel/pool. Harus masuk divisi Besfixed secara otomatis dan ter-assign.',
            ],
            // 2. Fallback VVIP (Tanpa kriteria, urgency VVIP -> masuk Besfixed, QUEUED di loker TL)
            [
                'namacust' => 'Megawati Fallback (Fallback VVIP)',
                'reportedpriority' => 'VVIP',
                'detailticket' => 'Tiket VVIP tanpa kriteria channel/pool. Harus otomatis ter-routing ke Besfixed dan masuk Loker TL (QUEUED).',
            ],
            // 3. Fallback HVC (Tanpa kriteria, urgency HVC -> masuk Besfixed, QUEUED di loker TL)
            [
                'namacust' => 'Ganjar Pranowo (Fallback HVC)',
                'reportedpriority' => 'HVC',
                'detailticket' => 'Tiket HVC tanpa kriteria channel/pool. Harus ter-routing ke Besfixed dan masuk Loker TL (QUEUED).',
            ],
            // 4. Fallback Super Emergency (Tanpa kriteria, urgency SE -> masuk Besfixed, QUEUED di loker TL)
            [
                'namacust' => 'Susi Pudjiastuti (Fallback SE)',
                'reportedpriority' => 'SUPER EMERGENCY',
                'detailticket' => 'Tiket Super Emergency tanpa kriteria. Harus ter-routing ke Besfixed dan masuk Loker TL (QUEUED).',
            ],
            // 5. Fallback Emergency (Tanpa kriteria -> masuk Besfixed, Auto-assigned ke salah satu agent Besfixed)
            [
                'namacust' => 'Anies Baswedan (Fallback Emergency)',
                'reportedpriority' => 'EMERGENCY',
                'detailticket' => 'Tiket Emergency tanpa kriteria. Harus ter-routing ke Besfixed dan auto-assigned.',
            ],
        ];

        // Jalankan MELALUI OBSERVER (tanpa bypass) agar routing logic bekerja langsung
        foreach ($fallbackTickets as $data) {
            Ticket::create(array_merge([
                'datereport' => Carbon::now(),
                'jenisTicket' => 'GANGGUAN UMUM',
                'notelpCust' => '0813' . rand(10000000, 99999999),
                'idlaporan' => rand(700000, 799999),
                'noSC' => 'SC' . rand(5000000, 5999999),
                'statusSC' => 'Open',
                'contact' => 'Aplikasi',
                'eksalasiVia' => 'Telegram',
                'resume' => 'Demo Fallback Routing: ' . $data['namacust'],
                
                // Sengaja dikosongkan agar memicu fallback
                'channel' => null,
                'pool_id' => null,
                'source_system' => null,
            ], $data));
        }
    }
}
