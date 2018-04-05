<?php

require __DIR__.'/../vendor/autoload.php';

try {
    $webapp = new \Webapp\WebApp();
    $webapp->run();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}
