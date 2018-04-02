<?php

class User
{
	
	public static function userExist()
	{
			if (!empty($_POST["username"]) && !empty($_POST["password"]))
			{
				$db = Db::getConnection();
				$st = $db->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
				$str = $st->execute([':username'=>$_POST["username"], ':password'=> $_POST["password"]]);
				$usr = $str->fetch(PDO::FETCH_ASSOC);
				if ($st != null) {
					$_SESSION["id"] = $usr['id'];
				}
			}
	}

	public static function isLoged($id)
	{

	}

	public static function getUserInf($id)
	{

	}

}