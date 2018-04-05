<?php

namespace App;

class Container
{

    protected $instances = [];

    private static $selfInstance = null;

    private function __clone(){}

    private function __construct(){}

    static function instance(): Container
    {
        if (!isset(static::$selfInstance)) {
            static::$selfInstance = new static;
        }
        return static::$selfInstance;
    }

    function set($abstract, $concrete = null)
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        $this->instances[$abstract] = $concrete;
    }

    function get($abstract)
    {
        if ( ! isset($this->instances[$abstract])) {
            $this->set($abstract);
        }

        return $this->resolve($this->instances[$abstract]);
    }

    function resolve($concrete)
    {
        $reflector = new \ReflectionClass($concrete);

        if ( ! $reflector->isInstantiable()) {
            throw new \Exception("Class {$concrete} is not instantiable");
        }
        $constructor = $reflector->getConstructor();
        if (null === $constructor) {
            return $reflector->newInstance();
        }

        $parameters = $constructor->getParameters();

        $dependencies = $this->getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }


    function getDependencies($parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if ($dependency === null) {

                if ($parameter->isDefaultValueAvailable()) {

                    $dependencies[] = $parameter->getDefaultValue();

                } else {
                    throw new \Exception(
                        "Can not resolve class dependency {$parameter->name}"
                    );
                }
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }
        return $dependencies;
    }
}
