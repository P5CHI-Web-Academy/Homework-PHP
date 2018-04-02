<?php

namespace Webapp\Model;


class Template
{
    const TEMPLATE_DIR = __DIR__.'/../Templates/';

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
        $templateData = array_merge(['body' => $templateContent], $vars);

        $result = $this->replacePlaceholders($baseTemplate, $templateData);

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
        $templateContent = $this->replacePlaceholders($templateContent, $vars);

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
            throw new \Exception(sprintf('Could not find template: %s', $template));
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
        $templateContent = preg_replace_callback(
            '/\{\{(\w+)}}/',
            function ($match) use ($vars) {
                $name = $match[1];

                return $vars[$name] ?? '';
            },
            $templateContent
        );

        return $templateContent;
    }

}
