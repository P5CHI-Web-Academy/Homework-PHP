<?php

namespace Core;

class Request
{
    private $server;

    public function __construct(array $server = [], array $post = [], array $get = [])
    {
        $this->server = $server;
    }

    public function getUrlParts()
    {
        $url = str_replace(__DIR__.'/../', null, $this->server['REQUEST_URI']);
        $url_parts = explode('/', $url);
        $url_parts = array_filter($url_parts);
        $url_parts = array_values($url_parts);

        return $url_parts;
    }

    public function getController(): string
    {
        $url_parts = $this->getUrlParts();
        $controller_name = \count($url_parts) ? explode(
            '?',
            $url_parts[0]
        )[0] : 'home'; // Cut-off url parameters. 'home' is the default page

        return 'Controller\\'.ucfirst($controller_name);
    }
}