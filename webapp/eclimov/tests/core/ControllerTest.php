<?php

use PHPUnit\Framework\TestCase;

final class ControllerTest extends TestCase{
    public function testControllerCreatedSuccessfully(){
        $mock_service_templater = $this->getMockBuilder(\Service\Templater::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        $mock_model_user = $this->getMockBuilder(\Model\User::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        $this->assertInstanceOf(\Core\Controller::class, new \Core\Controller($mock_model_user, $mock_service_templater));
    }
}