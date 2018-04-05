<?php

namespace Webapp\Service;

use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{

    public function setUp()
    {
        $_SESSION = [];
    }

    public function tearDown()
    {
        $_SESSION = [];
    }

    public function testSetValue()
    {
        $session = new Session();
        $key = 'test';
        $session->set($key, 'value');

        $this->assertTrue(isset($_SESSION[$key]));
    }

    public function testGetValue()
    {
        $value = 'testValue';
        $key = 'test';
        $_SESSION[$key] = $value;

        $session = new Session();

        $this->assertTrue($session->get($key) === $value);
    }

    public function testRemoveValue()
    {
        $value = 'testValue';
        $key = 'test';
        $_SESSION[$key] = $value;

        $session = new Session();
        $session->remove($key);

        $this->assertFalse(isset($_SESSION[$key]));
    }

    public function testCannotGetNonExistentValue()
    {
        $key = 'test';

        $session = new Session();

        $this->assertNull($session->get($key));
    }

}
