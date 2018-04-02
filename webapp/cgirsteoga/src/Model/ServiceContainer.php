<?php

namespace Webapp\Model;


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
     * @throws \Exception
     */
    public function get(string $name)
    {
        if (!isset($this->services[$name])) {
            throw new \Exception(sprintf('Service %s not defined', $name));
        }

        return $this->services[$name];
    }
}
