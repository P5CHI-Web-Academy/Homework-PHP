<?php

require_once __DIR__.'/../vendor/autoload.php';

use App\service\ServiceForAuthorization;

define('PATHDB', $_SERVER['DOCUMENT_ROOT'].'/../Database/mydatabase.sqlite');

$req = array_merge($_POST, $_GET);

$maping = [
    '/' => 'LoginControll@index',
    '/login' => 'LoginControll@login',
    '/logout' => 'LoginControll@logout',
];

$path_req = explode('?', $_SERVER['REQUEST_URI'])[0];

if (isset($maping[$path_req])) {
    session_start();
    ob_start();

    $res = explode('@', $maping[$path_req]);
    $controll_path = 'App\controller\\' . $res[0];
    $controll_method = $res[1];

    $controller = new $controll_path(new ServiceForAuthorization());
    $controller->$controll_method($req);

    echo ob_get_clean();

    if (!isset($_SESSION['client_name'])) {
        session_destroy();
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Oops! The is not found !!</h1>";
    die();
}
