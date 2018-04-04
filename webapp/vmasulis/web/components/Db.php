<?php

class Db
{
	public static function getConnection()
	{
		if (!is_dir(ROOT.'/data')) {mkdir(ROOT.'/data', 0777, true);}
		$paramPath = ROOT.'/config/db_params.php';
		$params = include($paramPath);

		$dsn = "sqlite:{$params['dbpath']}/{$params['dbname']}";

		$db = new \PDO($dsn);

		$db->exec("CREATE TABLE IF NOT EXISTS users (
							id INTEGER PRIMARY KEY, 
                            username TEXT, 
                            password TEXT,
                            email TEXT)");
		$st = $db->prepare('INSERT INTO users (id, username, password, email) VALUES (:id, :username, :password, :email)');

		$st->execute([':id'=>1,':username'=>'tor', ':password'=>'123456', ':email'=>'example@ex.com']);
		$st->execute([':id'=>2,':username'=>'pol', ':password'=>'123456', ':email'=>'example@ex.com']);
		$st->execute([':id'=>3,':username'=>'mira', ':password'=>'123456', ':email'=>'example@ex.com']);

		return $db;
	}
}