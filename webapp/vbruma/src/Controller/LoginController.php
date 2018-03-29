<?php

namespace App\Controller;

use App\Services\AuthService;

class LoginController extends AbstractController
{
    /**
     * @var AuthService
     */
    private $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    public function index(array $request = [])
    {
        if ($this->auth->isAuthenticated()) {
            include $this->templatePath . 'main/index.php';

            return;
        }

        include $this->templatePath . 'login/index.php';
    }

    public function login(array $request = [])
    {
        if ($this->auth->isAuthenticated()) {
            $this->redirect($_SERVER['HTTP_HOST']);
        }

        if (! $this->auth->authenticate($request)) {
            $_SESSION['error'] = 'Username and/or password is invalid';

            $this->redirect($_SERVER['HTTP_HOST']);
        }

        $this->redirect($_SERVER['HTTP_HOST']);
    }

    public function logout(array $request = [])
    {
        $this->auth->logout();

        $this->redirect($_SERVER['HTTP_HOST']);
    }
}
