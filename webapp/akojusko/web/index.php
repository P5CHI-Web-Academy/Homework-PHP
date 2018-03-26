<?php

require __DIR__.'/../vendor/autoload.php';

$webapp = new \Webapp\Webapp();
$webapp->hello($_GET['name'] ?? 'all');