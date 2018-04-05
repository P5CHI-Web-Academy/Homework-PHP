<?php

use PHPUnit\Framework\TestCase;

final class DIContainerTest extends TestCase{
    public function testContainerReflectionException(){
        $container = new \Service\DIContainer();
        $this->expectException(ReflectionException::class);
        $container->get('');  // Here can be anything, - an exception will be thrown anyway
    }

    public function testContainerReturnsValidObject(){
        $mock = $this->getMockBuilder(\Service\DIContainer::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        $this->assertInstanceOf(stdClass::class, $mock->get('stdClass'));
    }

    public function testContainerCanWorkWithClassConstructors(){
        $container = new \Service\DIContainer();
        $this->assertInstanceOf(\Webapp\Webapp::class, $container->get('\Webapp\Webapp'));
    }
}