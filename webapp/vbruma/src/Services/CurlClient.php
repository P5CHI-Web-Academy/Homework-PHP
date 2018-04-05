<?php

namespace App\Services;


class CurlClient
{

    /**
     * Make basic GET/POST request
     *
     * @param string $url
     * @param string $type
     * @param array $options
     * @return string
     */
    public static function request(string $url, string $type = 'GET', $options = []): string
    {
        $type = in_array($type, ['GET', 'POST']) ? $type : 'GET';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        if ($type === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);

            if ( isset($options['postData']) && !empty($options['postData']) ) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options['postData']));
            }
        }

        $result = curl_exec($ch);
        if (! $result) {
            $result = curl_error($ch);
        }

        curl_close($ch);

        return $result;
    }
}

