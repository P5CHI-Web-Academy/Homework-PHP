<?php

namespace App\Services\Session;


interface SessionAdapter
{
    /**
     * Get a session variable from storage
     *
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * Set a session variable to storage
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value): bool;

    /**
     * Clear the session
     */
    public function clear(): void;
}