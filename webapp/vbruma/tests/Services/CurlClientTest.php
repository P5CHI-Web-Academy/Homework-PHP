<?php

namespace App\tests\Services;

use App\Services\CurlClient;
use PHPUnit\Framework\TestCase;


class CurlClientTest extends TestCase
{
    public function testGetRequest()
    {
        $curlClient = new CurlClient();

        $response = json_decode($curlClient->request('https://api.github.com/users/viLLoyd'), true);

        $this->assertArrayHasKey('html_url', $response);
    }

    public function testPostRequest()
    {
        $curlClient = new CurlClient();

        $response = json_decode($curlClient->request('https://api.github.com/users/viLLoyd', 'POST'), true);

        $this->assertArrayHasKey('message', $response);
        $this->assertContains('Not Found', $response['message']);
    }
}