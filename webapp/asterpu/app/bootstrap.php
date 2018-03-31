<?php
/**
 * Copyright (c) 2018.
 *
 *  @author		Alexander Sterpu <alexander.sterpu@gmail.com>
 *
 */

/**
 * The bootstrap file returns the container and calls UserSeeder.
 */

use DI\ContainerBuilder;
use WebApp\UserSeeder;

require __DIR__ . '/../vendor/autoload.php';



$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/config.php');
$container = $containerBuilder->build();

$container->call([UserSeeder::class, "installIfNeeded"], []);

return $container;
