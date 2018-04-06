<?php

namespace App\Tests\Controller;

use App\Controller\LoginController;
use App\Services\API\Github;
use App\Services\AuthService;
use App\Services\Session\GlobalSession;
use PHPUnit\Framework\TestCase;


class LoginControllerTest extends TestCase
{
    private $authServiceStub;
    private $gitServiceStub;
    private $sessionServiceStub;


    public function setUp()
    {
        parent::setUp();

        $this->authServiceStub = $this->createMock(AuthService::class);
        $this->gitServiceStub = $this->createMock(Github::class);
        $this->sessionServiceStub = $this->createMock(GlobalSession::class);
    }


    public function testIndexWhenAnonymous()
    {
        $this->authServiceStub->method('isAuthenticated')
            ->willReturn(false);

        $controller = new LoginController($this->authServiceStub, $this->gitServiceStub, $this->sessionServiceStub);

        $this->expectOutputRegex('/Please sign in/');

        $controller->index([]);
    }

    public function testIndexWhenLoggedIn()
    {
        $this->authServiceStub->method('isAuthenticated')
            ->willReturn(true);

        $this->gitServiceStub->method('getProfileLink')
            ->willReturn('aGitLink');

        $this->sessionServiceStub->method('get')
            ->willReturn('test');

        $this->sessionServiceStub->method('set')
            ->willReturn(true);

        $controller = new LoginController($this->authServiceStub, $this->gitServiceStub, $this->sessionServiceStub);

        $this->expectOutputRegex('/Log out/');

        $controller->index([]);
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoginWhenLoggedIn()
    {
        $this->authServiceStub->method('isAuthenticated')
            ->willReturn(true);

        $controller = new LoginController($this->authServiceStub, $this->gitServiceStub, $this->sessionServiceStub);
        $controller->login();

        $this->assertContains('Location: /', xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoginValid()
    {
        $this->authServiceStub->method('isAuthenticated')
            ->willReturn(false);
        $this->authServiceStub->method('authenticate')
            ->willReturn(true);

        $controller = new LoginController($this->authServiceStub, $this->gitServiceStub, $this->sessionServiceStub);
        $controller->login();

        $this->assertContains('Location: /', xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoginInvalid()
    {
        $this->authServiceStub->method('isAuthenticated')
            ->willReturn(false);
        $this->authServiceStub->method('authenticate')
            ->willReturn(false);

        $controller = new LoginController($this->authServiceStub, $this->gitServiceStub, $this->sessionServiceStub);
        $controller->login();

        $this->assertContains('Location: /', xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testLogout()
    {
        $controller = new LoginController($this->authServiceStub, $this->gitServiceStub, $this->sessionServiceStub);
        $controller->login();

        $this->assertContains('Location: /', xdebug_get_headers());
    }
}