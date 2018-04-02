<?php

namespace Webapp\Model;


class PasswordHelper
{
    /**
     * @param string $plain
     * @return string
     */
    public function hash(string $plain)
    {
        $result = password_hash($plain, PASSWORD_BCRYPT);
        if (!$result) {
            throw new \RuntimeException('Could not create password hash');
        }

        return $result;
    }

    /**
     * @param string $plain
     * @param string $hash
     * @return bool
     */
    public function verify(string $plain, string $hash)
    {
        return password_verify($plain, $hash);
    }
}
