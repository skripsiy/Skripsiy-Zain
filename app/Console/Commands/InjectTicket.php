<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;

class InjectTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:inject';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inject satu tiket baru secara otomatis dengan data random (simulasi real-time ticket flow).';

    /**
     * Data pool untuk randomisasi tiket.
     */
    private array $topics = [
        'LOS Merah',
        'Koneksi Lambat',
        'Router Blank',
        'Biling Tagihan',
        'Putus-putus',
        'Gangguan Massal',
        'Modem Mati',
        'WiFi Tidak Terdeteksi',
        'Migrasi Paket',
        'Pemasangan Baru',
    ];

    private array $jenisTicket = ['INTERNET', 'Gamas', 'IPTV', 'VOICE'];

    private array $contacts = ['Telepon', 'WhatsApp', 'Email', 'Walk-in', 'Telegram'];

    private array $eksalasiVia = ['Telegram', 'WhatsApp', 'Email'];

    private array $channels = ['call_center', 'web_portal', 'mobile_app', 'walk_in'];

    private array $sourceSystems = ['myindihome', 'our_center', 'crm_legacy'];

    private array $divisionTargets = ['area', 'besfixed', 'saltik'];

    /**
     * Nama pelanggan random Indonesia.
     */
    private array $namaDepan = [
        'Budi', 'Siti', 'Andi', 'Dewi', 'Rudi', 'Rina', 'Agus', 'Putri',
        'Hendra', 'Wati', 'Joko', 'Lina', 'Fajar', 'Mega', 'Doni', 'Yuni',
        'Arif', 'Tina', 'Bayu', 'Sri', 'Rizky', 'Nurul', 'Dimas', 'Ayu',
    ];

    private array $namaBelakang = [
        'Santoso', 'Wijaya', 'Pratama', 'Sari', 'Hidayat', 'Kusuma', 'Hartono',
        'Rahayu', 'Putra', 'Lestari', 'Setiawan', 'Handoko', 'Wibowo', 'Permata',
        'Nugraha', 'Susanto', 'Suryadi', 'Purnama', 'Gunawan', 'Maulana',
    ];

    /**
     * Detail gangguan random.
     */
    private array $detailTemplates = [
        'Pelanggan melaporkan koneksi internet tidak bisa digunakan sejak pagi.',
        'Internet putus-putus, lampu LOS pada modem menyala merah.',
        'Pelanggan tidak bisa browsing, speed test menunjukkan 0 Mbps.',
        'WiFi terdeteksi tetapi tidak bisa terhubung ke internet.',
        'Modem mati total, sudah dicoba restart berkali-kali.',
        'Koneksi lambat, hanya mendapat 2 Mbps dari paket 100 Mbps.',
        'Pelanggan baru, instalasi belum selesai, teknisi belum datang.',
        'Tagihan tidak sesuai dengan paket yang digunakan.',
        'Pelanggan ingin migrasi ke paket yang lebih tinggi.',
        'Gangguan massal di area perumahan, beberapa pelanggan terdampak.',
        'Router blank screen, tidak ada lampu yang menyala.',
        'IPTV tidak bisa menampilkan channel, error code 1305.',
    ];

    /**
     * Resume/catatan random.
     */
    private array $resumeTemplates = [
        'Pelanggan melaporkan gangguan melalui Call Center. Menunggu assign ke agent.',
        'Laporan diterima via WhatsApp. Pelanggan mendesak untuk segera ditangani.',
        'Tiket otomatis dari sistem monitoring. Terdeteksi signal loss di ODC.',
        'Pelanggan walk-in ke Plasa Telkom. Sudah dicatat oleh CSR.',
        'Keluhan diterima via email. Pelanggan sudah komplain 2x sebelumnya.',
        'Laporan dari aplikasi MyIndiHome. Auto-dispatch ke queue.',
        'Eskalasi dari team dispatch. Perlu penanganan segera.',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil urgency level random (1-5) dengan distribusi weighted
        $urgencyLevel = $this->getWeightedUrgency();

        // Map urgency level ke reportedpriority yang sesuai TicketRoutingService::determineUrgencyLevel()
        // Ini yang menentukan apakah tiket auto-assign (level 1-2) atau masuk loker TL (level 3-5)
        $reportedPriority = match ($urgencyLevel) {
            5 => 'VVIP',
            4 => 'HVC',
            3 => 'Super Emergency',
            2 => 'Emergency',
            default => 'Low Emergency',
        };

        // Generate data random
        $namaCustomer = $this->namaDepan[array_rand($this->namaDepan)] . ' ' . $this->namaBelakang[array_rand($this->namaBelakang)];
        $topic = $this->topics[array_rand($this->topics)];
        $jenisTicket = $this->jenisTicket[array_rand($this->jenisTicket)];

        // Buat tiket TANPA division_target agar Observer trigger routeTicket()
        // Observer akan: tentukan divisi, urgency, lalu auto-assign (level 1-2) atau queue (level 3-5)
        $ticket = Ticket::create([
            'datereport'       => Carbon::now(),
            'jenisTicket'      => $jenisTicket,
            'notelpCust'       => '08' . rand(11, 99) . rand(10000000, 99999999),
            'namacust'         => $namaCustomer,
            'idlaporan'        => rand(500000, 999999),
            'detailticket'     => $this->detailTemplates[array_rand($this->detailTemplates)],
            'resume'           => $this->resumeTemplates[array_rand($this->resumeTemplates)],
            'topic'            => $topic,
            'noSC'             => 'SC' . rand(1000000, 9999999),
            'statusSC'         => 'Open',
            'contact'          => $this->contacts[array_rand($this->contacts)],
            'reportedpriority' => $reportedPriority,
            'eksalasiVia'      => $this->eksalasiVia[array_rand($this->eksalasiVia)],
            // Channel & source untuk metadata (tidak mempengaruhi routing)
            'channel'          => $this->channels[array_rand($this->channels)],
            'source_system'    => $this->sourceSystems[array_rand($this->sourceSystems)],
        ]);

        // Refresh untuk ambil data terbaru setelah Observer proses routing
        $ticket->refresh();
        $urgencyLabel = $ticket->urgency_label;
        $actualUrgency = $ticket->urgency_level;

        // Output hasil
        $this->info("✅ Tiket #{$ticket->idTicket} berhasil di-inject!");
        $this->line("   📋 Pelanggan : {$namaCustomer}");
        $this->line("   🔖 Topik     : {$topic}");
        $this->line("   🚨 Urgency   : Level {$actualUrgency} ({$urgencyLabel})");
        $this->line("   📌 Priority  : {$reportedPriority}");
        $this->line("   📞 Jenis     : {$jenisTicket}");
        $this->line("   📍 Divisi    : {$ticket->division_label}");

        // Tampilkan status routing
        if ($ticket->status === 'ASSIGNED') {
            $this->line("   👤 Assign ke : {$ticket->assignedTo?->name}");
            $this->info("   → Auto-assigned via round-robin ke agent");
        } else {
            $this->warn("   → Masuk loker Team Leader (urgency tinggi, perlu assign manual)");
        }

        $this->line("   🕐 Waktu     : " . Carbon::now()->format('H:i:s'));

        return Command::SUCCESS;
    }

    /**
     * Generate urgency level dengan distribusi weighted agar lebih realistis.
     * 
     * Distribusi:
     * - Level 1 (Low Emergency)    : 30%
     * - Level 2 (Emergency)        : 30%
     * - Level 3 (Super Emergency)  : 20%
     * - Level 4 (HVC)              : 12%
     * - Level 5 (VVIP/Management)  : 8%
     */
    private function getWeightedUrgency(): int
    {
        $rand = rand(1, 100);

        return match (true) {
            $rand <= 30  => 1,  // Low Emergency    — 30%
            $rand <= 60  => 2,  // Emergency        — 30%
            $rand <= 80  => 3,  // Super Emergency  — 20%
            $rand <= 92  => 4,  // HVC              — 12%
            default      => 5,  // VVIP/Management  — 8%
        };
    }
}
