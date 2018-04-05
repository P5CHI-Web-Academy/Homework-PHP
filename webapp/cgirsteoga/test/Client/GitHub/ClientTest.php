<?php

namespace Webapp\Client\GitHub;

use PHPUnit\Framework\TestCase;
use Webapp\Client\CurlClient;

class ClientTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testCanGetUserInfo()
    {
        $testUserInfo = [
            'test' => 'test',
        ];

        $httpClient = $this->createMock(CurlClient::class);
        $httpClient->method('get')->willReturn($testUserInfo);

        $client = new Client($httpClient);

        $userInfo = $client->getUserInfo('test_user');

        $this->assertSame($testUserInfo, $userInfo->getData());
    }

    /**
     * @throws \ReflectionException
     */
    public function testCannotGetUserInfoWhenErrorOnHTTPClient()
    {
        $testUserInfo = [
            'error_message' => 'test error',
        ];

        $httpClient = $this->createMock(CurlClient::class);
        $httpClient->method('get')->willReturn($testUserInfo);

        $client = new Client($httpClient);
        $this->expectException(\Exception::class);
        $client->getUserInfo('test_user');
    }

    public function testCanGetProfileLink()
    {
        $profileLink = 'http://test.test.teste/adas';
        $testUserInfo = [
            UserResponse::HTML_URL => $profileLink,
        ];

        $response = new UserResponse($testUserInfo);

        $clientMock = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUserInfo'])
            ->getMock();
        $clientMock
            ->expects($this->any())
            ->method('getUserInfo')
            ->willReturn($response);

        $this->assertSame($profileLink, $clientMock->getProfileLink('testUser'));
    }

    public function testCannotGetProfileLink()
    {
        $profileLink = 'http://test.test.teste/adas';
        $testUserInfo = [
            'test_data' => $profileLink,
        ];

        $response = new UserResponse($testUserInfo);

        $clientMock = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['getUserInfo'])
            ->getMock();
        $clientMock
            ->expects($this->any())
            ->method('getUserInfo')
            ->willReturn($response);

        $this->assertNull($clientMock->getProfileLink('testUser'));
    }
}
