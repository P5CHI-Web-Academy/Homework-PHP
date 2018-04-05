<?php

namespace Webapp\Model;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testCanSetGetUserProperties()
    {
        $id = 1;
        $userName = 'test_userName';
        $fullName = 'test_fullName';
        $password = 'test_pass';

        $user = (new User())
            ->setId($id)
            ->setUserName($userName)
            ->setFullName($fullName)
            ->setPassword($password);

        $this->assertSame($id, $user->getId());
        $this->assertSame($userName, $user->getUserName());
        $this->assertSame($fullName, $user->getFullName());
        $this->assertSame($password, $user->getPassword());
    }

}
