<?php

namespace Webapp\Service;


use Webapp\Template\AbstractControlStructure;

class Template
{
    const TEMPLATE_DIR = __DIR__.'/../Templates/';

    /** @var AbstractControlStructure[] */
    protected $controlStructures;

    /**
     * Template constructor.
     */
    public function __construct()
    {
        $this->controlStructures = [];
    }

    /**
     * @param AbstractControlStructure $controlStructure
     * @return $this
     */
    public function addControlStructure(AbstractControlStructure $controlStructure)
    {
        $this->controlStructures[] = $controlStructure;

        return $this;
    }

    /**
     * @param string $template
     * @param array $vars
     * @throws \Exception
     * @return string
     */
    public function render(string $template, array $vars = [])
    {
        $templateContent = $this->renderPartial($template, $vars);

        $baseTemplate = $this->loadTemplate('base.html');
        $templateData = \array_merge(['body' => $templateContent], $vars);

        $result = $this->processControlStructures($baseTemplate, $vars);
        $result = $this->replacePlaceholders($result, $templateData);

        return $result;
    }

    /**
     * @param string $template
     * @param array $vars
     * @throws \Exception
     * @return string
     */
    public function renderPartial(string $template, array $vars = [])
    {
        $templateContent = $this->loadTemplate($template);
        $templateContent = $this->processControlStructures($templateContent, $vars);
        $templateContent = $this->replacePlaceholders($templateContent, $vars);

        return $templateContent;
    }

    /**
     * @param string $templateContent
     * @param array $vars
     * @return string
     */
    public function processControlStructures(string $templateContent, array $vars)
    {
        foreach ($this->controlStructures as $controlStructure) {
            $templateContent = $controlStructure
                ->setContentData($templateContent, $vars)
                ->process()
                ->getContent();
        }

        return $templateContent;
    }

    /**
     * @param string $template
     * @return bool|string
     * @throws \Exception
     */
    public function loadTemplate(string $template)
    {
        $file = self::TEMPLATE_DIR.$template;

        if (!\file_exists($file)) {
            throw new \Exception(\sprintf('Could not find template: %s', $template));
        }

        return \file_get_contents($file);
    }

    /**
     * @param string $templateContent
     * @param array $vars
     * @return string
     */
    public function replacePlaceholders(string $templateContent, array $vars)
    {
        $templateContent = \preg_replace_callback(
            '/{{(.+?)}}/',
            function ($match) use ($vars) {
                $name = \trim($match[1]);

                return $vars[$name] ?? '';
            },
            $templateContent
        );

        return $templateContent;
    }

}
