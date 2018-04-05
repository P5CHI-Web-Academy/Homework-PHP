<?php

namespace Webapp\Service;

use PHPUnit\Framework\TestCase;
use Webapp\Model\Response;
use Webapp\Test\Service\Mock\TestController;

class RouterTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testMatchRouteFromRequest()
    {
        $router = $this->createRouter();
        $route = $this->getTestRoute();

        $router->addRoute(
            'test_route',
            $route
        );

        $response = $router->matchRouteFromRequest($route[Router::URL]);

        $this->assertTrue($response instanceof Response);
        $this->assertTrue($response->getContent() === TestController::TEST_CONTENT);
    }

    /**
     * @throws \ReflectionException
     */
    public function testNotFoundResponseForNonExistingRoute()
    {
        $router = $this->createRouter();
        $route = $this->getTestRoute();

        $router->addRoute(
            'test_route',
            $route
        );

        $response = $router->matchRouteFromRequest('/path-to-test');

        $this->assertTrue($response instanceof Response);
        $this->assertTrue($response->getCode() === Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws \ReflectionException
     */
    public function testAddRoute()
    {
        $router = $this->createRouter();

        $result = $router->addRoute(
            'test_route',
            $this->getTestRoute()
        );

        $this->assertTrue($router === $result);
    }

    /**
     * @return Router
     * @throws \ReflectionException
     */
    protected function createRouter()
    {
        $mock = $this->createMock(ServiceContainer::class);
        $router = new Router($mock, 'test.host');

        return $router;
    }

    /**
     * @return array
     */
    protected function getTestRoute()
    {
        return [
            Router::CONTROLLER => TestController::class,
            Router::ACTION => 'testAction',
            Router::URL => '/test-path',
        ];
    }
}
