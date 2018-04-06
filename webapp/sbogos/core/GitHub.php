<?php

namespace App\Core;

class GitHub
{
    public static function fetch($user)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.github.com/users/{$user}");
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode(trim($output))->html_url ?? null;
    }
}
