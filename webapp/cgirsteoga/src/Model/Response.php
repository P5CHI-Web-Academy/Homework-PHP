<?php

namespace Webapp\Model;


class Response
{
    const HTTP_OK = 200;
    const HTTP_NOT_FOUND = 404;

    protected static $statusText = [
        200 => 'OK',
        404 => 'Not Found',
    ];

    /** @var int */
    protected $code;
    /** @var string */
    protected $content;

    public function __construct(string $content, int $code = self::HTTP_OK)
    {
        $this->code = $code;
        $this->content = $content;
    }

    /**
     * @return $this
     */
    public function send()
    {
        $this->sendHeaders();

        echo $this->getContent();

        return $this;
    }

    protected function sendHeaders()
    {
        $statusText = static::$statusText[$this->code] ?? 'Unknown status';
        \header(\sprintf('HTTP/1.1  %s %s', $this->code, $statusText), true, $this->code);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }
}
