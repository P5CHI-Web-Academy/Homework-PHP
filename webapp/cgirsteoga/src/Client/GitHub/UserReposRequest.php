<?php

namespace Webapp\Client\GitHub;


class UserReposRequest extends UserRequest
{
    /**
     * @inheritDoc
     */
    public function getHost()
    {
        $baseHost = parent::getHost();

        return \sprintf('%s/repos', $baseHost);
    }
}
