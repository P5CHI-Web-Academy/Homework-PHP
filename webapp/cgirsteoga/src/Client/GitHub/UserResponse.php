<?php

namespace Webapp\Client\GitHub;


class UserResponse extends Response
{
    const HTML_URL = 'html_url';

    /** {@inheritdoc} */
    public function isSuccessful()
    {
        return $this->values->offsetExists(static::HTML_URL);
    }
}
