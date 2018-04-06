<?php

namespace Webapp\Template;


abstract class AbstractControlStructure
{
    const CONTROL_BLOCK_REGEX = '';

    /** @var array */
    protected $vars;
    /** @var string */
    protected $content;
    /** @var array */
    protected $blockMatches;
    /** @var string */
    protected $structureStatement;

    /**
     * AbstractControlStructure constructor.
     */
    public function __construct()
    {
        $this->vars = [];
        $this->content = '';
        $this->blockMatches = [];
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function getVarByName(string $name)
    {
        if (!isset($this->vars[$name])) {
            throw new \Exception(\sprintf('Undefined variable: %s ', $name));
        }

        return $this->vars[$name];
    }

    /**
     * @return bool
     */
    protected function matchBlock()
    {
        return (bool)\preg_match(static::CONTROL_BLOCK_REGEX, $this->content, $this->blockMatches);
    }

    /**
     *  Parse if statement block
     */
    protected function parseBlock()
    {
        $this->structureStatement = $this->blockMatches[0];
    }

    /**
     * @inheritDoc
     */
    public function isSupported()
    {
        return $this->matchBlock();
    }

    /**
     * @param string $content
     * @param array $vars
     * @return $this
     */
    public function setContentData(string $content, array $vars)
    {
        $this->content = $content;
        $this->vars = $vars;

        return $this;
    }

    /**
     * @return $this
     */
    public function process()
    {
        while ($this->isSupported()) {
            $this->processContent();
        }

        return $this;
    }

    /**
     * Process control structure in content
     */
    abstract public function processContent();
}
