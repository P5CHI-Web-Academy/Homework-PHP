<?php

namespace Webapp;

use Webapp\Client\CurlClient;
use Webapp\Client\GitHub\Client;
use Webapp\Model\PasswordHelper;
use Webapp\Model\UserDAO;
use Webapp\Model\Router;
use Webapp\Model\Session;
use Webapp\Model\Security;
use Webapp\Model\Template;
use Webapp\Model\ServiceContainer;
use Webapp\Controller\AppController;


class WebApp
{
    const DSN = 'sqlite:'.__DIR__.'/../data/web_app.db';

    /** @var ServiceContainer */
    protected $services;

    /**
     * @throws \Exception
     */
    public function run()
    {
        session_start();

        $this->initServices();
        $this->initRoutes();
        /** @var Router $router */
        $router = $this->services->get('router');

        $response = $router->matchRouteFromRequest();

        $response->send();
    }

    /**
     * @throws \Exception
     */
    protected function initServices()
    {
        $this->services = new ServiceContainer();

        $this->services->add('db', new \PDO(self::DSN));
        $this->services->add('userDAO', new UserDAO($this->services->get('db')));
        $this->services->add('template', new Template());
        $this->services->add('router', new Router($this->services));
        $this->services->add('session', new Session());
        $this->services->add('http_client', new CurlClient());
        $this->services->add('github_client', new Client($this->services->get('http_client')));
        $this->services->add('pass_helper', new PasswordHelper());
        $this->services->add(
            'security',
            new Security(
                $this->services->get('session'),
                $this->services->get('userDAO'),
                $this->services->get('pass_helper')
            )
        );
    }

    /**
     * @throws \Exception
     */
    protected function initRoutes()
    {
        /** @var Router $router */
        $router = $this->services->get('router');

        $router
            ->addRoute(
                'app_index',
                [
                    'controller' => AppController::class,
                    'action' => 'index',
                    'url' => '/',
                ]
            )->addRoute(
                'app_login',
                [
                    'controller' => AppController::class,
                    'action' => 'login',
                    'url' => '/login',
                ]
            )->addRoute(
                'app_logout',
                [
                    'controller' => AppController::class,
                    'action' => 'logout',
                    'url' => '/logout',
                ]
            );
    }
}
