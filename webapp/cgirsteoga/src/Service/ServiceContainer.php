<?php

namespace Webapp\Service;


class ServiceContainer
{
    /** @var array */
    protected $services;

    public function __construct()
    {
        $this->services = [];
    }

    /**
     * @param $name
     * @param mixed $service
     * @return $this
     */
    public function add(string $name, $service)
    {
        $this->services[$name] = $service;

        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get(string $name)
    {
        if (!isset($this->services[$name])) {
            throw new \InvalidArgumentException(\sprintf('Service %s not defined', $name));
        }

        return $this->services[$name];
    }
}
