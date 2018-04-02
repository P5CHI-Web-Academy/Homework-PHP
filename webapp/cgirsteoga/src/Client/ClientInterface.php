<?php

namespace Webapp\Client;


interface ClientInterface
{
    /**
     * @param RequestInterface $request
     * @return array
     */
    public function get(RequestInterface $request);

    /**
     * @param RequestInterface $request
     * @return array
     */
    public function post(RequestInterface $request);
}