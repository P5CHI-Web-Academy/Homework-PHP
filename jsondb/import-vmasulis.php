<?php
if ($_SERVER['argc'] !== 2) {
    exit('Only one argument is accepted.');
}
$GLOBALS['dir_files'] = [];

if (is_dir($_SERVER['argv'][1])) {
	if ($dir_h = opendir($_SERVER['argv'][1])) {
		while (($file = readdir($dir_h)) !== false) {
			if (pathinfo($file, PATHINFO_EXTENSION) === 'json'){
				$dir_files[] = $_SERVER['argv'][1].DIRECTORY_SEPARATOR.$file;
			}
		}
		closedir($dir_h);
	}
} else {
	exit('You path is incorrect.');
}
try {
	$db = new \PDO('sqlite:mess.db');
	if (!$db) exit("Could not create database!");
	$db->exec("CREATE TABLE IF NOT EXISTS info (
							id INTEGER PRIMARY KEY, 
                            date TEXT, 
                            type TEXT,
                            owner TEXT,
                            message TEXT)");
	$s = $db->prepare('INSERT INTO info (date, type, owner, message) VALUES (:date, :type, :owner, :message)');
	foreach ($dir_files as $file_) {
		$f_data = json_decode(file_get_contents($file_), true);
		foreach ($f_data as $row) {
			if ((DateTime::createFromFormat('Y-m-d', $row['date']) !== FALSE)
				&& ctype_upper($row['type']) && (ctype_lower($row['owner']) && ctype_alnum($row['owner']))) {
				$s->execute([':date'=>$row['date'], ':type'=>$row['type'], ':owner'=>$row['owner'], ':message'=>$row['message']]);
			}
		}
		unlink($file_);
	}
} catch (PDOException $e) {
	exit('PDOException are detected: '.$e->getMessage());
}
