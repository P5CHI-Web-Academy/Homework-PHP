<?php

use App\Controllers\LoginController;

/**
 * Class Router
 */
class Router
{
    public function run()
    {
        $request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);

        switch ($request_uri[0]) {
            case '/':
                if (empty($_SESSION['auth'])) {
                    require dirname(__DIR__).'/Views/beforeLogin/index.php';
                } else {
                    $content = file_get_contents(dirname(__DIR__).'/Views/afterLogin/index.php');
                    $content = preg_replace('/{login}/', $_SESSION['name'], $content);
                    echo $content;
                }
                break;
            case '/login':
                require dirname(__DIR__).'/Controllers/LoginController.php';
                $newLogin = new LoginController();
                $newLogin->postLogin();
                break;
            case '/logout':
                $logout = new LoginController();
                $logout->logout();
                break;
        }
    }
}