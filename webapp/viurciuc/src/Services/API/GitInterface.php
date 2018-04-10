<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 4/5/18
 * Time: 2:13 PM
 */

namespace App\Services\API;


interface GitInterface
{
    /**
     * Get public user info
     *
     * @param $username
     * @return array
     */
    public function getUser($username): array;

    /**
     * @param $username
     * @return string
     */
    public function getProfileLink($username): string;
}

