<?php
/**
 * Created by PhpStorm.
 */
$dir = $argv[1];

if ($_SERVER['argc'] < 2 ) {
    exit ('Missing arguments.' . PHP_EOL);
}

if(!is_dir($dir) && !mkdir($dir)) {
    exit('Directory not created' . PHP_EOL);
}


try {
    $db = new PDO('sqlite:asircov.db');
    $stmt = $db->query("SELECT type, COUNT(*) as count FROM messages GROUP BY type");

    if($stmt) {
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }else{
        exit('Unknown error' . PHP_EOL);
    }
}catch(PDOException $exceptionMsg) {
    exit('PDOException : '.$exceptionMsg->getMessage());
}

$dir = realpath($dir) . DIRECTORY_SEPARATOR . 'counts.json';

$res = array_combine(array_column($res, 'type'), array_map('intval', array_column($res, 'count')));



