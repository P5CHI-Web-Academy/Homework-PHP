<?php

use PHPUnit\Framework\TestCase;

final class WebappTest extends TestCase{
    /**
     * @dataProvider sampleTestProvider
     */
    public function testSampleReturnsValidNumber($number, $expected){
        $mock = $this->getMockBuilder(\Webapp\Webapp::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        $this->assertSame($expected, $mock->sampleForTest($number));
    }

    public function sampleTestProvider(){
        return [
            [0,0],
            [1,0.5],
            [2,2],
            [3,4.5],
            [4,8]
        ];
    }
}