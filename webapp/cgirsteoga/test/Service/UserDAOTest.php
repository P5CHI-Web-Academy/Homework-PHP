<?php

namespace Webapp\Service;

use PHPUnit\Framework\TestCase;
use Webapp\Installer;
use Webapp\Model\User;

class UserDAOTest extends TestCase
{

    public function testCannotGetUserByIdOrUserName()
    {
        $userDAOMock = $this
            ->getMockBuilder(UserDAO::class)
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();
        $userDAOMock
            ->expects($this->any())->method('find')
            ->willReturn(null);

        $this->assertNull($userDAOMock->getById(11));
        $this->assertNull($userDAOMock->getByUserName('test'));
    }

    public function testCanFindUserByUserName()
    {
        $db = new \PDO('sqlite::memory:');
        Installer::createStructure($db);
        Installer::importDemoData($db);

        $userDAO = new UserDAO($db);
        $user = $userDAO->find([UserDAO::USER_NAME => 'user1']);

        $this->assertTrue($user instanceof User);
    }

    public function testCannotFindUserByNonExistingUserName()
    {
        $db = new \PDO('sqlite::memory:');
        Installer::createStructure($db);
        Installer::importDemoData($db);

        $userDAO = new UserDAO($db);
        $user = $userDAO->find([UserDAO::USER_NAME => 'user_not_exists']);

        $this->assertNull($user);
    }

}
