<?php

namespace Service;

use ReflectionException;

/**
 * Dependency Injection Container
 * @package Service
 */
class DIContainer
{
    private $container = [];

    private function add(string $service)
    {
        try {
            $class = new \ReflectionClass($service);
        } catch (ReflectionException $e) {
            //echo 'Reflection Exception in DIContainer class';
            throw $e;
        }

        $constructor = $class->getConstructor();
        if ($constructor !== null) {
            $arguments = [];
            foreach ($constructor->getParameters() as $parameter) {
                $paramType = $parameter->getClass();
                $arguments[] = $this->get($paramType->getName());
            }
            $instance = new $service(...$arguments);
        } else {
            $instance = new $service;
        }

        $this->container[$service] = $instance;

        return $instance;
    }

    public function get(string $service)
    {
        if (!array_key_exists($service, $this->container)) {
            $this->add($service);
        }

        return $this->container[$service];
    }
}