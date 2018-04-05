<?php

if ($_SERVER['argc'] !== 2) {

    die('Incorrect input' . PHP_EOL);
}

$path = $_SERVER['argv'][1];

if(!is_dir($path) && !mkdir($path, 0777, true)) {

    die('Can not make a directory: ' . $path . PHP_EOL);
}

try {

    $database = new PDO('sqlite:database.db');
    $st = $database->query("SELECT type, COUNT(*) as count FROM messages GROUP BY type");

    if($st) {

        $res = $st->fetchAll();

    }else{

        die('No messages' . PHP_EOL);
    }

}catch(PDOException $e) {

    die('PDOException : '.$e->getMessage());
}

$path = realpath($path) . DIRECTORY_SEPARATOR . 'count.json';
$res = array_combine(array_column($res, 'type'), array_map('intval', array_column($res, 'count')));

print((file_put_contents($path, json_encode($res)) ? 'All right!' : 'Can not write to ' . $path) . PHP_EOL);