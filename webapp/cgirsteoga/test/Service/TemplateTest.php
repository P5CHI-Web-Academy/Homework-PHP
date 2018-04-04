<?php

namespace Webapp\Service;

use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{

    public function testReplacePlaceholders()
    {
        $template = new Template();

        $content = 'Test {{value}}';
        $vars = ['value' => 'success'];

        $result = $template->replacePlaceholders($content, $vars);

        $this->assertContains('success', $result);
    }

    /**
     * @throws \Exception
     */
    public function testLoadTemplate()
    {
        $template = new Template();
        $templateContent = $template->loadTemplate('base.html');

        $this->assertContains('html', $templateContent);
    }

    /**
     * @throws \Exception
     */
    public function testCannotLoadNonExistingTemplate()
    {
        $template = new Template();

        $this->expectException(\Exception::class);
        $template->loadTemplate('nonExistingTemplate.html');
    }

    /**
     * @throws \Exception
     */
    public function testRender()
    {
        $template = new Template();
        $result = $template->render('index.html', []);

        $this->assertContains('Hello', $result);
    }

}
