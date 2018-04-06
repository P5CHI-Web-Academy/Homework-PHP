<?php

use App\Components\Container;

/**
 * Class Router
 */
class Router
{
    protected $routes = [
        '/' => 'LoginController/index',
        '/login' => 'LoginController/postLogin',
        '/logout' => 'LoginController/logout',
    ];

    public function run()
    {
        $containerData = [];

        $request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);

        if (isset($this->routes[$request_uri[0]])) {
            $containerData = explode('/', $this->routes[$request_uri[0]]);
        }

        $class = 'App\Controllers\\' . $containerData[0];
        $method = $containerData[1];

        try {
            $controller = Container::instance()->get($class);
        } catch (\Exception $e) {
            die('Error');
        }

        $controller->$method();
    }
}
