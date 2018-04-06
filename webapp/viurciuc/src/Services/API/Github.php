<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 4/5/18
 * Time: 2:14 PM
 */

namespace App\Services\API;


use App\Services\CurlClient;

class Github implements GitInterface
{
    /**
     * @var string Github api url
     */
    private $baseUrl = 'https://api.github.com/';

    /**
     * @var array Git public profile info
     */
    private $userInfo = [];

    /**
     * {@inheritdoc}
     */
    public function getUser($username): array
    {
        $url = $this->baseUrl.'users/'.$username;

        $response = json_decode(CurlClient::request($url, 'GET'), true);

        $this->userInfo = $response;

        return $this->userInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileLink($username): string
    {
        if (empty($this->userInfo)) {
            $this->getUser($username);
        }

        return $this->userInfo['html_url'] ?? 'https://github.com/';
    }
}

