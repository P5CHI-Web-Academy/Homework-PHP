<?php

namespace Webapp\Controller;

use Webapp\Service\ServiceContainer;

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
        return $this->services->get('template')->render($templateName, $vars);
    }

    /**
     * @param string $templateName
     * @param array $vars
     * @return string
     * @throws \Exception
     */
    protected function renderPartial(string $templateName, array $vars)
    {
        return $this->services->get('template')->renderPartial($templateName, $vars);
    }
}
