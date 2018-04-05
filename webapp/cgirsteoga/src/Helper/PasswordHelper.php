<?php

namespace Webapp\Helper;


class PasswordHelper
{
    /**
     * @param string $plain
     * @return string
     */
    public function hash(string $plain)
    {
        return \password_hash($plain, PASSWORD_BCRYPT);
    }

    /**
     * @param string $plain
     * @param string $hash
     * @return bool
     */
    public function verify(string $plain, string $hash)
    {
        return \password_verify($plain, $hash);
    }
}
