<?php
/**
 * Copyright (c) 2018.
 *
 *  @author		Alexander Sterpu <alexander.sterpu@gmail.com>
 *
 */

use Aura\Session\Session;
use Aura\Session\SessionFactory;
use WebApp\Model\UserRepository;
use WebApp\Persistence\PDOUserRepository;
use function DI\create;
use function DI\get;

define('DSN', 'sqlite:'.__DIR__.'/../data/app.db');

return [
    \PDO::class => function(){ return new PDO(DSN); },

   	UserRepository::class => create(PDOUserRepository::class)->constructor(get(\PDO::class)),

   	Session::class => function(){
    	$session_factory = new SessionFactory;
    	return $session_factory->newInstance($_COOKIE);
    },

    Twig_Environment::class => function () {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../src/Views');
        return new Twig_Environment($loader);
    },
];
