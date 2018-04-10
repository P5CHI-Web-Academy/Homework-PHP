<?php

namespace App\service\api;

use App\service\CurlService;

class Github implements GitInterface
{

    private $baseUrl = 'https://api.github.com/';

    private $userInfo = [];

    function getUser($username): array
    {
        $url = $this->baseUrl.'users/'.$username;

        $response = json_decode(CurlService::request($url, 'GET'), true);

        $this->userInfo = $response;

        return $this->userInfo;
    }

    function getProfileLink($username): string
    {
        if (empty($this->userInfo)) {
            $this->getUser($username);
        }

        return $this->userInfo['html_url'] ?? 'https://github.com/';
    }
}
