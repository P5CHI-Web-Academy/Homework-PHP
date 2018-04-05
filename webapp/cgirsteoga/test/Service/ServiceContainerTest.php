<?php

namespace Webapp\Service;

use PHPUnit\Framework\TestCase;

class ServiceContainerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGet()
    {
        $service = 'service_instance';
        $serviceName = 'test_service';
        $container = new ServiceContainer();
        $container->add($serviceName, $service);
        $resultService = $container->get($serviceName);

        $this->assertTrue($service === $resultService);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function testCannotGetNonExistingService()
    {
        $container = new ServiceContainer();
        $this->expectException(\InvalidArgumentException::class);
        $container->get('non_existing_service');
    }

    public function testAdd()
    {
        $service = 'service_instance';
        $serviceName = 'test_service';
        $container = new ServiceContainer();
        $result = $container->add($serviceName, $service);

        $this->assertTrue($result === $container);
    }
}
