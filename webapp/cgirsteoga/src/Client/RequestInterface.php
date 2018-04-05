<?php

namespace Webapp\Client;


interface RequestInterface
{
    /**
     * @return array
     */
    public function getData();

    /**
     * @return string
     */
    public function getHost();
}
