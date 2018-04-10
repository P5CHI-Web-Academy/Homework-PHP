<?php

namespace Webapp\Client\GitHub;

use Webapp\Client\RequestInterface;


class RepoRequest implements RequestInterface
{
    const HOST = 'https://api.github.com/repos/%s/%s/%s';
    const COMMITS = 'commits';
    const CONTRIBUTORS = 'contributors';

    /** @var string */
    protected $userName;
    /** @var string */
    private $repoName;
    /** @var string */
    private $data;

    /**
     * UserRequest constructor.
     * @param string $userName
     * @param string $repoName
     * @param string $data
     */
    public function __construct(string $userName, string $repoName, string $data)
    {
        $this->userName = $userName;
        $this->repoName = $repoName;
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getHost()
    {
        return \sprintf(static::HOST, $this->userName, $this->repoName, $this->data);
    }
}
