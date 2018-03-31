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
                require dirname(__DIR__).'/Views/beforeLogin/index.php';
                break;
            case '/login':
                require dirname(__DIR__).'/Controllers/LoginController.php';
                $newLogin = new LoginController();
                $newLogin->checkLoginData();
                break;
        }
    }
}