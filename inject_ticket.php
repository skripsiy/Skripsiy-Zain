<?php
$agent = App\Models\User::where("role", "agent")->first();
if ($agent) {
    App\Models\Ticket::create([
        "assignby" => $agent->name,
        "topic" => "Test Dashboard",
        "condition" => "In Progress",
        "status" => "ASSIGNED",
        "idlaporan" => 999999,
        "namacust" => "Budi Test",
        "datereport" => now(),
        "notelpCust" => "08123456789",
        "jenisTicket" => "Gamas",
        "resume" => "Pending"
    ]);
    echo "Successfully injected ticket for Agent: " . $agent->name . PHP_EOL;
} else {
    echo "No agent found!" . PHP_EOL;
}
