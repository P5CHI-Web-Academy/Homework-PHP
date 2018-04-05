<?php

namespace App\Controller;

use App\Services\Session\SessionAdapter;

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
     * AbstractController constructor.
     *
     * @param SessionAdapter $session
     */
    public function __construct(SessionAdapter $session)
    {
        $this->session = $session;
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
     */
    protected function renderTemplate(string $fileName, array $vars = []): void
    {
        $content = \file_get_contents($this->templatePath . $fileName);
        $viewVars = array_merge($vars, [
            'errorDisplay' => 'none'
        ]);

        // check session error variable
        if ($this->session->get('error')) {
            $viewVars['errorDisplay'] = 'block';
            $viewVars['error'] = 'Username and/or password is invalid';

            $this->session->set('error', null);
        }

        foreach ($viewVars as $key => $value) {
            $content = str_replace('{{ '.$key.' }}', $value, $content);
        }

        echo($content);
    }
}
