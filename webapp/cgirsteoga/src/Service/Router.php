<?php

namespace Webapp\Service;

use Webapp\Model\Response;

class Router
{
    const URL = 'url';
    const CONTROLLER = 'controller';
    const ACTION = 'action';

    /** @var array */
    protected $routes;
    /** @var string */
    protected $protocol;
    /** @var string */
    protected $host;
    /** @var ServiceContainer */
    protected $services;

    /**
     * Router constructor.
     * @param ServiceContainer $services
     * @param string $host
     * @param string $protocol
     */
    public function __construct(
        ServiceContainer $services,
        string $host,
        string $protocol = 'http://'
    ) {
        $this->services = $services;
        $this->protocol = $protocol;
        $this->host = $host;
    }

    /**
     * @param string $name
     * @param array $route
     * @return $this
     */
    public function addRoute(string $name, array $route)
    {
        $this->routes[$name] = $route;

        return $this;
    }

    /**
     * @param string $path
     * @return Response
     */
    public function matchRouteFromRequest(string $path)
    {
        foreach ($this->routes as $name => $route) {
            if ($route[self::URL] === $path) {
                $controller = new $route[self::CONTROLLER]($this->services);
                $content = \call_user_func([$controller, $route[self::ACTION]]);

                return new Response($content);
            }
        }

        return new Response('Not Found', Response::HTTP_NOT_FOUND);
    }

    public function redirect(string $path)
    {
        $redirectPath = \sprintf('%s%s%s', $this->protocol, $this->host, $path);

        \header('Location: '.$redirectPath);
        exit();
    }
}
