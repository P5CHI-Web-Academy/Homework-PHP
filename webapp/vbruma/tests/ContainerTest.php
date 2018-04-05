<?php

namespace App\tests;

use App\Container;
use App\Services\API\Github;
use App\Services\API\GitInterface;
use App\Services\CurlClient;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{

    public function testGetInstance()
    {
        $this->assertTrue(Container::instance() instanceof Container);
    }

    public function testSetConcrete()
    {
        $container = Container::instance();
        $container->set(Github::class);

        $this->assertTrue($container->get(Github::class) instanceof Github);
    }

    public function testSetAbstract()
    {
        $container = Container::instance();
        $container->set(GitInterface::class ,Github::class);

        $this->assertTrue($container->get(GitInterface::class) instanceof Github);
    }

    public function testResolveConcrete()
    {
        $container = Container::instance();

        $this->assertTrue($container->resolve(Github::class) instanceof Github);
    }

    public function testResolveAbstract()
    {
        $container = Container::instance();

        $this->expectException(\Exception::class);

        $container->resolve(GitInterface::class);
    }

    public function testResolvableDependencies()
    {
        $reflector = new \ReflectionClass(Github::class);
        $constructor = $reflector->getConstructor();
        $parameters  = $constructor->getParameters();
        $classes = Container::instance()->getDependencies($parameters);

        $this->assertTrue($classes[0] instanceof CurlClient);
    }

    public function testUnresolvableDependencies()
    {
        $reflector = new \ReflectionClass(new class (0){
            public function __construct($unresolvableVariable) {}
        });
        $constructor = $reflector->getConstructor();
        $parameters  = $constructor->getParameters();

        $this->expectException(\Exception::class);

        Container::instance()->getDependencies($parameters);
    }
}