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
        $besfixedAgents = User::where('role', 'agent')->whereRaw('LOWER(campaign) = ?', ['besfixed'])->get();

        if ($besfixedAgents->isEmpty()) {
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
        $this->seedAreaTickets($besfixedAgents);

        // 5. Seed Besfixed Tickets (18 Tickets)
        $this->seedBesfixedTickets($besfixedAgents);

        // 6. Seed Saltik Tickets (12 Tickets)
        $this->seedSaltikTickets($besfixedAgents);

        // 7. Seed Fallback & Routing Test Tickets (5 Tickets via Observer)
        $this->seedFallbackRoutingTickets();

        $this->command->info('🎉 Seeded ' . Ticket::count() . ' tickets successfully!');
    }

    private function seedAreaTickets($agents)
    {
        // Tiket Aktif (Akan dirouting otomatis oleh Observer ke divisi Area & auto-assigned)
        $activeTickets = [
            // --- VVIP (Akan masuk Loker TL Area) ---
            [
                'namacust' => 'Joko Widodo (Kepresidenan)',
                'reportedpriority' => 'VVIP',
                'lapul' => 5,
                'gaul' => 3,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA PUSAT',
                'detailticket' => 'Layanan internet Istana Negara putus total (LOS Merah). Harap ditindaklanjuti segera.',
                'klasifikasi' => 'VVIP INTERNET',
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(8),
                'THT' => Carbon::now()->addHours(1),
            ],
            [
                'namacust' => 'Sri Mulyani (Kemenkeu)',
                'reportedpriority' => 'MANAGEMENT',
                'lapul' => 3,
                'gaul' => 1,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA PUSAT',
                'detailticket' => 'WiFi VIP gedung Djuanda Kemenkeu lambat. Akses rapat koordinasi terhambat.',
                'klasifikasi' => 'VVIP WIFI',
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(9),
                'THT' => Carbon::now()->addHours(2),
            ],

            // --- HVC (Akan masuk Loker TL Area) ---
            [
                'namacust' => 'PT Bank Mandiri Tbk',
                'reportedpriority' => 'HVC',
                'lapul' => 4,
                'gaul' => 2,
                'regional' => 'JATIM',
                'witel' => 'SURABAYA',
                'detailticket' => 'Link utama ATM Cabang Darmo mengalami degradasi kualitas (packet loss 15%).',
                'klasifikasi' => 'HVC LEASED LINE',
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(10),
                'THT' => Carbon::now()->addHours(3),
            ],

            // --- Super Emergency (Akan masuk Loker TL Area) ---
            [
                'namacust' => 'Badan Penanggulangan Bencana (BPBD)',
                'reportedpriority' => 'SUPER EMERGENCY',
                'lapul' => 6,
                'gaul' => 4,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'Telepon darurat posko BPBD mati total saat siaga bencana hidrometeorologi.',
                'klasifikasi' => 'TELEPON POSKO',
                'source_system' => 'DSC',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'datereport' => Carbon::now()->subHours(7),
                'THT' => Carbon::now()->addMinutes(45),
            ],

            // --- Emergency (Otomatis Auto-assigned Round-Robin ke Agent Area) ---
            [
                'namacust' => 'Ridwan Kamil (Auto Area Emergency)',
                'reportedpriority' => 'Emergency',
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
                'namacust' => 'Anies Baswedan (Auto Area Emergency)',
                'reportedpriority' => 'Emergency',
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

            // --- Low Emergency (Otomatis Auto-assigned Round-Robin ke Agent Area) ---
            [
                'namacust' => 'Bambang Pamungkas (Auto Area Low)',
                'reportedpriority' => 'Low',
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
            [
                'namacust' => 'Susi Susanti (Auto Area Low - Out SLA)',
                'reportedpriority' => 'Low',
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
            [
                'namacust' => 'Alan Budikusuma (Auto Area Low - Menuju SLA)',
                'reportedpriority' => 'Low',
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
        ];

        // Tambah tiket tambahan untuk variasi
        for ($i = 1; $i <= 7; $i++) {
            $activeTickets[] = [
                'namacust' => 'Pelanggan Area ' . $i . ' (Auto Area Low)',
                'reportedpriority' => 'Low',
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

        // Tiket Closed (Historical, By pass observer karena sudah selesai di masa lalu)
        $closedTickets = [
            [
                'namacust' => 'Taufik Hidayat',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'besfixed',
                'status' => 'Closed',
                'condition' => 'Closed',
                'assigned_to_user_id' => $agents[3]->id, // Gita Permata
                'solved_by_user_id' => $agents[3]->id,
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
                'division_target' => 'besfixed',
                'status' => 'Closed',
                'condition' => 'Closed',
                'assigned_to_user_id' => $agents[4]->id, // Hendra Wijaya
                'solved_by_user_id' => $agents[4]->id,
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

        // 1. Jalankan Tiket Aktif melalui OBSERVER agar otomatis ter-assign round-robin
        foreach ($activeTickets as $data) {
            $ticket = new Ticket(array_merge([
                'jenisTicket' => 'INTERNET',
                'notelpCust' => '0812' . rand(10000000, 99999999),
                'idlaporan' => rand(400000, 499999),
                'noSC' => 'SC' . rand(2000000, 2999999),
                'statusSC' => 'Open',
                'contact' => 'Telepon',
                'eksalasiVia' => 'Telegram',
                'resume' => 'Demo Area: Laporan ' . $data['namacust'],
            ], $data));
            if (isset($data['datereport'])) {
                $ticket->created_at = Carbon::parse($data['datereport']);
            }
            $ticket->save();
        }

        // 2. Jalankan Tiket Closed
        foreach ($closedTickets as $data) {
            Ticket::create(array_merge([
                'jenisTicket' => 'INTERNET',
                'notelpCust' => '0812' . rand(10000000, 99999999),
                'idlaporan' => rand(400000, 499999),
                'noSC' => 'SC' . rand(2000000, 2999999),
                'statusSC' => 'Closed',
                'contact' => 'Telepon',
                'eksalasiVia' => 'Telegram',
                'resume' => 'Demo Area: Laporan ' . $data['namacust'],
            ], $data));
        }
    }

    private function seedBesfixedTickets($agents)
    {
        // Tiket Aktif (Akan dirouting otomatis oleh Observer ke divisi Besfixed & auto-assigned)
        $activeTickets = [
            // --- VVIP (Akan masuk Loker TL Besfixed) ---
            [
                'namacust' => 'Prabowo Subianto',
                'reportedpriority' => 'VVIP',
                'lapul' => 8,
                'gaul' => 4,
                'regional' => 'JABAR',
                'witel' => 'BOGOR',
                'detailticket' => 'Akses internet kediaman Hambalang lambat dan tidak stabil. Rapat zoom terganggu.',
                'klasifikasi' => 'VVIP FIBER',
                'source_system' => 'INSERA',
                'channel' => '19',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subHours(8),
                'THT' => Carbon::now()->addHours(2),
            ],

            // --- HVC (Akan masuk Loker TL Besfixed) ---
            [
                'namacust' => 'Gibran Rakabuming',
                'reportedpriority' => 'HVC',
                'lapul' => 3,
                'gaul' => 2,
                'regional' => 'JATENG',
                'witel' => 'SOLO',
                'detailticket' => 'Smart Office Balaikota Surakarta tidak terhubung ke jaringan pusat.',
                'klasifikasi' => 'HVC VPN',
                'source_system' => 'INSERA',
                'channel' => '2',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subHours(9),
                'THT' => Carbon::now()->addHours(4),
            ],

            // --- Super Emergency (Akan masuk Loker TL Besfixed) ---
            [
                'namacust' => 'Rumah Sakit Hasan Sadikin',
                'reportedpriority' => 'SUPER EMERGENCY',
                'lapul' => 6,
                'gaul' => 2,
                'regional' => 'JABAR',
                'witel' => 'BANDUNG',
                'detailticket' => 'Sistem antrean BPJS online rumah sakit down. Penumpukan pasien terjadi.',
                'klasifikasi' => 'SE HOSPITAL SYSTEM',
                'source_system' => 'DSC',
                'channel' => '4',
                'pool_id' => 'new_site179 BESFIXED',
                'datereport' => Carbon::now()->subHours(7),
                'THT' => Carbon::now()->addHours(1),
            ],

            // --- Emergency (Otomatis Auto-assigned Round-Robin ke Agent Besfixed) ---
            [
                'namacust' => 'Sandhy Sondoro (Auto Besfixed Emergency)',
                'reportedpriority' => 'Emergency',
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
                'namacust' => 'Afgan Syahreza (Auto Besfixed Emergency)',
                'reportedpriority' => 'Emergency',
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

            // --- Low Emergency (Otomatis Auto-assigned Round-Robin ke Agent Besfixed) ---
            [
                'namacust' => 'Isyana Sarasvati (Auto Besfixed Low)',
                'reportedpriority' => 'Low',
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
        ];

        // Tambah tiket tambahan untuk variasi
        for ($i = 1; $i <= 10; $i++) {
            $activeTickets[] = [
                'namacust' => 'Pelanggan Besfixed ' . $i . ' (Auto Besfixed Low)',
                'reportedpriority' => 'Low',
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

        // Tiket Closed (Historical, Bypass observer)
        $closedTickets = [
            [
                'namacust' => 'Raisa Andriana',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'besfixed',
                'status' => 'Closed',
                'condition' => 'Closed',
                'assigned_to_user_id' => $agents[3]->id, // Linda Sari
                'solved_by_user_id' => $agents[3]->id,
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
                'assigned_to_user_id' => $agents[4]->id, // Muhamad Rizki
                'solved_by_user_id' => $agents[4]->id,
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

        // 1. Jalankan Tiket Aktif melalui OBSERVER agar otomatis ter-assign round-robin
        foreach ($activeTickets as $data) {
            $ticket = new Ticket(array_merge([
                'jenisTicket' => 'INTERNET',
                'notelpCust' => '0812' . rand(10000000, 99999999),
                'idlaporan' => rand(500000, 599999),
                'noSC' => 'SC' . rand(3000000, 3999999),
                'statusSC' => 'Open',
                'contact' => 'Telepon',
                'eksalasiVia' => 'Telegram',
                'resume' => 'Demo Besfixed: Laporan ' . $data['namacust'],
            ], $data));
            if (isset($data['datereport'])) {
                $ticket->created_at = Carbon::parse($data['datereport']);
            }
            $ticket->save();
        }

        // 2. Jalankan Tiket Closed
        foreach ($closedTickets as $data) {
            Ticket::create(array_merge([
                'jenisTicket' => 'INTERNET',
                'notelpCust' => '0812' . rand(10000000, 99999999),
                'idlaporan' => rand(500000, 599999),
                'noSC' => 'SC' . rand(3000000, 3999999),
                'statusSC' => 'Closed',
                'contact' => 'Telepon',
                'eksalasiVia' => 'Telegram',
                'resume' => 'Demo Besfixed: Laporan ' . $data['namacust'],
            ], $data));
        }
    }

    private function seedSaltikTickets($agents)
    {
        // Tiket Aktif (Akan dirouting otomatis oleh Observer ke divisi Saltik & auto-assigned)
        $activeTickets = [
            // --- VVIP (Akan masuk Loker TL Saltik) ---
            [
                'namacust' => 'Megawati Soekarnoputri',
                'reportedpriority' => 'VVIP',
                'lapul' => 5,
                'gaul' => 2,
                'regional' => 'DKI JAKARTA',
                'witel' => 'JAKARTA PUSAT',
                'detailticket' => 'Telepon rumah kediaman Teuku Umar berdengung keras dan tidak bisa menerima panggilan.',
                'klasifikasi' => 'VVIP WSA TELEPON',
                'source_system' => 'DSC',
                'channel' => '2',
                'pool_id' => 'SALAM SIMPATIK',
                'datereport' => Carbon::now()->subHours(8),
                'THT' => Carbon::now()->addHours(1),
            ],

            // --- HVC (Akan masuk Loker TL Saltik) ---
            [
                'namacust' => 'Susilo Bambang Yudhoyono',
                'reportedpriority' => 'HVC',
                'lapul' => 4,
                'gaul' => 1,
                'regional' => 'JABAR',
                'witel' => 'BOGOR',
                'detailticket' => 'Layanan internet Cikeas mengalami mati total berkali-kali.',
                'klasifikasi' => 'HVC WSA INTERNET',
                'source_system' => 'DSC',
                'channel' => '19',
                'pool_id' => 'SALAM SIMPATIK',
                'datereport' => Carbon::now()->subHours(9),
                'THT' => Carbon::now()->addHours(2),
            ],

            // --- Emergency (Otomatis Auto-assigned Round-Robin ke Agent Saltik) ---
            [
                'namacust' => 'Yura Yunita (Auto Saltik Emergency)',
                'reportedpriority' => 'Emergency',
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
                'namacust' => 'Tulus (Auto Saltik Emergency)',
                'reportedpriority' => 'Emergency',
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

            // --- Low Emergency (Otomatis Auto-assigned Round-Robin ke Agent Saltik) ---
            [
                'namacust' => 'Ari Lasso (Auto Saltik Low)',
                'reportedpriority' => 'Low',
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
        ];

        // Tambah tiket tambahan untuk variasi
        for ($i = 1; $i <= 6; $i++) {
            $activeTickets[] = [
                'namacust' => 'Pelanggan Saltik ' . $i . ' (Auto Saltik Low)',
                'reportedpriority' => 'Low',
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

        // Tiket Closed (Historical, Bypass observer)
        $closedTickets = [
            [
                'namacust' => 'Once Mekel',
                'reportedpriority' => 'Low',
                'urgency_level' => 1,
                'division_target' => 'besfixed',
                'status' => 'Closed',
                'condition' => 'Closed',
                'assigned_to_user_id' => $agents[3]->id, // Rendi Saputra
                'solved_by_user_id' => $agents[3]->id,
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

        // 1. Jalankan Tiket Aktif melalui OBSERVER agar otomatis ter-assign round-robin
        foreach ($activeTickets as $data) {
            $ticket = new Ticket(array_merge([
                'jenisTicket' => 'TELEPON',
                'notelpCust' => '0812' . rand(10000000, 99999999),
                'idlaporan' => rand(600000, 699999),
                'noSC' => 'SC' . rand(4000000, 4999999),
                'statusSC' => 'Open',
                'contact' => 'Telepon',
                'eksalasiVia' => 'Telegram',
                'resume' => 'Demo Saltik: Laporan ' . $data['namacust'],
            ], $data));
            if (isset($data['datereport'])) {
                $ticket->created_at = Carbon::parse($data['datereport']);
            }
            $ticket->save();
        }

        // 2. Jalankan Tiket Closed
        foreach ($closedTickets as $data) {
            Ticket::create(array_merge([
                'jenisTicket' => 'TELEPON',
                'notelpCust' => '0812' . rand(10000000, 99999999),
                'idlaporan' => rand(600000, 699999),
                'noSC' => 'SC' . rand(4000000, 4999999),
                'statusSC' => 'Closed',
                'contact' => 'Telepon',
                'eksalasiVia' => 'Telegram',
                'resume' => 'Demo Saltik: Laporan ' . $data['namacust'],
            ], $data));
        }
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
            $createdAt = Carbon::now()->subHours(8);
            $ticket = new Ticket(array_merge([
                'datereport' => $createdAt,
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
            $ticket->created_at = $createdAt;
            $ticket->save();
        }
    }
}
