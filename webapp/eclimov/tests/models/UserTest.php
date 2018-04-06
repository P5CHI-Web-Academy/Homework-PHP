<?php

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase{
    protected static $m_user;

    public static function setUpBeforeClass()
    {
        if(!defined('DB_DSN')){
            define('DB_DSN', 'sqlite::memory:');
        }

        self::$m_user = new \Model\User();
        self::$m_user->create_user('testuser1', 'l1', 'p1');
        self::$m_user->create_user('testuser2', 'l2', 'p2');
    }

    /**
     * @dataProvider userCredentialsProvider
     */
    public function testUserAuth($login, $password, $expected){
        $this->assertEquals(self::$m_user->authorize_user($login, $password), $expected);
    }

    public function userCredentialsProvider(){
        return [
            ['l1','p1',1],  //Positive
            ['l2','p2',2],  //Positive
            ['qwe','zzz',0],  //Negative
        ];
    }

    /**
     * @dataProvider userInfoProvider
     */
    public function testUserGetInfo($user_id, $expected){
        $this->assertEquals(self::$m_user->get_user_info($user_id), $expected);
    }

    public function userInfoProvider(){
        return [
            [1, array('id'=>1, 'name'=>'testuser1', 'login'=>'l1')],  //Positive
            [2, array('id'=>2, 'name'=>'testuser2', 'login'=>'l2')],  //Positive
            [0, array()],  //Negative
        ];
    }

    public function testcreateUser(){
        $this->assertEquals(self::$m_user->create_user('testuser3', 'l3', 'p3'), 3);
    }

    public static function tearDownAfterClass(){
        self::$m_user = null;
    }
}