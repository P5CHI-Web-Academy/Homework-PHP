<?php

namespace App\service\api;

use App\service\CurlService;

class Bitbucket implements GitInterface
{
    private $baseUrl = 'https://api.bitbucket.org/2.0/';

    private $userInfo = [];

    function getUsers($username): array
    {
        $url = $this->baseUrl . 'users/' . $username;

        $response = json_decode(CurlService::request($url, 'GET'), true);

        $this->userInfo = is_array($response) ? $response : [];

        return $this->userInfo;
    }

    function getProfileLink($username): string
    {
        if (empty($this->userInfo)) {
            $this->getUsers($username);
        }
        return $this->userInfo['links']['html']['href'] ?? 'https://bitbucket.org/';
    }
}