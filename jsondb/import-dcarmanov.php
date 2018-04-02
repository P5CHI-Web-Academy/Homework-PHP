<?php

if ($_SERVER['argc'] !== 2) {

    die('Incorrect input.' . PHP_EOL);
}

$path = realpath($_SERVER['argv'][1]);

if(is_dir($path)) {

    $dir_files = array_slice(scandir($_SERVER['argv'][1]), 2);

}else {

    die('Your path is wrong.' . PHP_EOL);
}

try {
    $database = new PDO('sqlite:database.db');

    if (!$database) die ("Can not create database!");

    $database->exec("CREATE TABLE IF NOT EXISTS messages (
        id INTEGER PRIMARY KEY, 
        date TEXT, 
        type TEXT,
        owner TEXT,
        message TEXT
    )");

    $st = $database->prepare("INSERT INTO messages (date, type, owner, message) VALUES (:date, :type, :owner, :message)");

    foreach ($dir_files as $file) {

        $fullpath = $path . DIRECTORY_SEPARATOR . $file;
        $data = false;

        if(preg_match('/\.json$/', $file) && is_file($fullpath)) {

            $data = json_decode(file_get_contents($fullpath), true);
            unlink($fullpath);
        }

        if(!is_array($data) || (json_last_error() !== JSON_ERROR_NONE)) {

            continue;
        }

    }

}catch(PDOException $e) {

    die('PDOException : '.$e->getMessage() . PHP_EOL);
}
