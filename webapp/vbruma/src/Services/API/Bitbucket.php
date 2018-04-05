<?php

namespace App\Services\API;


use App\Services\CurlClient;

class Bitbucket implements GitInterface
{
    /**
     * @var string Github api url
     */
    private $baseUrl = 'https://api.bitbucket.org/2.0/';

    /**
     * @var array Git public profile info
     */
    private $userInfo = [];

    /**
     * {@inheritdoc}
     */
    public function getUser($username): array
    {
        $url = $this->baseUrl . 'users/' . $username;

        $response = json_decode(CurlClient::request($url, 'GET'), true);

        $this->userInfo = is_array($response) ? $response : [];

        return $this->userInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileLink($username): string
    {
        if (empty($userInfo)) {
            $this->getUser($username);
        }

        return $this->userInfo['links']['html']['href'];
    }
}

