<?php

use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase{
    public function testRequestClassInstanceCreatedSucessfully(){
        $this->assertInstanceOf(\Core\Request::class, new \Core\Request());
    }

    /**
     * @param $request_uri
     * @param $expected
     * @dataProvider requestUriAndUrlPartsProvider
     */
    public function testRequestClassInstanceProcessesUrlPartsCorrectly($request_uri, $expected){
        $request = new \Core\Request($server=array('REQUEST_URI'=>$request_uri));
        $this->assertEquals($request->getUrlParts(), $expected);
    }

    /**
     * @param $request_uri
     * @param $expected
     * @dataProvider requestUriAndClassProvider
     */
    public function testRequestReturnsCorrectController($request_uri, $expected){
        $request = new \Core\Request($server=array('REQUEST_URI'=>$request_uri));
        $this->assertEquals($request->getController(), $expected);
    }

    public function requestUriAndUrlPartsProvider(){
        return [
            ['/home',array('home')],
            ['/login', array('login')],
            ['/user/vasea', array('user','vasea')],
            ['/user/vasea/settings', array('user','vasea','settings')]
        ];
    }

    public function requestUriAndClassProvider(){
        return [
            ['',\Controller\Home::class],
            ['/', \Controller\Home::class],
            ['/home', \Controller\Home::class],
            ['/home?', \Controller\Home::class],
            ['/home?param1=a&param2=b', \Controller\Home::class],
        ];
    }
}