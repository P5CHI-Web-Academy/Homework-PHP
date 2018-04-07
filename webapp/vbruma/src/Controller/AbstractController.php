<?php

namespace App\Controller;

use App\Services\Session\SessionAdapter;
use App\Services\TemplateParser;

abstract class AbstractController
{
    /**
     * @var string Path to templates directory
     */
    protected $templatePath = __DIR__.'/../../src/templates/';

    /**
     * @var SessionAdapter
     */
    protected $session;

    /**
     * @var TemplateParser
     */
    private $templateParser;

    /**
     * AbstractController constructor.
     *
     * @param SessionAdapter $session
     * @param TemplateParser $templateParser
     */
    public function __construct(SessionAdapter $session, TemplateParser $templateParser)
    {
        $this->session = $session;
        $this->templateParser = $templateParser;
    }

    /**
     * Redirect helper
     *
     * @param string $path
     */
    protected function redirect(string $path)
    {
        header('Location: ' . $path);
        ob_end_flush();
        die();
    }

    /**
     * Basic template renderer
     *
     * @param string $fileName
     * @param array $vars
     * @throws \Exception
     */
    protected function renderTemplate(string $fileName, array $vars = []): void
    {
        $content = \file_get_contents($this->templatePath . $fileName);
        $viewVars = array_merge($vars, [
            'hasError' => false,
            'error' => '',
            'items' => [
                ['name' => 'lol', 'something' => 'nope'],
                ['name' => 'lol2', 'something' => 'nope2']
            ]
        ]);

        // check session error variable
        if ($this->session->get('error')) {
            $viewVars['hasError'] = true;
            $viewVars['error'] = 'Username and/or password is invalid';

            $this->session->set('error', null);
        }

        $parsedContent = $this->templateParser->parse($content, $viewVars);

        echo($parsedContent);
    }
}
