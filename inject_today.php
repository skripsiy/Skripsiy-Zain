<?php
use App\Models\Ticket;
use App\Models\User;

$agent = User::where("role", "agent")->first();
$agentName = $agent ? $agent->name : 'Dummy Agent';

echo "Injecting 5 tickets strictly for TODAY...\n";

for ($i = 1; $i <= 5; $i++) {
    Ticket::create([
        "assignby" => $agentName,
        "topic" => "TODAY TICKET #" . $i,
        "condition" => "In Progress",
        "status" => "ASSIGNED",
        "idlaporan" => 300000 + $i,
        "namacust" => "Today User " . $i,
        "datereport" => now(),
        "notelpCust" => "081234555",
        "jenisTicket" => "Helpdesk C4",
        "created_at" => now(),
        "updated_at" => now()
    ]);
}

$todayCount = Ticket::whereDate('created_at', now()->today())->count();
echo "Successfully injected! Total tickets for today in DB: $todayCount\n";
