<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Http; // Untuk metode REST API Telkomsel
// use Illuminate\Support\Facades\DB;   // Untuk metode Multiple DB Telkomsel

class CheckSlaTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:check-sla';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for tickets that have passed the 6-hour SLA and dispatch notifications.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /*
        // ====================================================================================
        // IMPLEMENTASI MASA DEPAN: SINKRONISASI DATABASE TELKOMSEL
        // ====================================================================================
        
        // --- OPSI A: REST API PULL (Direkomendasikan) ---
        // try {
        //     $response = Http::get('https://api.telkomsel.co.id/v1/tickets/active');
        //     if ($response->successful()) {
        //         $ticketsData = $response->json();
        //         foreach ($ticketsData as $data) {
        //             Ticket::updateOrCreate(
        //                 ['idTicket' => $data['id']],
        //                 [
        //                     'topic' => $data['topic'],
        //                     'condition' => $data['status'],
        //                     'datereport' => $data['created_at']
        //                 ]
        //             );
        //         }
        //     }
        // } catch (\Exception $e) {
        //     Log::error("Gagal sinkronisasi data dari API Telkomsel: " . $e->getMessage());
        // }

        // --- OPSI B: MULTIPLE DATABASE (Direct Read-Only) ---
        // try {
        //     // Baca data langsung dari database Oracle/SQLServer Telkomsel
        //     $telkomselTickets = DB::connection('telkomsel')->table('TICKETS')->where('STATUS', 'OPEN')->get();
        //     foreach ($telkomselTickets as $t) {
        //         Ticket::updateOrCreate(['idTicket' => $t->TICKET_ID], ['topic' => $t->TITLE, ...]);
        //     }
        // } catch (\Exception $e) {
        //     Log::error("Gagal konek DB Telkomsel: " . $e->getMessage());
        // }
        // ====================================================================================
        */

        $this->info('Memulai pengecekan SLA Tiket (6 Jam)...');

        // Batas waktu 6 jam yang lalu
        $batasWaktu = Carbon::now()->subHours(6);

        // Ambil tiket yang statusnya BUKAN Closed DAN sudah lewat 6 jam dari datereport/created_at
        $tiketNyangkut = Ticket::where('condition', '!=', 'Closed')
            ->where('datereport', '<=', $batasWaktu)
            // ->where('sla_notified', false) // Flag opsional agar tidak spam notif berkali-kali
            ->get();

        if ($tiketNyangkut->isEmpty()) {
            $this->info('Semua tiket aman. Tidak ada pelanggaran SLA 6 jam.');
            return Command::SUCCESS;
        }

        foreach ($tiketNyangkut as $ticket) {
            /*
            // ====================================================================================
            // LOGIKA DISPATCH / PENGIRIMAN NOTIFIKASI
            // ====================================================================================
            // Contoh 1: Notifikasi internal sistem (Database Notification Laravel)
            // Notification::send($teamLeaders, new TicketSlaBreachNotification($ticket));
            
            // Contoh 2: Kirim email/WhatsApp API ke tim dispatch
            // dispatch(new SendWhatsAppNotificationJob($ticket->assignby, "Tiket {$ticket->idTicket} telah melewati SLA 6 Jam!"));
            
            // Contoh 3: Update flag agar tidak diproses berulang-ulang
            // $ticket->update(['sla_notified' => true]);
            // ====================================================================================
            */
            
            Log::warning("SLA BREACH ALERT: Tiket {$ticket->idTicket} telah melewati batas 6 jam!");
        }

        $this->info("Pengecekan selesai. Ditemukan {$tiketNyangkut->count()} tiket melewati SLA.");
        
        return Command::SUCCESS;
    }
}
