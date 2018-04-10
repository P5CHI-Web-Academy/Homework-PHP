<?php

namespace Webapp\Template;


class ForControlStructure extends AbstractControlStructure
{
    const CONTROL_BLOCK_REGEX = '/{%(\s+)?(?:\bfor\s).*endfor(\s+)?%}/sU';
    const CONTROL_STATEMENT_REGEX = '/{%\s*for\s+(\b\w+)\s+(?:\bin\s).*(\b\w+).*/';
    const LOOP_BLOCK_REGEX = '/(?<=(%}\n)).*(?={%)/sU';

    /** @var string */
    protected $iteratorName;
    /** @var \Iterator */
    protected $iteratorValue;
    /** @var string */
    protected $itemName;
    /** @var string */
    protected $loopBlockContent;

    /**
     * @inheritDoc
     * @throws \Exception
     */
    function processContent()
    {
        if (!$this->matchBlock()) {
            return;
        }
        $this->parseBlock();
        $this->parseControlStatement();
        $this->parseLoopBlock();
        $this->render();
    }

    /**
     * @throws \Exception
     */
    protected function parseControlStatement()
    {
        $statementMatch = [];
        if (!\preg_match(static::CONTROL_STATEMENT_REGEX, $this->structureStatement, $statementMatch)) {
            throw new \Exception(\sprintf('Invalid FOR statement: %s', $this->structureStatement));
        }

        $this->itemName = \trim($statementMatch[1]);
        $this->iteratorName = \trim($statementMatch[2]);
        $this->iteratorValue = $this->getVarByName($this->iteratorName);
    }

    /**
     * @throws \Exception
     */
    protected function parseLoopBlock()
    {
        $loopMatch = [];
        if (!\preg_match(static::LOOP_BLOCK_REGEX, $this->structureStatement, $loopMatch)) {
            throw new \Exception(\sprintf('Invalid loop block in FOR statement: %s', $this->structureStatement));
        }
        $this->loopBlockContent = \trim($loopMatch[0]);
    }

    protected function render()
    {
        $itemRegex = \sprintf('/{{(.*%s.*)}}/U', $this->itemName);

        $result = [];
        foreach ($this->iteratorValue as $itemValue) {
            $result[] = \preg_replace_callback(
                $itemRegex,
                function ($match) use ($itemValue) {
                    if (\is_array($itemValue)) {
                        $matchParts = explode('.', \trim($match[1]));
                        if (isset($matchParts[1])) {
                            return $itemValue[$matchParts[1]];
                        }
                    }

                    return $itemValue;
                },
                $this->loopBlockContent
            );
        }

        $resultContent = \implode($result);

        $this->content = \str_replace($this->structureStatement, $resultContent, $this->content);
    }
}
