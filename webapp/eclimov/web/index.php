<?php

require __DIR__.'/../vendor/autoload.php';

use \Core\Request;

if (!isset($_SESSION)) {
    session_start();
}

$request = new Request($_SERVER, $_POST, $_GET);
$controller = $request->getController();
$controller = new $controller;