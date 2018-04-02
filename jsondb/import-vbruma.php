<?php

if ($_SERVER['argc'] != 2) {
    echo 'Invalid parameter number' . PHP_EOL;
    exit(0);
}

$dirName = '.' . DIRECTORY_SEPARATOR . $_SERVER['argv'][1];
$jsonArr = [];
$scannedDir = [];
$processedFiles = [];

// scan directory/files and fetch data
if (is_dir($dirName)) {
    $scannedDir = array_diff(scandir("$dirName"), array('..', '.'));

    foreach ($scannedDir as $fileName) {
        if (preg_match('/[\w\d]+.json$/', $fileName)) {
            $filePath = $dirName . DIRECTORY_SEPARATOR . $fileName;
            $processedFiles[] = $filePath;
            $data = file_get_contents($filePath);
            $decodedData = $data ? json_decode($data, true) : null;
            $jsonArr = is_array($decodedData) ? array_merge($jsonArr, $decodedData) : $jsonArr;
        }
    }

} else {
    echo "'$dirName' is not a directory";
    exit(0);
}

// validate/filter data
$filteredArr = [];
foreach($jsonArr as $entry) {
    $dt = isset($entry['date']) ? \DateTime::createFromFormat("Y-m-d", $entry['date']) : false;
    if ( $dt === false || ($dt instanceof \DateTime && array_sum($dt->getLastErrors()) )
        || ($dt instanceof \DateTime && $dt->format('Y-m-d') !== $entry['date'])
        || !isset($entry['type']) || $entry['type'] !== strtoupper($entry['type'])
        || !isset($entry['owner']) || $entry['owner'] !== strtolower($entry['owner'])
        || !ctype_alnum($entry['owner'])
    ) {

    } else {
        $filteredArr[] = $entry;
    }
}

if (!count($filteredArr)) {
    echo 'No valid entries found' . PHP_EOL;
    exit(0);
}

// insert into db
$db = new PDO('sqlite:vbruma.db');
$db->beginTransaction();

//create table
try {
    $stmt = $db->prepare(
        'CREATE TABLE IF NOT EXISTS logs (
            id INTEGER PRIMARY KEY,
            \'date\' VARCHAR (20) NOT NULL,
            \'type\' VARCHAR (50) NOT NULL,
            owner VARCHAR (50) NOT NULL,
            message TEXT
        )'
    );

    if ($stmt) {
        $stmt->execute();
    }
} catch (\Exception $e) {
    $db->rollBack();
    echo 'DB Error: ' . $e->getMessage() . PHP_EOL;
    exit(0);
}

// insert rows
try {
    $stmt = $db->prepare(
        'INSERT INTO logs(\'date\', \'type\', owner, message) VALUES(:date, :type, :owner, :message)'
    );

    if ($stmt) {
        foreach ($filteredArr as $entry) {
            $stmt->execute([
                ':date' => $entry['date'],
                ':type' => $entry['type'],
                ':owner' => $entry['owner'],
                ':message' => $entry['message']
            ]);
        }
    }
} catch (\Exception $e) {
    echo 'DB Error: ' . $e->getMessage() . PHP_EOL;
    exit(0);
}

// commit
try {
    $db->commit();
} catch (\Exception $e) {
    echo 'DB Error: ' . $e->getMessage() . PHP_EOL;
    $db->rollBack();
    exit(0);
}

// clean files
foreach ($processedFiles as $fileToRemove) {
    unlink($fileToRemove);
}

echo 'Done!' . PHP_EOL;

