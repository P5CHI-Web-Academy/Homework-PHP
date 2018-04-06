<?php

namespace App\Components;

class Container
{
    /**
     * @var array Array of class instances
     */
    protected $instances = [];
    private static $selfInstance = null;

    /**
     * Singleton
     */
    private function __construct()
    {
    }

    /**
     * Singleton
     */
    private function __clone()
    {
    }

    /**
     * @return Container
     */
    public static function instance()
    {
        if (!isset(static::$selfInstance)) {
            static::$selfInstance = new static;
        }

        return static::$selfInstance;
    }

    /**
     * Set an instance to container
     *
     * @param $abstract
     * @param null $concrete
     */
    public function set($abstract, $concrete = null)
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        $this->instances[$abstract] = $concrete;
    }

    /**
     * Get instance from container
     *
     * @param $abstract
     *
     * @return object
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function get($abstract)
    {
        if (!isset($this->instances[$abstract])) {
            $this->set($abstract);
        }

        return $this->resolve($this->instances[$abstract]);
    }

    /**
     * Create an instance of given class
     *
     * @param $concrete
     *
     * @return object
     * @throws \Exception
     * @throws \ReflectionException
     */
    public function resolve($concrete)
    {
        $reflector = new \ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$concrete} is not instantiable");
        }
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Resolve class dependencies
     *
     * @param $parameters
     *
     * @return array
     * @throws \Exception
     */
    public function getDependencies($parameters)
    {
        $dependencies = [];
        /** @var \ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if ($dependency === null) {

                if ($parameter->isDefaultValueAvailable()) {

                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Can not resolve class dependency {$parameter->name}");
                }
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }

        return $dependencies;
    }
}