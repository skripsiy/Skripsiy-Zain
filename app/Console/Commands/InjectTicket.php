<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Support\RandomTicketData;
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
     * Execute the console command.
     */
    public function handle()
    {
        $data = RandomTicketData::generate([
            'namacust' => 'SIM-' . str_replace('SIM-', '', (string) (fake()->name() ?? 'Demo')),
            'resume' => '[SIM] ' . 'Tiket inject otomatis untuk demo.',
        ]);

        $ticket = Ticket::create($data);

        $ticket->refresh();
        $urgencyLabel = $ticket->urgency_label;
        $actualUrgency = $ticket->urgency_level;
        $reportedPriority = $ticket->reportedpriority;
        $namaCustomer = $ticket->namacust;
        $topic = $ticket->topic;
        $jenisTicket = $ticket->jenisTicket;

        $this->info("✅ Tiket #{$ticket->idTicket} berhasil di-inject!");
        $this->line("   📋 Pelanggan : {$namaCustomer}");
        $this->line("   🔖 Topik     : {$topic}");
        $this->line("   🚨 Urgency   : Level {$actualUrgency} ({$urgencyLabel})");
        $this->line("   📌 Priority  : {$reportedPriority}");
        $this->line("   📞 Jenis     : {$jenisTicket}");
        $this->line("   📍 Divisi    : {$ticket->division_label}");

        if ($ticket->status === 'ASSIGNED') {
            $this->line("   👤 Assign ke : {$ticket->assignedTo?->name}");
            $this->info("   → Auto-assigned via round-robin ke agent");
        } else {
            $this->warn("   → Masuk loker Team Leader (urgency tinggi, perlu assign manual)");
        }

        $this->line("   🕐 Waktu     : " . Carbon::now()->format('H:i:s'));

        return Command::SUCCESS;
    }
}
