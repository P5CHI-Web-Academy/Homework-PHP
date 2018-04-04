<?php

namespace Webapp\Client\GitHub;

use PHPUnit\Framework\TestCase;

class UserRequestTest extends TestCase
{
    public function testCanGetData()
    {
        $response = new UserRequest('test');
        $this->assertSame([], $response->getData());
    }

}
