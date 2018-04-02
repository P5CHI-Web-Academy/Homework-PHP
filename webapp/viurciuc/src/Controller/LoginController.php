<?php

namespace App\Controller;

use App\Services\AuthService;
use App\Template;

class LoginController
{

    private $templatePath = __DIR__.'/../../src/Template/tpls/';

    /**
     * @var AuthService
     */
    private $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    private function redirect(string $path)
    {
        header('Location: http://'.$path);
        ob_end_flush();
        die();
    }

    public function index()
    {
        if ($this->auth->isAuthenticated()) {
            include $this->templatePath.'main/index.php';

            return;
        }

        include $this->templatePath.'login/index.php';
    }

    public function login(array $request = [])
    {
        if ($this->auth->isAuthenticated()) {
            $this->redirect($_SERVER['HTTP_HOST']);
        }

        if ( ! $this->auth->authenticate($request)) {
            $_SESSION['error'] = 'Username and/or password is invalid';

            $this->redirect($_SERVER['HTTP_HOST']);
        }

        $this->redirect($_SERVER['HTTP_HOST']);
    }

    public function logout()
    {
        $this->auth->logout();

        $this->redirect($_SERVER['HTTP_HOST']);
    }
}
