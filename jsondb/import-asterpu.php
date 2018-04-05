<?php

if ($_SERVER['argc'] < 2) {
    die('Missing arguments.' . PHP_EOL);
}

if(is_dir($path = realpath($_SERVER['argv'][1]))) {
    $files = array_slice(scandir($_SERVER['argv'][1]), 2);
}else {
    die('The provided path is incorrect.' . PHP_EOL);
}

try {
    $db = new PDO('sqlite:db.db');

    $db->exec("CREATE TABLE IF NOT EXISTS messages (
        id INTEGER PRIMARY KEY, 
        date TEXT, 
        type TEXT,
        owner TEXT,
        message TEXT
    )");

    $stmt = $db->prepare("INSERT INTO messages (date, type, owner, message) 
        VALUES (:date, :type, :owner, :message)");

    foreach ($files as $file) {
        $fullpath = $path . DIRECTORY_SEPARATOR . $file;

        $data = false;

        if(preg_match('/\.json$/', $file) && is_file($fullpath)) {
            $data = json_decode(file_get_contents($fullpath), true);
            unlink($fullpath);
        }

        if(!is_array($data) || (json_last_error() !== JSON_ERROR_NONE)) {
            continue;
        }

        foreach ($data as $entry) {
            if(
                validateDate($entry['date']) &&
                preg_match('/^[A-Z]+$/', $entry['type']) &&
                preg_match('/^[a-z0-9]+$/', $entry['owner'])
            ) {
                $stmt->execute([
                    ':date' => $entry['date'],
                    ':type' => $entry['type'],
                    ':owner' => $entry['owner'],
                    ':message' => $entry['message']
                ]);
               }
        }
    }
}catch(PDOException $e) {
    die('PDOException : '.$e->getMessage() . PHP_EOL);
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
