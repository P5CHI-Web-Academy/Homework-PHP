<?php

namespace App\Services\API;


use App\Services\CurlClient;

class Github extends AbstractGit
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
     * @var CurlClient
     */
    private $curlClient;

    /**
     * Github constructor.
     *
     * @param CurlClient $curlClient
     */
    public function __construct(CurlClient $curlClient)
    {
        $this->curlClient = $curlClient;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($username): array
    {
        $url = $this->baseUrl . 'users/' . $username;

        $response = json_decode($this->curlClient->request($url, 'GET'), true);

        $this->userInfo = is_array($response) ? $response : [];

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

        return isset($this->userInfo['html_url']) ? $this->userInfo['html_url'] : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getRepositories($username): array
    {
        $url = $this->baseUrl . 'users/' . $username . '/repos';

        $response = json_decode($this->curlClient->request($url, 'GET'), true);

        return is_array($response) ? $response : [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRepositoryCommits($username, $repository): array
    {
        $url = $this->baseUrl . 'repos/' . $username . '/' . $repository . '/commits';

        $response = json_decode($this->curlClient->request($url, 'GET'), true);

        return is_array($response) ? $response : [];
    }
}

