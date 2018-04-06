<?php

namespace App\tests\Services;

use App\Model\UserRepository;
use App\Services\AuthService;
use App\Services\Session\GlobalSession;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private $sessionServiceStub;
    private $repositoryStub;


    public function setUp()
    {
        parent::setUp();

        $this->sessionServiceStub = $this->createMock(GlobalSession::class);
        $this->repositoryStub = $this->createMock(UserRepository::class);
    }

    public function testIsAuthenticatedTrue()
    {
        $this->sessionServiceStub->method('get')
            ->willReturn(true);

        $authService = new AuthService($this->sessionServiceStub, $this->repositoryStub);

        $this->assertTrue($authService->isAuthenticated());
    }

    public function testIsAuthenticatedFalse()
    {
        $this->sessionServiceStub->method('get')
            ->willReturn(null);

        $authService = new AuthService($this->sessionServiceStub, $this->repositoryStub);

        $this->assertFalse($authService->isAuthenticated());
    }

    public function testAuthenticateEmptyInput()
    {
        $authService = new AuthService($this->sessionServiceStub, $this->repositoryStub);

        $this->assertFalse($authService->authenticate());
    }

    public function testAuthenticateInvalidInput()
    {
        $this->repositoryStub->method('get')
            ->willReturn(null);

        $authService = new AuthService($this->sessionServiceStub, $this->repositoryStub);

        $this->assertFalse($authService->authenticate(['username' => '!ssd!', 'password' => '???']));
    }

    public function testAuthenticateValidInput()
    {
        $this->sessionServiceStub->method('set')
            ->willReturn(true);
        $this->repositoryStub->method('get')
            ->willReturn(['username' => 'test_user']);


        $authService = new AuthService($this->sessionServiceStub, $this->repositoryStub);

        $this->assertTrue($authService->authenticate(['username' => 'test_user', 'password' => 'test_password']));
    }

    public function testLogout()
    {
        $this->sessionServiceStub->method('get')
            ->willReturn('username');
        $this->sessionServiceStub->method('set')
            ->willReturn(true);

        $authService = new AuthService($this->sessionServiceStub, $this->repositoryStub);

        $this->assertTrue($authService->logout());
    }

    public function testGetUser()
    {
        $authService = new AuthService($this->sessionServiceStub, $this->repositoryStub);

        $this->assertTrue(is_array($authService->user()));
    }
}