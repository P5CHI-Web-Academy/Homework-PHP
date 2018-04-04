<?php

namespace Webapp\Helper;

use PHPUnit\Framework\TestCase;

class PasswordHelperTest extends TestCase
{

    public function testCanHash()
    {
        $helper = new PasswordHelper();
        $plain = 'testPass';

        $hash = $helper->hash($plain);

        $this->assertNotEmpty($hash);
    }

    public function testVerifySuccess()
    {
        $helper = new PasswordHelper();
        $plain = 'testPass';

        $hash = $helper->hash($plain);

        $this->assertTrue($helper->verify($plain, $hash));
    }

    public function testVerifyFail()
    {
        $helper = new PasswordHelper();
        $plain = 'testPass';

        $hash = $helper->hash($plain);
        $plain = 'changedPass';
        $this->assertFalse($helper->verify($plain, $hash));
    }

}
