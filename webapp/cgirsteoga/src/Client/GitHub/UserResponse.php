<?php

namespace Webapp\Client\GitHub;

use Webapp\Client\ResponseInterface;


class UserResponse implements ResponseInterface
{
    const MESSAGE = 'message';
    const HTML_URL = 'html_url';

    /**
     * @var \ArrayObject
     */
    protected $values;

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->values = new \ArrayObject($values);
    }

    /** {@inheritdoc} */
    public function isSuccessful()
    {
        return $this->values->offsetExists(static::HTML_URL);
    }

    /**
     * @param mixed $index
     * @param mixed $default
     * @return mixed|null
     */
    public function getOffset($index, $default = null)
    {
        return $this->values->offsetExists($index) ? $this->values->offsetGet($index) : $default;
    }

    /** {@inheritdoc} */
    public function getErrorMessage()
    {
        return $this->getOffset(static::ERROR_MESSAGE, '');
    }

    /** {@inheritdoc} */
    public function getData()
    {
        return $this->values->getArrayCopy();
    }
}
