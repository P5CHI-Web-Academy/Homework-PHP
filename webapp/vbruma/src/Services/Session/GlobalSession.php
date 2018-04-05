<?php


namespace App\Services\Session;


class GlobalSession implements SessionAdapter
{

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value): bool
    {
        $_SESSION[$key] = $value;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        session_destroy();
    }
}