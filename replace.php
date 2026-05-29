<?php

$file = 'resources/views/agent/ticket-detail.blade.php';
$content = file_get_contents($file);

$startStr = '<div class="form-grid">';
$endStr = '<div class="form-group">
                        <label class="form-label">Deskripsi</label>';

$startIdx = strpos($content, $startStr);
$endIdx = strpos($content, $endStr);

if ($startIdx === false || $endIdx === false) {
    die("Could not find boundaries.");
}

$gridsArea = substr($content, $startIdx, $endIdx - $startIdx);

// Extract all form groups
preg_match_all('/<div class="form-group">.*?<\/div>\s*/s', $gridsArea, $matches);
$groups = $matches[0];

$order = [
    'resolved_by_agent',
    'eksalasiVia',
    'contact',
    'reasonnoODS',
    'responBE'
];

$groupMap = [];
foreach ($groups as $g) {
    if (preg_match('/name="([^"]+)"/', $g, $m)) {
        $groupMap[$m[1]] = trim($g);
    }
}

$orderedGroups = [];
foreach ($order as $o) {
    if (isset($groupMap[$o])) {
        $orderedGroups[] = $groupMap[$o];
        unset($groupMap[$o]);
    }
}

foreach ($groupMap as $v) {
    $orderedGroups[] = $v;
}

$newGridsArea = "";
$chunks = array_chunk($orderedGroups, 4);
foreach ($chunks as $chunk) {
    $newGridsArea .= '                    <div class="form-grid">' . "\n";
    foreach ($chunk as $g) {
        $lines = explode("\n", $g);
        foreach ($lines as $line) {
            $newGridsArea .= '                        ' . trim($line) . "\n";
        }
    }
    $newGridsArea .= '                    </div>' . "\n\n";
}

$newContent = substr_replace($content, $newGridsArea, $startIdx, $endIdx - $startIdx);
file_put_contents($file, $newContent);

echo "Reordered successfully!\n";
