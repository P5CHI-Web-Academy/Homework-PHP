<<<<<<< HEAD
<?php
if ($_SERVER['argc'] !== 2) {
    exit('Only one argument is accepted.');
}
$GLOBALS['owner'] = $_SERVER['argv'][1];
$GLOBALS['path'] = "report".DIRECTORY_SEPARATOR.$owner;

if (!is_dir($path)) {
	mkdir($path, 0777, true);
}

$owner_data = [];
try {
	$db = new \PDO('sqlite:mess.db');
	if (!$db) exit("Could not open database!");
	$st = $db->prepare('SELECT COUNT(id) AS `count`, type FROM info WHERE owner = :owner GROUP BY type');
	$st->execute([':owner' => $owner]);
	$res = $st->fetchAll();
	if (count($res) <= 0) {
		exit('No information.');
	}
	foreach ($res as $row) {
		$row['type'] = strtolower($row['type']);
		$owner_data[$row['type']] = $row['count'];
	}
} catch (PDOException $e) {
	exit('PDOException are detected: '.$e->getMessage());
}
=======
<?php
if ($_SERVER['argc'] !== 2) {
    exit('Only one argument is accepted.');
}

$GLOBALS['owner'] = $_SERVER['argv'][1];
$GLOBALS['path'] = "report".DIRECTORY_SEPARATOR.$owner;

if (!is_dir($path)) {
	mkdir($path, 0777, true);
}

$owner_data = [];

try {
	$db = new \PDO('sqlite:mess.db');
	if (!$db) exit("Could not open database!");
	$st = $db->prepare('SELECT COUNT(id) AS `count`, type FROM info WHERE owner = :owner GROUP BY type');
	$st->execute([':owner' => $owner]);
	$res = $st->fetchAll();
	if (count($res) <= 0) {
		exit('No information.');
	}
	foreach ($res as $row) {
		$row['type'] = strtolower($row['type']);
		$owner_data[$row['type']] = $row['count'];
	}
} catch (PDOException $e) {
	exit('PDOException are detected: '.$e->getMessage());
}
>>>>>>> a2866bce6b8463a53247745d2b7e1c7b62214b05
file_put_contents($path.DIRECTORY_SEPARATOR.'count.json', json_encode($owner_data));