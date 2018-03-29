<?php

require_once __DIR__.'/../vendor/autoload.php';


define('DB_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../database/vbruma.db');
define('CONFIG_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../config/');

$requestVars = array_merge($_GET, $_POST);

$kernel = new \App\Kernel($requestVars);
$kernel->handle();

