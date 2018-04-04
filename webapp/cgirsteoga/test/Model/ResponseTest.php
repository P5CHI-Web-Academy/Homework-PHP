<?php

namespace Webapp\Model;

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testCanSendResponse()
    {
        $testContent = 'test content';

        $responseMock = $this
            ->getMockBuilder(Response::class)
            ->setConstructorArgs([$testContent])
            ->setMethods(['sendHeaders'])
            ->getMock();
        $responseMock
            ->expects($this->any())
            ->method('sendHeaders')
            ->willReturn(null);

        $this->expectOutputString($testContent);
        $responseMock->send();
    }

}
