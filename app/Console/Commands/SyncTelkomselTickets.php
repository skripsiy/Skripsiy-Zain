<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Http; // Untuk integrasi REST API masa depan
// use Illuminate\Support\Facades\DB;   // Untuk integrasi multiple database masa depan

class SyncTelkomselTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:sync-telkomsel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync active dispatched tickets with Telkomsel DB and close resolved ones (locally mocked for now).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi tiket DISPATCHED dengan Telkomsel...');

        // Ambil tiket yang kondisinya Dispatched / DISPATCHED
        $dispatchedTickets = Ticket::whereIn('condition', ['Dispatched', 'DISPATCHED'])
            ->where('condition', '!=', 'Closed')
            ->get();

        if ($dispatchedTickets->isEmpty()) {
            $this->info('Tidak ada tiket dengan status DISPATCHED yang perlu disinkronkan.');
            return Command::SUCCESS;
        }

        /*
        // ====================================================================================
        // IMPLEMENTASI INTEGRASI DATABASE TELKOMSEL (NO. 3) - MASA DEPAN
        // ====================================================================================
        
        // --- OPSI A: REST API PULL ---
        // try {
        //     foreach ($dispatchedTickets as $ticket) {
        //         $response = Http::get("https://api.telkomsel.co.id/v1/tickets/{$ticket->idTicket}/status");
        //         if ($response->successful()) {
        //             $externalData = $response->json();
        //             if (isset($externalData['status']) && $externalData['status'] === 'RESOLVED') {
        //                 $ticket->update([
        //                     'condition' => 'Closed',
        //                     'status' => 'Closed',
        //                     'datesolved' => now(),
        //                     'solvedby' => 'System Auto Sync'
        //                 ]);
        //             }
        //         }
        //     }
        // } catch (\Exception $e) {
        //     Log::error("Gagal sinkronisasi API Telkomsel untuk closing: " . $e->getMessage());
        // }

        // --- OPSI B: MULTIPLE DATABASE QUERY ---
        // try {
        //     foreach ($dispatchedTickets as $ticket) {
        //         $externalTicket = DB::connection('telkomsel')
        //             ->table('TICKETS')
        //             ->where('TICKET_ID', $ticket->idTicket)
        //             ->first();
        //             
        //         if ($externalTicket && $externalTicket->STATUS === 'CLOSED') {
        //             $ticket->update([
        //                 'condition' => 'Closed',
        //                 'status' => 'Closed',
        //                 'datesolved' => now(),
        //                 'solvedby' => 'System Auto Sync'
        //             ]);
        //         }
        //     }
        // } catch (\Exception $e) {
        //     Log::error("Gagal kueri DB Telkomsel untuk closing: " . $e->getMessage());
        // }
        // ====================================================================================
        */

        // ====================================================================================
        // SIMULASI LOKAL (MOCK RESOLVER)
        // ====================================================================================
        $resolvedCount = 0;
        
        foreach ($dispatchedTickets as $ticket) {
            // Simulasi lokal: 50% tiket diselesaikan secara acak
            $shouldResolve = rand(0, 1) === 1;

            if ($shouldResolve) {
                $oldCondition = $ticket->condition;
                $oldStatus = $ticket->status;

                $ticket->update([
                    'condition' => 'Closed',
                    'status' => 'Closed',
                    'datesolved' => now(),
                    'solvedby' => 'System Auto Sync (Mock)'
                ]);

                // Log aktivitas
                Log::info("SYNC MOCK: Tiket {$ticket->idTicket} berhasil diselesaikan di Telkomsel (Simulasi) dan ditutup di XENA.");
                
                // Spatie Activitylog akan mencatat perubahan secara otomatis via LogsActivity trait pada Model
                
                $this->line(" - Tiket {$ticket->idTicket}: Status diubah dari '{$oldStatus}'/'{$oldCondition}' menjadi 'Closed'.");
                $resolvedCount++;
            } else {
                $this->line(" - Tiket {$ticket->idTicket}: Masih dalam proses di Telkomsel (Simulasi).");
            }
        }

        $this->info("Sinkronisasi selesai. {$resolvedCount} tiket dari {$dispatchedTickets->count()} berhasil ditutup.");

        return Command::SUCCESS;
    }
}
