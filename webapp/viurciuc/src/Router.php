<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 4/5/18
 * Time: 2:00 PM
 */

namespace App;


class Router
{
    /**
     * @var array
     */
    private $requestVars;


    /**
     * Kernel constructor.
     *
     * @param array $requestVars
     */
    public function __construct(array $requestVars = [])
    {
        $this->requestVars = $requestVars;

        $this->setProjectDependencies();
    }

    /**
     * Main initialization logic
     */
    public function run(): void
    {
        $map = array(
            '/'          => 'LoginController@index',
            '/login'     => 'LoginController@login',
            '/logout'    => 'LoginController@logout',
        );

        $requestPath = explode('?', $_SERVER['REQUEST_URI'])[0];

        if (isset($map[$requestPath])) {
            session_start();
            ob_start();

            $resource = explode('@', $map[$requestPath]);
            $controllerPath   = 'App\Controller\\'.$resource[0];
            $controllerMethod = $resource[1];

            try {
                $controller = Container::instance()->get($controllerPath);
            } catch (\Exception $e) {
                $this->stopExecution(
                    'Project has missing or invalid configuration',
                    'HTTP/1.1 422 Unprocessable Entity'
                );
            }

            $controller->$controllerMethod($this->requestVars);

            echo ob_get_clean();

            if ( ! isset($_SESSION['username'])) {
                session_destroy();
            }
        } else {
            $this->stopExecution('Page not found', 'HTTP/1.1 404 Not Found');
        }
    }

    /**
     * Load json config and inject configured dependencies
     */
    private function setProjectDependencies()
    {
        $json = json_decode(file_get_contents(CONFIG_PATH.'Services.json'))
        or $this->stopExecution(
            'Config parse error',
            'HTTP/1.1 422 Unprocessable Entity'
        );
        $jsonArr = json_decode(json_encode($json), true);
        foreach ($jsonArr['dependencies'] as $dependency) {
            Container::instance()->set(
                $dependency['abstract'],
                $dependency['default']
            );
        }
    }

    /**
     * @param string $message Error message
     * @param string $status  Response header
     */
    private function stopExecution(string $message = '', string $status = '')
    {
        header($status);
        echo "<h1>$message</h1>";
        die();
    }
}

