<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Support\RandomTicketData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SimulateTicketTraffic extends Command
{
    protected $signature = 'tickets:simulate {--interval=10 : Jeda detik antar tiket} {--max=0 : Batas jumlah tiket, 0 berarti tanpa batas} {--high=0.4 : Proporsi tiket urgensi tinggi (3-5) untuk loker Team Leader}';

    protected $description = 'Membuat tiket demo secara berkala dengan data acak untuk menguji routing otomatis.';

    private bool $stopRequested = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if (function_exists('pcntl_async_signals')) {
            pcntl_async_signals(true);
        }

        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGINT, function () {
                $this->stopRequested = true;
            });
            pcntl_signal(SIGTERM, function () {
                $this->stopRequested = true;
            });
        }

        $interval = max(1, (int) $this->option('interval'));
        $max = max(0, (int) $this->option('max'));
        $high = max(0.0, min(1.0, (float) $this->option('high')));

        $count = 0;

        while (!$this->stopRequested) {
            if ($max > 0 && $count >= $max) {
                break;
            }

            $data = RandomTicketData::generateForSimulation($high, [
                'resume' => '[SIM] Tiket demo otomatis untuk traffic testing',
            ]);

            $ticket = Ticket::create($data);
            $ticket->refresh();

            $urgency = (int) ($ticket->urgency_level ?? 1);
            $assigned = !empty($ticket->assigned_to_user_id);

            if ($assigned) {
                $this->info(sprintf("[%s] #%s -> Auto-assign (round-robin) ke agent: %s", now()->format('H:i:s'), $ticket->idTicket, ($ticket->assignedTo?->name ?? '-')));
            } else {
                if ($urgency >= 3) {
                    $this->warn(sprintf("[%s] #%s -> Masuk loker Team Leader", now()->format('H:i:s'), $ticket->idTicket));
                } else {
                    $this->comment(sprintf("[%s] #%s -> QUEUED (tak ada agent online)", now()->format('H:i:s'), $ticket->idTicket));
                }
            }

            $count++;

            if ($max > 0 && $count >= $max) {
                break;
            }

            sleep($interval);
        }

        $this->info("Simulator berhenti. Total tiket dibuat: {$count}");

        return Command::SUCCESS;
    }
}
