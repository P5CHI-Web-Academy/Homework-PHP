<?php

require_once __DIR__.'/../vendor/autoload.php';


ini_set('display_errors', 1);
error_reporting(E_ALL);

define('DB_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../database/db.sqlite');
define('CONFIG_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../config/');


$requestVars = array_merge($_GET, $_POST);

$router = new \App\Router($requestVars);
$router->run();

