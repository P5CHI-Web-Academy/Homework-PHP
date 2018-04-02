<?php


class Router
{
	private $routes;
	
	public function __construct()
	{
		$routesArr = ROOT.'/config/routes.php';
		$this->routes = include($routesArr);
	}

	private function getUri()
	{
		if (!empty($_SERVER['REQUEST_URI'])) {
			return $uri = trim($_SERVER['REQUEST_URI'], '/');
		}
	}

	public function run()
	{
		$uri = $this->getUri();
		
		foreach ($this->routes as $uriPatt => $path) {
			
			if (preg_match("~$uriPatt~", $uri)) {

				$internalRoute = preg_replace("~$uriPatt~", $path, $uri);
				//
				#$segments = explode('/', $path);
				$segments = explode('/', $internalRoute);

				$controllerName = ucfirst(array_shift($segments).'Controller');

				$actionName = 'action'.ucfirst(array_shift($segments));

				$parameters = $segments;

				$controllerFile = ROOT.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controllerName.'.php';

				if (file_exists($controllerFile)) {
					include_once($controllerFile);
				}

				$controllerObject = new $controllerName;
				#$result = $controllerObject->$actionName();
				$result = call_user_func_array(array($controllerObject, $actionName), $parameters);

				if ($result != null) {
					break;
				}

			}
		}

	}
}