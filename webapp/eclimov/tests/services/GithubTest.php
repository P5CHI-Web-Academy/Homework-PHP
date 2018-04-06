<?php

use Service\Github;
use PHPUnit\Framework\TestCase;

final class GithubTest extends TestCase{

    /**
     * @dataProvider githubLoginProvider
     */
    public function testGithubApiReturnsValidProfiles($login, $expected){
        $actual = (new \Service\Github())->get_user($login);
        $this->assertSame($expected, $actual);
    }

    /**
     * Check, whether 'Github' service can handle situation, when it is impossible to connect to the API
     * Useful info at Google search result: "Unit Testing Tutorial Part V: Mock Methods and Overriding Constructors"
     * Basically, the coverage would be the same without this test, but I've spent too much time on it 0_0
     * @dataProvider githubLoginProvider
     */
    public function testGithubServiceWorkflowWithoutConnection($login){
        $mock = $this->getMockBuilder(Github::class)
            ->disableOriginalConstructor()
            ->setMethods(array('get_api_result'))
            ->getMock();
        $mock->expects($this->any())
            ->method('get_api_result')
            ->will($this->returnValue(false));
        $this->assertFalse($mock->get_user($login));
    }

    public function githubLoginProvider(){
        return [
            ['ksardedik', array('name'=>'Eduard Climov', 'url'=>'https://github.com/ksardedik')], // Positive
            ['', false]  // Negative
        ];
    }
}