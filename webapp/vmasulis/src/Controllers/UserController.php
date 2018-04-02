<?php

include_once ROOT.'/../src/models/User.php';

class UserController
{
	
	public function actionIndex()
	{
		$content = file_get_contents(ROOT.'/../src/views/user/index.html');
		$result = '';
		if (isset($_SESSION["id"])) {
			$db = Db::getConnection();

			$st = $db->prepare("SELECT * FROM users WHERE id = :id");
			$st->execute([':id'=>$_SESSION["id"]]);
			$usr = $st->fetch(PDO::FETCH_ASSOC);
			$result = str_replace('{{user}}', $usr['username'], $content);
			$result = str_replace("{{loginx}}", "logout", $result);
			$result = str_replace("{{Login}}", "Logout", $result);
		}else{
			$result = str_replace("{{user}}", "guest", $content);
			$result = str_replace("{{loginx}}", "login", $result);
			$result = str_replace("{{Login}}", "Login", $result);
		}

		echo $result;
	}

	public function actionLogin()
	{
		$content = file_get_contents(ROOT.'/../src/views/user/login.html');
		$result = '';
		if (isset($_SESSION["id"])) {
			self::actionIndex();
		}else{
			if (empty($_POST["username"]) && empty($_POST["password"]))
			{
				$result = $content;
			}else {
				$db = Db::getConnection();
				$username = $_POST["username"];
				$password = $_POST["password"];

				$st = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
				$st->execute(array($username, $password));
				$usr = $st->fetch(PDO::FETCH_ASSOC);
				if ($usr != null) {
					$_SESSION["id"] = $usr['id'];
					self::actionIndex();
				}else {
					self::actionLogin();
				}
			}

		}
		echo $result;
	}

	public function actionLogout()
	{
		unset($_SESSION["id"]);
		session_unset();
		session_destroy();
		self::actionIndex();
	}

	public function actionProfile($id)
	{
		$db = Db::getConnection();

		$res = $db->query("SELECT * FROM users WHERE id = $id");
		$user = $res->fetch(PDO::FETCH_ASSOC);
		
		$content = file_get_contents(ROOT.'/../src/views/user/profile.html');
		$result = '';

		$result = str_replace("{{username}}", $user['username'], $content);
		$result = str_replace("{{email}}", $user['email'], $result);
		ob_clean();
		echo $result;
	}
}