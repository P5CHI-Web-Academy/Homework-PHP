<?php
/**
 * Copyright (c) 2018.
 *
 *  @author		Alexander Sterpu <alexander.sterpu@gmail.com>
 *
 */

namespace WebApp;


/**
 * Class GitHubUserChecker
 * @package WebApp
 */
class GitHubUserChecker
{
    /**
     * GitHubUserChecker constructor.
     */
    private function __construct() { }

    /**
     * @param $user_name
     * @return bool
     */
    public static function hasGitHubProfile($user_name)
    {
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_URL => 'https://api.github.com/users/' . $user_name,
            CURLOPT_USERAGENT => 'P5CHI-Web-Academy',
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => true
        ));

        curl_exec($ch);
        
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $httpcode === 200;
    }
}