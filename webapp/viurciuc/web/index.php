<?php

require_once __DIR__.'/../vendor/autoload.php';

use App\Services\AuthService;

define('DB_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../database/db.sqlite');

$requestVars = array_merge($_GET, $_POST);

$map = array(
    '/' => 'LoginController@index',
    '/login' => 'LoginController@login',
    '/logout' => 'LoginController@logout'
);

$requestPath = explode('?', $_SERVER['REQUEST_URI'])[0];

if (isset($map[$requestPath])) {
    session_start();
    ob_start();

    $resource = explode('@', $map[$requestPath]);
    $controllerPath = 'App\Controller\\' . $resource[0];
    $controllerMethod = $resource[1];

    $controller = new $controllerPath(new AuthService());
    $controller->$controllerMethod($requestVars);

    echo ob_get_clean();

    if (!isset($_SESSION['username'])) {
        session_destroy();
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Page not found</h1>";
    die();
}
