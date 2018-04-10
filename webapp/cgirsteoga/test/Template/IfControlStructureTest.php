<?php

namespace Webapp\Template;

use PHPUnit\Framework\TestCase;

class IfControlStructureTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testProcessConditionTrue()
    {
        $vars = [
            'condition' => true,
        ];

        $ifStructure = new IfControlStructure();
        $ifStructure->setContentData($this->getValidContent(), $vars);

        $content = $ifStructure->process()->getContent();
        $this->assertContains('<p>Condition is true</p>', $content);
    }

    /**
     * @throws \Exception
     */
    public function testProcessConditionFalse()
    {
        $vars = [
            'condition' => false,
        ];

        $ifStructure = new IfControlStructure();
        $ifStructure->setContentData($this->getValidContent(), $vars);

        $content = $ifStructure->process()->getContent();
        $this->assertNotContains('<p>Condition is true</p>', $content);
    }

    public function testIsSupportedWhenValidContent()
    {
        $vars = [
            'condition' => true,
        ];

        $ifStructure = new IfControlStructure();
        $ifStructure->setContentData($this->getValidContent(), $vars);

        $this->assertTrue($ifStructure->isSupported());
    }

    public function testIsNotSupportedWhenInvalidContent()
    {
        $ifStructure = new IfControlStructure();
        $ifStructure->setContentData($this->getInvalidContent(), []);

        $this->assertFalse($ifStructure->isSupported());
    }

    protected function getValidContent()
    {
        $content = <<<HTML
        <div>
            {% if condition %}
                <p>Condition is true</p>
            {% endif %}
        </div>
HTML;

        return $content;
    }

    protected function getInvalidContent()
    {
        $content = <<<HTML
        <div>
            {% ifa condition %}
                <p>Condition is true</p>
            {% endif %}
        </div>
HTML;

        return $content;
    }
}
