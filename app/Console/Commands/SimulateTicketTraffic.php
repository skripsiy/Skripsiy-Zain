<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Models\User;
use App\Support\RandomTicketData;
use Illuminate\Console\Command;

class SimulateTicketTraffic extends Command
{
    protected $signature = 'tickets:simulate
        {--interval=10 : Jeda detik antar tiket}
        {--max=0 : Batas jumlah tiket, 0 berarti tanpa batas}
        {--high=0.4 : Proporsi tiket urgensi tinggi (3-5) untuk loker Team Leader}';

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
        $max      = max(0, (int) $this->option('max'));
        $high     = max(0.0, min(1.0, (float) $this->option('high')));

        // Tampilkan status agent online sebelum loop (read-only, tidak ubah state apapun)
        $this->checkAndWarnOnlineAgents();

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

            $urgency  = (int) ($ticket->urgency_level ?? 1);
            $division = $ticket->division_target ?? '?';
            $assigned = !empty($ticket->assigned_to_user_id);

            if ($assigned) {
                $agentName = $ticket->assignedTo?->name ?? '-';
                $this->info(sprintf(
                    '[%s] #%s [%s] -> Auto-assign (round-robin) ke agent: %s',
                    now()->format('H:i:s'),
                    $ticket->idTicket,
                    strtoupper($division),
                    $agentName
                ));
            } else {
                if ($urgency >= 3) {
                    $this->warn(sprintf(
                        '[%s] #%s [%s] -> Masuk loker Team Leader (urgency %d)',
                        now()->format('H:i:s'),
                        $ticket->idTicket,
                        strtoupper($division),
                        $urgency
                    ));
                } else {
                    $this->error(sprintf(
                        '[%s] #%s [%s] -> QUEUED (tak ada agent online di divisi %s)',
                        now()->format('H:i:s'),
                        $ticket->idTicket,
                        strtoupper($division),
                        $division
                    ));
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

    /**
     * Tampilkan ringkasan agent online per divisi.
     * READ-ONLY — tidak mengubah status apapun di database.
     */
    private function checkAndWarnOnlineAgents(): void
    {
        $divisions    = array_keys(config('tickets.simulation_divisions', []));
        $hasAnyOnline = false;

        foreach ($divisions as $div) {
            $count = User::where('role', 'agent')
                ->where('status', 'active')
                ->whereRaw('LOWER(campaign) = ?', [strtolower($div)])
                ->whereHas('workSessions', function ($q) {
                    $q->where('work_date', today())->where('status', 'online');
                })
                ->count();

            if ($count > 0) {
                $this->line(sprintf(
                    '  <info>✓</info> Divisi <comment>%s</comment>: <info>%d agent online</info>',
                    strtoupper($div),
                    $count
                ));
                $hasAnyOnline = true;
            } else {
                $this->line(sprintf(
                    '  <comment>⚠</comment> Divisi <comment>%s</comment>: <error>tidak ada agent online</error> — tiket ke divisi ini akan QUEUED',
                    strtoupper($div)
                ));
            }
        }

        if (!$hasAnyOnline) {
            $this->error('');
            $this->error('⚠  PERINGATAN: Tidak ada agent online di semua divisi!');
            $this->error('   Semua tiket urgency rendah akan QUEUED.');
            $this->error('   Silakan login sebagai agent dan aktifkan status online terlebih dahulu.');
            $this->error('');
        }

        $this->line('');
    }
}
