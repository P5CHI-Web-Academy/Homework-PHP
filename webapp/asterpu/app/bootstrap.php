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
use Aura\Session\Session;
use Aura\Session\SessionFactory;
use WebApp\Config;
use WebApp\GitHubUserChecker;
use WebApp\Model\UserRepository;
use WebApp\Persistence\PDOUserRepository;
use WebApp\UserSeeder;

require __DIR__ . '/../vendor/autoload.php';


$containerBuilder = new ContainerBuilder;

$containerBuilder->enableCompilation(__DIR__ . '/../data');
$containerBuilder->writeProxiesToFile(true, __DIR__ . '/../data');
$containerBuilder->useAnnotations(false);

$containerBuilder->addDefinitions([
	Config::class => DI\autowire(),

	'DSN' => function(Config $config){ return $config->DSN; },
	'TEMPLATES_FOLDER' => function(Config $config){ return $config->TEMPLATES_FOLDER; },

    \PDO::class => DI\autowire()->constructor(DI\get('DSN'), null, null, null),

    UserRepository::class => DI\autowire(PDOUserRepository::class),   	

   	Session::class => function(){
    	$session_factory = new SessionFactory;
    	return $session_factory->newInstance($_COOKIE);
    },

    Twig_Environment::class => \DI\autowire()->constructor(
    	DI\create(Twig_Loader_Filesystem::class)->constructor(DI\get('TEMPLATES_FOLDER'))
    ),
]);

$container = $containerBuilder->build();

$container->call([UserSeeder::class, "seedIfNeed"], []);

return $container;
