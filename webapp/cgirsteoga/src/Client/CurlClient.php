<?php

namespace Webapp\Client;


class CurlClient implements ClientInterface
{
    const HTTP_GET = 'GET';

    protected $userAgent = 'CLI Client';

    /**
     * @inheritDoc
     */
    public function get(RequestInterface $request)
    {
        return $this->makeRequest($request->getHost());
    }

    /**
     * @param string $uri
     * @return array
     */
    protected function makeRequest(string $uri)
    {
        $handler = curl_init();
        curl_setopt_array(
            $handler,
            [
                CURLOPT_URL => $uri,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_USERAGENT => $this->userAgent,
            ]
        );

        $result = curl_exec($handler);

        if (!$result) {
            $result = [
                ResponseInterface::ERROR_MESSAGE => curl_error($handler),
            ];
        } else {
            $result = \json_decode($result, true);
        }

        curl_close($handler);

        return $result;
    }
}
