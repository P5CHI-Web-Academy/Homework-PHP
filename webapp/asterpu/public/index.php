<?php
/**
 * Copyright (c) 2018.
 *
 *  @author		Alexander Sterpu <alexander.sterpu@gmail.com>
 *
 */

use FastRoute\RouteCollector;

$container = require __DIR__ . '/../app/bootstrap.php';

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', ['WebApp\Controller\HomeController', 'index']);
    $r->addRoute(['GET', 'POST'], '/login', ['WebApp\Controller\AuthController', 'login']);

    $r->addRoute('GET', '/{path:[\w/?=&\-]+}',  ['WebApp\Controller\HomeController', 'show404']);
});

$route = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed");
        break;

    case FastRoute\Dispatcher::FOUND:
        $controller = $route[1];
        $parameters = $route[2];

        $container->call($controller, $parameters);
        break;
}
