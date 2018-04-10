<?php

namespace App\service;

class CurlService
{
    static function request(string $url, string $type = 'GET', $options = []): string
    {
        $type = in_array($type, ['GET', 'POST']) ? $type : 'GET';
        $curlh = curl_init();

        curl_setopt($curlh, CURLOPT_URL, $url);
        curl_setopt($curlh, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlh, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        if ($type === 'POST') {

            curl_setopt($curlh, CURLOPT_POST, 1);

            if (isset($options['postData']) && !empty($options['postData'])) {
                curl_setopt($curlh, CURLOPT_POSTFIELDS, http_build_query($options['postData']));
            }
        }

        $result = curl_exec($curlh);

        if (!$result) {
            $result = curl_error($curlh);
        }

        curl_close($curlh);

        return $result;
    }
}
