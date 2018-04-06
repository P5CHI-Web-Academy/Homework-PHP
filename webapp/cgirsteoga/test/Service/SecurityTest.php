<?php

namespace Webapp\Service;

use PHPUnit\Framework\TestCase;
use Webapp\Helper\PasswordHelper;
use Webapp\Model\User;

class SecurityTest extends TestCase
{

    /**
     * @throws \ReflectionException
     */
    public function testLogin()
    {
        $sessionStub = $this->createMock(Session::class);
        $userDAOStub = $this->createMock(UserDAO::class);
        $passwordHelperStub = $this->createMock(PasswordHelper::class);
        $userDAOStub
            ->method('getByUserName')
            ->willReturn($this->getTestUser());
        $passwordHelperStub
            ->method('verify')
            ->willReturn(true);

        $security = new Security($sessionStub, $userDAOStub, $passwordHelperStub);

        $result = $security->login('testuser', 'testpassword');

        $this->assertTrue($result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testCannotLoginIncorrectUser()
    {
        $sessionStub = $this->createMock(Session::class);
        $userDAOStub = $this->createMock(UserDAO::class);
        $passwordHelperStub = $this->createMock(PasswordHelper::class);
        $userDAOStub
            ->method('getByUserName')
            ->willReturn(null);
        $passwordHelperStub
            ->method('verify')
            ->willReturn(true);

        $security = new Security($sessionStub, $userDAOStub, $passwordHelperStub);

        $result = $security->login('testuser', 'testpassword');

        $this->assertFalse($result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testCannotLoginIncorrectPassword()
    {
        $sessionStub = $this->createMock(Session::class);
        $userDAOStub = $this->createMock(UserDAO::class);
        $passwordHelperStub = $this->createMock(PasswordHelper::class);
        $userDAOStub
            ->method('getByUserName')
            ->willReturn($this->getTestUser());
        $passwordHelperStub
            ->method('verify')
            ->willReturn(false);

        $security = new Security($sessionStub, $userDAOStub, $passwordHelperStub);

        $result = $security->login('testuser', 'testpassword');

        $this->assertFalse($result);
    }

    /**
     * @throws \ReflectionException
     */
    public function testLogout()
    {
        $sessionStub = $this->createMock(Session::class);
        $userDAOStub = $this->createMock(UserDAO::class);
        $passwordHelperStub = $this->createMock(PasswordHelper::class);
        $sessionStub->expects($this->once())->method('destroy');

        $security = new Security($sessionStub, $userDAOStub, $passwordHelperStub);

        $security->logout();
    }

    /**
     * @throws \ReflectionException
     */
    public function testIsLoggedIn()
    {
        $sessionStub = $this->createMock(Session::class);
        $userDAOStub = $this->createMock(UserDAO::class);
        $passwordHelperStub = $this->createMock(PasswordHelper::class);
        $sessionStub->method('has')
            ->willReturn(true);

        $security = new Security($sessionStub, $userDAOStub, $passwordHelperStub);

        $this->assertTrue($security->isLoggedIn());
    }

    /**
     * @throws \ReflectionException
     */
    public function testCanGetLoggedUserId()
    {
        $testUserId = 213;
        $sessionStub = $this->createMock(Session::class);
        $userDAOStub = $this->createMock(UserDAO::class);
        $passwordHelperStub = $this->createMock(PasswordHelper::class);
        $sessionStub
            ->method('get')
            ->willReturn($testUserId);

        $securityMock = $this
            ->getMockBuilder(Security::class)
            ->setConstructorArgs([$sessionStub, $userDAOStub, $passwordHelperStub])
            ->setMethods(['isLoggedIn'])
            ->getMock();
        $securityMock
            ->expects($this->any())
            ->method('isLoggedIn')
            ->willReturn(true);

        $this->assertTrue($securityMock->getLoggedUserId() === $testUserId);
    }

    /**
     * @throws \ReflectionException
     */
    public function testCannotGetLoggedUserIdWhenIsNotLoggedIn()
    {
        $sessionStub = $this->createMock(Session::class);
        $userDAOStub = $this->createMock(UserDAO::class);
        $passwordHelperStub = $this->createMock(PasswordHelper::class);
        $sessionStub
            ->method('get')
            ->willReturn(null);

        $securityMock = $this
            ->getMockBuilder(Security::class)
            ->setConstructorArgs([$sessionStub, $userDAOStub, $passwordHelperStub])
            ->setMethods(['isLoggedIn'])
            ->getMock();
        $securityMock
            ->expects($this->any())
            ->method('isLoggedIn')
            ->willReturn(false);

        $this->assertNull($securityMock->getLoggedUserId());
    }

    /**
     * @throws \ReflectionException
     */
    public function testCannotGetLoggedUserWhenIsNotLoggedIn()
    {
        $sessionStub = $this->createMock(Session::class);
        $userDAOStub = $this->createMock(UserDAO::class);
        $passwordHelperStub = $this->createMock(PasswordHelper::class);
        $userDAOStub
            ->method('getById')
            ->willReturn(null);

        $securityMock = $this
            ->getMockBuilder(Security::class)
            ->setConstructorArgs([$sessionStub, $userDAOStub, $passwordHelperStub])
            ->setMethods(['getLoggedUserId'])
            ->getMock();
        $securityMock
            ->expects($this->any())
            ->method('getLoggedUserId')
            ->willReturn(null);

        $this->assertNull($securityMock->getLoggedUser());
    }

    /**
     * @throws \ReflectionException
     */
    public function testCanGetLoggedUser()
    {
        $sessionStub = $this->createMock(Session::class);
        $userDAOStub = $this->createMock(UserDAO::class);
        $passwordHelperStub = $this->createMock(PasswordHelper::class);
        $userDAOStub->method('getById')->willReturn($this->getTestUser());

        $securityMock = $this
            ->getMockBuilder(Security::class)
            ->setConstructorArgs([$sessionStub, $userDAOStub, $passwordHelperStub])
            ->setMethods(['getLoggedUserId'])
            ->getMock();
        $securityMock
            ->expects($this->any())
            ->method('getLoggedUserId')
            ->willReturn(true);


        $user = $securityMock->getLoggedUser();

        $this->assertTrue($user instanceof User);
    }

    protected function getTestUser()
    {
        return (new User())
            ->setPassword('test')
            ->setUserName('test');
    }
}
