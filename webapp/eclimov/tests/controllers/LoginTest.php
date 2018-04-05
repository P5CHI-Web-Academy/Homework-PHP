<?php

use PHPUnit\Framework\TestCase;

final class LoginTest extends TestCase{
    protected static $template_login_content;

    protected static $m_user;

    public static function setUpBeforeClass()/* The :void return type declaration that should be here would cause a BC issue */
    {
        if(!defined('TEMPLATES_PATH')){
            define('TEMPLATES_PATH', __DIR__.'/../views/');  // Test path
        }

        self::$template_login_content = "{{color}} {{login_message}}";

        file_put_contents(TEMPLATES_PATH.'/login.html', self::$template_login_content);

        if(!defined('DB_DSN')){
            define('DB_DSN', 'sqlite::memory:');
        }
        self::$m_user = new \Model\User();
        self::$m_user->create_user('testuser1', 'l1', 'p1');
    }

    public function testNoCredentialsProvided(){
        if(isset($_POST['login'])){
            unset($_POST['login']);
        }
        if(isset($_POST['password'])){
            unset($_POST['password']);
        }

        $t_templater = new \Service\Templater();

        $this->expectOutputString('black Please, log in');

        new \Controller\Login(self::$m_user, $t_templater);
    }

    public function testLoginInorrectCredentials(){
        $_POST['login'] = 'l1';
        $_POST['password'] = 'qwerty';
        $t_templater = new \Service\Templater();

        $this->expectOutputString('red Incorrect login or password!');

        new \Controller\Login(self::$m_user, $t_templater);
    }

    public static function tearDownAfterClass()/* The :void return type declaration that should be here would cause a BC issue */
    {
        unlink(TEMPLATES_PATH.'/login.html');
        self::$m_user = null;
    }
}