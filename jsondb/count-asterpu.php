<?php

if ($_SERVER['argc'] < 2) {
    die('Missing arguments.' . PHP_EOL);
}

if(!is_dir($path = $_SERVER['argv'][1]) && !mkdir($path, 0777, true)) {
    die('Couldn\'t create a directory: ' . $path . PHP_EOL);
}


try {
    $db = new PDO('sqlite:db.db');
    $stmt = $db->query("SELECT type, COUNT(*) as count FROM messages GROUP BY type");

    if($stmt) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);    
    }else{
        die('There\'s no messages' . PHP_EOL);
    }
}catch(PDOException $e) {
    die('PDOException : '.$e->getMessage());
}

$path = realpath($path) . DIRECTORY_SEPARATOR . 'counts.json';

$result = array_combine(array_column($result, 'type'), array_map('intval', array_column($result, 'count')));

print((file_put_contents($path, json_encode($result)) ? 'Done!' : 'Could\'t write to ' . $path) . PHP_EOL);
