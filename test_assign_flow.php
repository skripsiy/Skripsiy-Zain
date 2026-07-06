<?php

use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketRoutingService;
use Illuminate\Support\Facades\Cache;

$ticket = Ticket::create([
    'datereport' => now(),
    'jenisTicket' => 'INTERNET',
    'namacust' => 'Test Routing Flow',
    'idlaporan' => rand(100000, 999999),
    'reportedpriority' => 'Emergency',
    'notelpCust' => '0812345678',
]);

$division = 'besfixed';
$onlineAgents = User::where('role', 'agent')
    ->where('status', 'active')
    ->whereRaw('LOWER(campaign) = ?', [strtolower($division)])
    ->whereHas('workSessions', function ($q) {
        $q->where('work_date', today())
          ->where('status', 'online');
    })
    ->orderBy('id')
    ->get();

echo "Online Agents count: " . $onlineAgents->count() . "\n";
if ($onlineAgents->isEmpty()) {
    echo "No online agents found.\n";
    exit;
}

$cacheKey = "rr_index_{$division}";
$index = Cache::get($cacheKey, 0);
echo "Current rr_index in cache: " . var_export($index, true) . "\n";

if ($index >= $onlineAgents->count()) {
    $index = 0;
}

$agent = $onlineAgents[$index];
echo "Selected Agent: {$agent->id} - {$agent->name}\n";

$nextIndex = ($index + 1) % $onlineAgents->count();
Cache::put($cacheKey, $nextIndex, 3600);
echo "New rr_index stored in cache: {$nextIndex}\n";

try {
    $res = $ticket->update([
        'assignby'        => $agent->name,
        'condition'       => 'ASSIGNED',
        'status'          => 'ASSIGNED',
        'auto_assigned_at' => now(),
    ]);
    echo "Update result: " . var_export($res, true) . "\n";
    echo "Ticket status after update: " . $ticket->status . "\n";
    echo "Ticket assignby after update: " . $ticket->assignby . "\n";
} catch (\Exception $e) {
    echo "Update failed: " . $e->getMessage() . "\n";
}
