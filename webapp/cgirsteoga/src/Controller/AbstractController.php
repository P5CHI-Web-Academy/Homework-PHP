<?php

namespace Webapp\Controller;

use Webapp\Model\Template;
use Webapp\Model\ServiceContainer;

class AbstractController
{
    /** @var ServiceContainer */
    protected $services;

    /**
     * AbstractController constructor.
     * @param ServiceContainer $services
     */
    public function __construct(ServiceContainer $services)
    {
        $this->services = $services;
    }

    /**
     * @param string $templateName
     * @param array $vars
     * @return string
     * @throws \Exception
     */
    protected function render(string $templateName, array $vars)
    {
        /** @var Template $template */
        $template = $this->services->get('template');

        return $template->render($templateName, $vars);
    }
}
