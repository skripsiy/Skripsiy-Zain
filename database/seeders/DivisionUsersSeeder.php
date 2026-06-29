<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DivisionUsersSeeder extends Seeder
{
    /**
     * Seed agent & team leader per divisi (Area, Besfixed, Saltik).
     * Gunakan: php artisan db:seed --class=DivisionUsersSeeder
     */
    public function run(): void
    {
        // ─── AREA ────────────────────────────────────────────────────
        $this->createTeamLeader(
            name:     'TL Area',
            email:    'tl.area@xena.com',
            username: 'tl_area',
            campaign: 'area',
        );

        $areaAgents = [
            ['Budi Santoso',    'budi.area@xena.com',    'budi_area'],
            ['Dewi Rahayu',     'dewi.area@xena.com',    'dewi_area'],
            ['Farhan Hidayat',  'farhan.area@xena.com',  'farhan_area'],
            ['Gita Permata',    'gita.area@xena.com',    'gita_area'],
            ['Hendra Wijaya',   'hendra.area@xena.com',  'hendra_area'],
        ];
        foreach ($areaAgents as [$name, $email, $username]) {
            $this->createAgent($name, $email, $username, 'area');
        }

        // ─── BESFIXED ────────────────────────────────────────────────
        $this->createTeamLeader(
            name:     'TL Besfixed',
            email:    'tl.besfixed@xena.com',
            username: 'tl_besfixed',
            campaign: 'besfixed',
        );

        $besfixedAgents = [
            ['Irfan Maulana',   'irfan.bes@xena.com',    'irfan_bes'],
            ['Juliana Putri',   'juliana.bes@xena.com',  'juliana_bes'],
            ['Kevin Firmansyah','kevin.bes@xena.com',    'kevin_bes'],
            ['Linda Sari',      'linda.bes@xena.com',    'linda_bes'],
            ['Muhamad Rizki',   'rizki.bes@xena.com',    'rizki_bes'],
        ];
        foreach ($besfixedAgents as [$name, $email, $username]) {
            $this->createAgent($name, $email, $username, 'besfixed');
        }

        // ─── SALTIK ──────────────────────────────────────────────────
        $this->createTeamLeader(
            name:     'TL Saltik',
            email:    'tl.saltik@xena.com',
            username: 'tl_saltik',
            campaign: 'saltik',
        );

        $saltikAgents = [
            ['Nadia Kurniawati', 'nadia.saltik@xena.com',  'nadia_saltik'],
            ['Oscar Pratama',    'oscar.saltik@xena.com',  'oscar_saltik'],
            ['Putri Ayu',        'putri.saltik@xena.com',  'putri_saltik'],
            ['Rendi Saputra',    'rendi.saltik@xena.com',  'rendi_saltik'],
            ['Sinta Maharani',   'sinta.saltik@xena.com',  'sinta_saltik'],
        ];
        foreach ($saltikAgents as [$name, $email, $username]) {
            $this->createAgent($name, $email, $username, 'saltik');
        }

        $this->command->info('✅ Selesai! Data yang dibuat:');
        $this->command->table(
            ['Role', 'Divisi', 'Jumlah'],
            [
                ['Team Leader', 'Area',     '1'],
                ['Team Leader', 'Besfixed', '1'],
                ['Team Leader', 'Saltik',   '1'],
                ['Agent',       'Besfixed', '15'],
            ]
        );
        $this->command->info('Password semua user: password123');
    }

    // ─────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────

    private function createTeamLeader(
        string $name,
        string $email,
        string $username,
        string $campaign,
    ): void {
        User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                'username' => $username,
                'campaign' => $campaign,
                'role'     => 'team_leader',
                'status'   => 'active',
                'password' => 'password123',
                'phone'    => '08' . rand(100000000, 999999999),
                'site'     => 'BANDUNG',
                'area'     => strtoupper($campaign),
            ]
        );
    }

    private function createAgent(
        string $name,
        string $email,
        string $username,
        string $campaign,
    ): void {
        User::updateOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                'username' => $username,
                'campaign' => $campaign,
                'role'     => 'agent',
                'status'   => 'active',
                'password' => 'password123',
                'phone'    => '08' . rand(100000000, 999999999),
                'site'     => 'BANDUNG',
                'area'     => strtoupper($campaign),
            ]
        );
    }
}
