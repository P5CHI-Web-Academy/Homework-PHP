<?php

namespace Webapp\Client;


interface ResponseInterface
{
    const ERROR_MESSAGE = 'error_message';

    /**
     * @return bool
     */
    public function isSuccessful();

    /**
     * @return string
     */
    public function getErrorMessage();

    /**
     * @return array
     */
    public function getData();
}
