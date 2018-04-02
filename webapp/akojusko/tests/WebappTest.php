<?php

namespace Webapp;

use PHPUnit\Framework\TestCase;

class WebappTest extends TestCase
{
    public function testHello()
    {
        $webapp = new Webapp();

        $this->assertTrue($webapp->hello('test'));
    }
}
