<?php

require __DIR__.'/../vendor/autoload.php';

use \Core\Request;

define('DB_DSN', 'sqlite:'.$_SERVER['DOCUMENT_ROOT'].'/../src/'.'database.db');
define('TEMPLATES_PATH', $_SERVER['DOCUMENT_ROOT'].'/../src/views');

if (!isset($_SESSION)) {
    session_start();
}

$container = new \Service\DIContainer();

$request = new Request($_SERVER, $_POST, $_GET);
$controller = $container->get($request->getController());