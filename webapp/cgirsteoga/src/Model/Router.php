<?php

namespace Webapp\Model;


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
     */
    public function __construct(ServiceContainer $services)
    {
        $this->services = $services;
        $this->protocol = 'http://';
        $this->host = $_SERVER['HTTP_HOST'];
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
     * @return Response
     */
    public function matchRouteFromRequest()
    {
        $path = $_SERVER['PATH_INFO'] ?? '/';

        foreach ($this->routes as $name => $route) {
            if ($route[self::URL] === $path) {
                $controller = new $route[self::CONTROLLER]($this->services);
                $content = call_user_func([$controller, $route[self::ACTION]]);

                return new Response($content);
            }
        }

        return new Response('Not Found', Response::HTTP_NOT_FOUND);
    }

    public function redirect(string $path)
    {
        $redirectPath = sprintf('%s%s%s', $this->protocol, $this->host, $path);

        header('Location: '.$redirectPath);
        exit();
    }
}
