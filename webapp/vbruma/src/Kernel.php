<?php

namespace App;


use App\Services\Session\SessionAdapter;

class Kernel
{
    /**
     * @var array
     */
    private $requestVars;

    /**
     * @var string
     */
    private $configPath;

    /**
     * Kernel constructor.
     *
     * @param array $requestVars
     * @param string $configPath
     */
    public function __construct($requestVars = [], $configPath = '')
    {
        $this->requestVars = $requestVars;
        $this->configPath = $configPath;

        $this->setProjectDependencies();
    }

    /**
     * Main initialization logic
     *
     * @param string $uri
     * @throws \ReflectionException
     */
    public function handle($uri = '')
    {
        $map = array(
            '/' => 'LoginController@index',
            '/login' => 'LoginController@login',
            '/logout' => 'LoginController@logout'
        );

        $requestPath = explode('?', $uri)[0];

        if (isset($map[$requestPath])) {
            session_start();
            ob_start();

            $resource = explode('@', $map[$requestPath]);
            $controllerPath = 'App\Controller\\' . $resource[0];
            $controllerMethod = $resource[1];

            try {
                $controller = Container::instance()->get($controllerPath);
            } catch (\Exception $e) {
                var_dump($e->getMessage());exit();
                $this->stopExecution('Project has missing or invalid configuration', 'HTTP/1.1 422 Unprocessable Entity');
            }

            $controller->$controllerMethod($this->requestVars);

            echo ob_get_clean();

            $session = Container::instance()->get(SessionAdapter::class);
            if ($session->get('username') === null) {
                $session->clear();
            }
        } else {
            $this->stopExecution('Page not found', 'HTTP/1.1 404 Not Found');
        }
    }

    /**
     * Load xml config and inject configured dependencies
     */
    private function setProjectDependencies()
    {
        $xml = simplexml_load_string(file_get_contents($this->configPath . 'services.xml'))
            or $this->stopExecution('Config parse error', 'HTTP/1.1 422 Unprocessable Entity');
        $xmlArr = json_decode(json_encode($xml), true);

        $abstractClasses = in_array('abstract', array_keys($xmlArr['dependencies']['class']), true) ?
            [$xmlArr['dependencies']['class']] : $xmlArr['dependencies']['class'];

        foreach($abstractClasses as $dependency) {
            Container::instance()->set($dependency['abstract'], $dependency['default']);
        }
    }

    /**
     * @param string $message Error message
     * @param string $status Response header
     */
    private function stopExecution(string $message = '', string $status = '')
    {
        header($status);
        echo "<h1>$message</h1>";
        die();
    }
}

