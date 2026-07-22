<?php

namespace App\Support;

class RandomTicketData
{
    public static function generate(array $overrides = []): array
    {
        $topics = [
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

        $jenisTicket = ['INTERNET', 'Gamas', 'IPTV', 'VOICE'];
        $contacts = ['Telepon', 'WhatsApp', 'Email', 'Walk-in', 'Telegram'];
        $eksalasiVia = ['Telegram', 'WhatsApp', 'Email'];
        $namaDepan = [
            'Budi',
            'Siti',
            'Andi',
            'Dewi',
            'Rudi',
            'Rina',
            'Agus',
            'Putri',
            'Hendra',
            'Wati',
            'Joko',
            'Lina',
            'Fajar',
            'Mega',
            'Doni',
            'Yuni',
            'Arif',
            'Tina',
            'Bayu',
            'Sri',
            'Rizky',
            'Nurul',
            'Dimas',
            'Ayu',
        ];
        $namaBelakang = [
            'Santoso',
            'Wijaya',
            'Pratama',
            'Sari',
            'Hidayat',
            'Kusuma',
            'Hartono',
            'Rahayu',
            'Putra',
            'Lestari',
            'Setiawan',
            'Handoko',
            'Wibowo',
            'Permata',
            'Nugraha',
            'Susanto',
            'Suryadi',
            'Purnama',
            'Gunawan',
            'Maulana',
        ];
        $detailTemplates = [
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
        $resumeTemplates = [
            'Pelanggan melaporkan gangguan melalui Call Center. Menunggu assign ke agent.',
            'Laporan diterima via WhatsApp. Pelanggan mendesak untuk segera ditangani.',
            'Tiket otomatis dari sistem monitoring. Terdeteksi signal loss di ODC.',
            'Pelanggan walk-in ke Plasa Telkom. Sudah dicatat oleh CSR.',
            'Keluhan diterima via email. Pelanggan sudah komplain 2x sebelumnya.',
            'Laporan dari aplikasi MyIndiHome. Auto-dispatch ke queue.',
            'Eskalasi dari team dispatch. Perlu penanganan segera.',
        ];

        $namaCustomer = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
        $priority = self::randomPriority();
        $urgencyLevel = self::mapPriorityToUrgency($priority);

        $divisions = config('tickets.simulation_divisions');

        // Cek agen yang online hari ini
        $onlineCampaigns = [];
        try {
            $onlineCampaigns = \App\Models\User::where('role', 'agent')
                ->where('status', 'active')
                ->whereHas('workSessions', function ($q) {
                    $q->where('work_date', today())
                        ->where('status', 'online');
                })
                ->pluck('campaign')
                ->filter()
                ->map(fn($c) => strtolower($c))
                ->unique()
                ->toArray();
        } catch (\Exception $e) {
            // Fallback jika terjadi error query/DB
        }

        // Filter divisi simulasi yang memiliki agen online
        $availableDivisions = array_intersect(array_keys($divisions), $onlineCampaigns);

        if (!empty($availableDivisions)) {
            $chosenDivision = $availableDivisions[array_rand($availableDivisions)];
        } else {
            $chosenDivision = array_rand($divisions);
        }

        $pick = $divisions[$chosenDivision];

        $data = [
            'datereport' => now(),
            'jenisTicket' => $pick['jenisTicket'],
            'notelpCust' => '08' . rand(11, 99) . rand(10000000, 99999999),
            'namacust' => $namaCustomer,
            'idlaporan' => rand(500000, 999999),
            'detailticket' => $detailTemplates[array_rand($detailTemplates)],
            'resume' => $resumeTemplates[array_rand($resumeTemplates)],
            'topic' => $topics[array_rand($topics)],
            'noSC' => 'SC' . rand(1000000, 9999999),
            'statusSC' => 'Open',
            'status' => 'New',
            'contact' => $contacts[array_rand($contacts)],
            'reportedpriority' => $priority,
            'eksalasiVia' => $eksalasiVia[array_rand($eksalasiVia)],
            'channel' => $pick['channel'],
            'source_system' => $pick['source_system'],
            'pool_id' => $pick['pool_id'],
            'is_simulated' => 1,
        ];

        // Jika divisi simulasi adalah SALTIK, lengkapi semua isian tiket
        if (strtolower($chosenDivision) === 'saltik') {
            $topicChoice = rand(0, 1) === 0 ? 'Phone Issue' : 'Internet Issue';
            $topicDetails = config("tickets.topicDetail.{$topicChoice}", []);
            $topicDetailKey = !empty($topicDetails) ? array_rand($topicDetails) : null;

            $reasonnoODSList = config('tickets.reasonnoODS', []);
            $reasonnoODSVal = !empty($reasonnoODSList) ? array_rand($reasonnoODSList) : null;

            $responBEList = config('tickets.responBE', []);
            $responBEVal = !empty($responBEList) ? array_rand($responBEList) : null;

            $noSCList = config('tickets.noSC', []);
            $noSCVal = !empty($noSCList) ? array_rand($noSCList) : 'SC' . rand(1000000, 9999999);

            $saltikFields = [
                'klasifikasi' => ['Technical', 'Non-Technical'][rand(0, 1)],
                'topic' => $topicChoice,
                'topicDetail' => $topicDetailKey,
                'noSC' => $noSCVal,
                'statusSC' => ['Open', 'Closed'][rand(0, 1)],
                'validateClose' => 'Yes',
                'reasonnoODS' => $reasonnoODSVal,
                'eksalasiTicket' => ['Yes', 'No'][rand(0, 1)],
                'eksalasiVia' => $eksalasiVia[array_rand($eksalasiVia)],
                'PIC' => 'SALTIK',
                'contact' => $contacts[array_rand($contacts)],
                'responBE' => $responBEVal,
                'description' => 'Pemeriksaan tiket selesai. Semua data tiket telah terisi lengkap.',
                'hasil_pengecekan' => 'Sinyal dan koneksi jaringan sudah diverifikasi normal. Data ODS dan SC sesuai.',
                'resolved_by_agent' => 'Succes Resolved',
                'regional' => 'REGIONAL ' . rand(1, 7),
                'witel' => 'WITEL ' . ['JAKARTA', 'BANDUNG', 'SURABAYA', 'MEDAN', 'SEMARANG', 'DENPASAR'][rand(0, 5)],
                'gamas' => '0',
                'lapul' => rand(0, 2),
                'gaul' => rand(0, 1),
            ];

            $data = array_merge($data, $saltikFields);
        }

        return array_merge($data, $overrides);
    }

    public static function generateForSimulation(float $highProbability = 0.4, array $overrides = []): array
    {
        $rand = mt_rand(1, 100) / 100;
        $high = $rand <= $highProbability;
        $priority = $high ? self::randomHighPriority() : self::randomLowPriority();

        return self::generate(array_merge($overrides, [
            'reportedpriority' => $priority,
            'urgency_level' => self::mapPriorityToUrgency($priority),
        ]));
    }

    private static function randomPriority(): string
    {
        $priorities = ['Low Emergency', 'Emergency', 'Super Emergency', 'HVC', 'VVIP'];
        return $priorities[array_rand($priorities)];
    }

    private static function randomHighPriority(): string
    {
        $priorities = ['Super Emergency', 'HVC', 'VVIP'];
        return $priorities[array_rand($priorities)];
    }

    private static function randomLowPriority(): string
    {
        $priorities = ['Low Emergency', 'Emergency'];
        return $priorities[array_rand($priorities)];
    }

    public static function mapPriorityToUrgency(string $priority): int
    {
        $priority = strtoupper(trim($priority ?? ''));

        if (str_contains($priority, 'VVIP') || str_contains($priority, 'MANAGEMENT')) {
            return 5;
        }

        if (str_contains($priority, 'HVC') || str_contains($priority, 'HIGH VALUE')) {
            return 4;
        }

        if (str_contains($priority, 'SUPER EMERGENCY') || str_contains($priority, 'SUPER_EMERGENCY') || $priority === 'SE') {
            return 3;
        }

        if (str_contains($priority, 'EMERGENCY') && !str_contains($priority, 'LOW')) {
            return 2;
        }

        return 1;
    }
}
