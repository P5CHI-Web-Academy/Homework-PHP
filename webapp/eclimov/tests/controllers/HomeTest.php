<?php

use PHPUnit\Framework\TestCase;

final class HomeTest extends TestCase{
    protected static $template_home_content;
    protected static $template_login_content;

    protected static $m_user;

    public static function setUpBeforeClass()/* The :void return type declaration that should be here would cause a BC issue */
    {
        if(!defined('TEMPLATES_PATH')){
            define('TEMPLATES_PATH', __DIR__.'/../views/');  // Test path
        }

        self::$template_home_content = "{{user_name}} {{github_profile_url}}";
        self::$template_login_content = "{{color}} {{login_message}}";

        file_put_contents(TEMPLATES_PATH.'/home.html', self::$template_home_content);
        file_put_contents(TEMPLATES_PATH.'/login.html', self::$template_login_content);

        if(!defined('DB_DSN')){
            define('DB_DSN', 'sqlite::memory:');
        }
        self::$m_user = new \Model\User();
        self::$m_user->create_user('testuser1', 'l1', 'p1');
    }

    public function testHomeTemplateGeneratedCorrectly(){
        $_SESSION['user_id'] = 1;
        $t_templater = new \Service\Templater();

        $github_api_mock = $this->getMockBuilder(\Service\Github::class)
            ->disableOriginalConstructor()
            ->setMethods(array('get_user'))
            ->getMock();
        $github_api_mock->expects($this->any())
            ->method('get_user')
            ->will($this->returnValue(array('url'=>'aaa')));

        $this->expectOutputString('testuser1 aaa');

        new \Controller\Home(self::$m_user, $t_templater, $github_api_mock);
    }

    /*
    // TODO: assert redirect. Doesn't work at the moment. Will be fixed, when some sort of "expectRedirect" is found
    // TO READ: https://stackoverflow.com/questions/45965699/mocks-vs-stubs-in-phpunit
    public function testRedirect(){
        if(isset($_SESSION['user_id'])){
            unset($_SESSION['user_id']);
        }
        $t_templater = new \Service\Templater();

        $github_api_mock = $this->getMockBuilder(\Service\Github::class)
            ->disableOriginalConstructor()
            ->setMethods(array('get_user'))
            ->getMock();
        $github_api_mock->expects($this->any())
            ->method('get_user')
            ->will($this->returnValue(array('url'=>'aaa')));

        $this->expectOutputRegex("/302/");
        new \Controller\Home(self::$m_user, $t_templater, $github_api_mock);

        //$this->assertEquals(302, (new \Controller\Home(self::$m_user, $t_templater, $github_api_mock)));
    }
    */

    public static function tearDownAfterClass()/* The :void return type declaration that should be here would cause a BC issue */
    {
        unlink(TEMPLATES_PATH.'/home.html');
        unlink(TEMPLATES_PATH.'/login.html');
        self::$m_user = null;
    }
}