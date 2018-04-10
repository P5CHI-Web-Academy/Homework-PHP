<?php

namespace Webapp\Template;

use PHPUnit\Framework\TestCase;

class ForControlStructureTest extends TestCase
{

    public function testIsSupportedWhenContentIsValid()
    {
        $vars = [
            'list' => \range(1, 5),
        ];

        $forControl = new ForControlStructure();
        $forControl->setContentData($this->getValidContent(), $vars);

        $this->assertTrue($forControl->isSupported());
    }

    public function testIsNotSupportedWhenContentIsInvalid()
    {
        $vars = [
            'list' => \range(1, 5),
        ];
        $forControl = new ForControlStructure();
        $forControl->setContentData($this->getInvalidContent(), $vars);

        $this->assertFalse($forControl->isSupported());
    }

    /**
     * @throws \Exception
     */
    public function testProcessContent()
    {
        $vars = [
            'list' => \range(1, 5),
        ];

        $forControl = new ForControlStructure();
        $forControl->setContentData($this->getValidContent(), $vars);

        $content = $forControl->process()->getContent();

        $this->assertContains('Item3', $content);
    }

    protected function getValidContent()
    {
        $content = <<<HTML
        <div>
            {% for item in list %}
                <p>Item{{ item }}</p>
            {% endfor %}
        </div>
HTML;

        return $content;
    }

    protected function getInvalidContent()
    {
        $content = <<<HTML
        <div>
            {% foritem in list %}
                <p>{{ item }}</p>
            {% endfor %}
        </div>
HTML;

        return $content;
    }
}
