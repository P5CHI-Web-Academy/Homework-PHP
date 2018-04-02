<?php

function file_force_contents($dir, $contents){
    $parts = explode(DIRECTORY_SEPARATOR, $dir);
    $file = array_pop($parts);
    $dir = '';

    foreach($parts as $part) {
        if (! is_dir($dir .= "{$part}/")) mkdir($dir);
    }

    return file_put_contents("{$dir}{$file}", $contents);
}

if ($_SERVER['argc'] != 2) {
    echo 'Invalid parameter number' . PHP_EOL;
    exit(0);
}

$owner = trim($_SERVER['argv'][1]);

// insert into db
$db = new PDO('sqlite:vbruma.db');

$rows = [];
try {
    $statement = $db->query('SELECT COUNT(id) as total, type FROM logs WHERE owner = :owner GROUP BY type');

    if ($statement) {
        $statement->execute([':owner' => $owner]);
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
} catch (\Exception $e) {
    echo 'DB Error: ' . $e->getMessage() . PHP_EOL;
    exit(0);
}

if (! count($result)) {
    echo 'No rows were found' . PHP_EOL;
    exit(0);
}

$jsonData = [];
foreach ($result as $entry) {
    $jsonData[strtolower($entry['type'])] = $entry['total'];
}

$filePath =  '.' . DIRECTORY_SEPARATOR . 'report' . DIRECTORY_SEPARATOR . $owner . DIRECTORY_SEPARATOR . 'counts.json';
file_force_contents($filePath, json_encode($jsonData));

echo 'Done!' . PHP_EOL;
