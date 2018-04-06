<?php

namespace App\Tests\Model;

use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{

    private $userRepository;

    private $validCredentials = [
        'username' => 'test_user',
        'password' => 'test_password'
    ];

    private $invalidCredentials = [
        'username' => 'wrong',
        'password' => ''
    ];


    public function setUp()
    {
        parent::setUp();

        $this->userRepository = new UserRepository(new \PDO('sqlite:database/vbruma.db'));
    }

    /**
     * Retrieve an existing user
     */
    public function testGetCorrect()
    {
        $user = $this->userRepository->get($this->validCredentials['username'], $this->validCredentials['password']);

        $this->assertEquals($user['username'], $this->validCredentials['username']);
    }

    /**
     * Retrieve a non-existing user
     */
    public function testGetIncorrect()
    {
        $this->assertNull(
            $this->userRepository->get($this->invalidCredentials['username'], $this->invalidCredentials['password'])
        );
    }
}