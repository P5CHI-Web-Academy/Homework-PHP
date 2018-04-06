<?php

namespace Webapp\Template;


class IfControlStructure extends AbstractControlStructure
{
    const CONTROL_BLOCK_REGEX = '/{%(\s+)?(?:if\s).*\bendif(\s+)?%}/sU';
    const CONDITION_REGEX = '/{%.*\bif.*(\b\w+)/';
    const BLOCK_START_REGEX = '/{%.*\bif.*%}/';
    const BLOCK_END_REGEX = '/{%.*\bendif.*%}/';

    /** @var string */
    protected $conditionVarName;
    /** @var bool */
    protected $conditionValue;

    /**
     * @throws \Exception
     */
    public function processContent()
    {
        if (!$this->matchBlock()) {
            return;
        }

        $this->parseBlock();
        $this->parseCondition();
        $this->evaluateCondition();

        if ($this->isTrue()) {
            $this->renderIfTrue();
        } else {
            $this->renderIfFalse();
        }
    }

    /**
     * Parse condition in if statement
     *
     * @throws \Exception
     */
    protected function parseCondition()
    {
        $matches = [];
        if (!\preg_match(self::CONDITION_REGEX, $this->structureStatement, $matches)) {
            throw new \Exception(\sprintf('Incorrect if statement: %s ', $this->structureStatement));
        }
        if (\count($matches) != 2) {
            throw new \Exception(\sprintf('Incorrect if statement: %s ', $this->structureStatement));
        }

        $this->conditionVarName = \trim($matches[1]);
    }

    /**
     * Evaluate statement condition to bool value
     *
     * @throws \Exception
     */
    protected function evaluateCondition()
    {
        $this->conditionValue = (bool)$this->getVarByName($this->conditionVarName);
    }

    /**
     * @return bool
     */
    protected function isTrue()
    {
        return $this->conditionValue;
    }

    /**
     * Render when condition in if statement is true
     */
    protected function renderIfTrue()
    {
        $resultStatementContent = \preg_replace(
            [self::BLOCK_START_REGEX, self::BLOCK_END_REGEX],
            '',
            $this->structureStatement
        );
        $this->content = \str_replace($this->structureStatement, $resultStatementContent, $this->content);
    }

    /**
     * Render when condition in if statement is false
     */
    protected function renderIfFalse()
    {
        $this->content = \str_replace($this->structureStatement, '', $this->content);
    }
}
