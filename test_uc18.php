<?php
use App\Models\Ticket;
use App\Models\User;

$agent = User::where("role", "agent")->first();
$agentName = $agent ? $agent->name : 'Dummy Agent';

$topics = ["NETWORK_DOWN", "ROUTER_BROKEN", "SPEED_SLOW", "LOGIN_FAILED", "PAYMENT_ISSUE"];
$statuses = ["QUEUED", "ASSIGNED", "CLOSED"];
$customers = ["Budi Test", "Siti", "Andi", "Rudi", "Tika"];

echo "Injecting 25 test tickets for pagination...\n";

for ($i = 1; $i <= 25; $i++) {
    $topic = $topics[array_rand($topics)];
    $status = $statuses[array_rand($statuses)];
    
    // Logic matching
    $condition = "Open";
    if ($status === "ASSIGNED") $condition = "In Progress";
    if ($status === "CLOSED") $condition = "Closed";

    $daysAgo = rand(0, 10);
    $date = now()->subDays($daysAgo);

    Ticket::create([
        "assignby" => ($status !== "QUEUED") ? $agentName : null,
        "topic" => $topic . " #" . $i,
        "condition" => $condition,
        "status" => $status,
        "idlaporan" => 200000 + $i,
        "namacust" => $customers[array_rand($customers)] . " " . $i,
        "datereport" => $date,
        "notelpCust" => "081234" . str_pad($i, 4, '0', STR_PAD_LEFT),
        "jenisTicket" => "Gamas",
        "created_at" => $date,
        "updated_at" => $date
    ]);
}

echo "25 Test Tickets injected successfully!\n";
$total = Ticket::count();
echo "Total Tickets in DB now: $total\n";
