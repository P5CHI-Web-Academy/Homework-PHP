<?php

if ($argc != 2) {
    die("Incorrect input\n");
}

$logData = [];
$dbc = new PDO('sqlite:cerednicenko.db');

$query = $dbc->prepare("SELECT 
    `type`, count(*) as count 
    from homework 
    where owner =:owner 
    GROUP BY `type`;"
);

$query->execute([':owner' => $argv[1]]);

$result = $query->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
    $path = __DIR__ . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR . $argv[1];

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }

    foreach ($result as $row) {
        $logData[$row['type']] = $row['count'];
    }

    file_put_contents($path . DIRECTORY_SEPARATOR . 'counts.json', json_encode($logData));
} else {
    echo "Unknown owner\n";
}
