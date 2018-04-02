<?php

namespace Webapp\Client\GitHub;

use Webapp\Client\RequestInterface;


class UserRequest implements RequestInterface
{
    const HOST = 'https://api.github.com/users/';

    /** @var string */
    protected $userName;

    /**
     * UserRequest constructor.
     * @param string $userName
     */
    public function __construct(string $userName)
    {
        $this->userName = $userName;
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
        return static::HOST.$this->userName;
    }
}
