<?php

namespace Webapp\Client;


class CurlClient implements ClientInterface
{
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';

    protected $userAgent = 'CLI Client';

    /**
     * @inheritDoc
     */
    public function get(RequestInterface $request)
    {
        return $this->makeRequest($request->getHost());
    }

    /**
     * @inheritDoc
     */
    public function post(RequestInterface $request)
    {
        $data = json_encode($request->getData());

        return $this->makeRequest($request->getHost(), self::HTTP_POST, $data);
    }

    /**
     * @param string $uri
     * @param string $type
     * @param string $data
     * @return array
     */
    protected function makeRequest(string $uri, string $type = self::HTTP_GET, $data = null)
    {
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $uri);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handler, CURLOPT_USERAGENT, $this->userAgent);
        if (self::HTTP_POST === $type) {
            curl_setopt($handler, CURLOPT_POST, 1);
            if (null !== $data) {
                curl_setopt($handler, CURLOPT_POSTFIELDS, $data);
            }
        }
        $result = curl_exec($handler);

        if (!$result) {
            $result = [
                ResponseInterface::ERROR_MESSAGE => curl_error($handler),
            ];
        } else {
            $result = json_decode($result, true);
        }

        curl_close($handler);

        return $result;
    }
}
