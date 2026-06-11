<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use Carbon\Carbon;

class InjectDemoTicketsSeeder extends Seeder
{
    /**
     * Run the database seeds to inject new tickets.
     * php artisan db:seed --class=InjectDemoTicketsSeeder
     */
    public function run(): void
    {
        $this->command->info('📥 Preparing large-scale live injection (15 tickets per division)...');

        $divisionsConfig = [
            'area' => [
                'count' => 15,
                'source_system' => 'INSERA',
                'channel' => '21',
                'pool_id' => 'Network Service Desk',
                'priorities' => ['Low', 'Emergency'],
                'jenis' => 'INTERNET',
            ],
            'besfixed' => [
                'count' => 15,
                'source_system' => 'INSERA',
                'channel' => '19',
                'pool_id' => 'new_site179 BESFIXED',
                'priorities' => ['Low', 'Emergency'],
                'jenis' => 'INTERNET',
            ],
            'saltik' => [
                'count' => 15,
                'source_system' => 'DSC',
                'channel' => '2',
                'pool_id' => 'SALAM SIMPATIK',
                'priorities' => ['Low', 'Emergency'],
                'jenis' => 'TELEPON',
            ],
        ];

        $totalInjected = 0;

        foreach ($divisionsConfig as $divName => $config) {
            $this->command->info("👉 Injecting {$config['count']} tickets for division [" . strtoupper($divName) . "]...");
            
            for ($i = 1; $i <= $config['count']; $i++) {
                $priority = $config['priorities'][array_rand($config['priorities'])];
                
                $createdAt = Carbon::now()->subHours(7);
                $ticket = new Ticket([
                    'datereport' => $createdAt,
                    'jenisTicket' => $config['jenis'],
                    'notelpCust' => '0821' . rand(10000000, 99999999),
                    'namacust' => "Live Cust " . ucfirst($divName) . " #{$i}",
                    'detailticket' => "Gangguan live-test skala besar untuk divisi " . ucfirst($divName) . " nomor {$i}.",
                    'idlaporan' => rand(800000, 899999),
                    'noSC' => 'SC' . rand(6000000, 6999999),
                    'statusSC' => 'Open',
                    'status' => 'New',
                    'contact' => 'Aplikasi',
                    'eksalasiVia' => 'Telegram',
                    'resume' => 'Inject Skala Besar: ' . ucfirst($divName) . " #{$i}",
                    
                    // Routing criteria
                    'source_system' => $config['source_system'],
                    'channel' => $config['channel'],
                    'pool_id' => $config['pool_id'],
                    'reportedpriority' => $priority,
                ]);
                $ticket->created_at = $createdAt;
                $ticket->save();

                $this->command->info(sprintf(
                    '  [%d/%d] Ticket #%d (%s) -> Routed to [%s] -> Assigned to: %s',
                    $i,
                    $config['count'],
                    $ticket->idTicket,
                    $ticket->namacust,
                    strtoupper($ticket->division_target),
                    $ticket->assignby ?? 'UNASSIGNED'
                ));

                $totalInjected++;
            }
        }

        $this->command->info("\n🚀 Injected {$totalInjected} tickets in total! Every online agent got exactly 3 new tickets.");
    }
}
