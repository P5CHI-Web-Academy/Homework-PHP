<?php

namespace App\tests\Services\API;

use App\Services\API\Bitbucket;
use App\Services\CurlClient;
use PHPUnit\Framework\TestCase;

class BitbucketTest extends TestCase
{
    private $curlClientStub;

    private $validUserInfo = [
        'username' => 'test',
        'links' => [
            'html' => [
                'href' => 'aLink'
            ]
        ]
    ];

    public function setUp()
    {
        parent::setUp();

        $this->curlClientStub = $this->createMock(CurlClient::class);
    }

    public function testGetUserValid()
    {
        $this->curlClientStub->method('request')
            ->willReturn(json_encode($this->validUserInfo));

        $github = new Bitbucket($this->curlClientStub);
        $userInfo = $github->getUser('anyname');

        $this->assertEquals($userInfo['username'], $this->validUserInfo['username']);
    }

    public function testGetUserNotFound()
    {
        $this->curlClientStub->method('request')
            ->willReturn('');

        $github = new Bitbucket($this->curlClientStub);
        $userInfo = $github->getUser('anyname');

        $this->assertEmpty($userInfo);
    }

    public function testGetProfileLinkValid()
    {
        $this->curlClientStub->method('request')
            ->willReturn(json_encode($this->validUserInfo));

        $github = new Bitbucket($this->curlClientStub);
        $link = $github->getProfileLink('anyname');

        $this->assertEquals($link, $this->validUserInfo['links']['html']['href']);
    }

    public function testGetProfileLinkEmpty()
    {
        $this->curlClientStub->method('request')
            ->willReturn('');

        $github = new Bitbucket($this->curlClientStub);
        $userInfo = $github->getProfileLink('anyname');

        $this->assertEmpty($userInfo);
    }
}