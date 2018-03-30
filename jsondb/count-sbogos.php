<?php

if ($_SERVER['argc'] != 2) {
  print "Invalid arguments"; exit();
}

$owner = $_SERVER['argv'][1];

try {

  $conn = new \PDO("sqlide:sbogos.db");
} catch (\PDOException $e) {
  
  print $e->getMessage();
}

$stmt = $conn->prepare("SELECT type, COUNT(type) AS records FROM logs WHERE owner = :owner GROUP BY type");
$stmt->execute(['owner' => $owner]);
$results =  $stmt->fetchAll(PDO::FETCH_OBJ);

$json = [];

foreach ($results as $row) {
	$json[strtolower($row->type)] = $row->records;
}

if (!is_dir('report' . DIRECTORY_SEPARATOR . $owner)) {
	mkdir('report' . DIRECTORY_SEPARATOR . $owner, 0777, true);
}

file_put_contents('report' . DIRECTORY_SEPARATOR . $owner . DIRECTORY_SEPARATOR . 'count.json', json_encode($json));
